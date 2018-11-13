<?php

namespace controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class TweetsController
{

    public function __construct($container)
    {
        $this->pdo = $container->get('pdo');
    }

    public function __invoke($req, $res)
    {
        // not used
    }

    public function get(ServerRequestInterface $req, ResponseInterface $res)
    {
        $_page = intval($req->getQueryParam('page'));
        $_count = intval($req->getQueryParam('count'));
        $page = $_page > 0 ? $_page : 1;
        $count = $_count > 0 ? $_count : 25;
        $username = $req->getQueryParam('author');
        $hashtag = $req->getQueryParam('hashtag');

        $conditionFilterByUser = $username ? "WHERE tweet.user = :username" : null;
        $conditionFilterByHashtag = $hashtag ? "HAVING hashtags REGEXP :hashtag" : null;

        $stmt_fetchTweets = $this->pdo->prepare(
            "SELECT tweet.id, tweet.user, tweet.message, tweet.date, GROUP_CONCAT(hashtag) AS hashtags
            FROM `tweets`
            AS tweet
            LEFT JOIN (SELECT * FROM `hashtags`) AS hashtag
            ON tweet.id = hashtag.idTweet
            $conditionFilterByUser
            GROUP BY id
            $conditionFilterByHashtag
            LIMIT :limit
            OFFSET :offset"
        );
        $stmt_fetchTweets->bindValue(":limit", $count, \PDO::PARAM_INT);
        $stmt_fetchTweets->bindValue(":offset", ($page - 1) * $count, \PDO::PARAM_INT);
        if ($conditionFilterByUser) {
            $stmt_fetchTweets->bindValue(":username", $username);
        }
        if ($conditionFilterByHashtag) {
            $stmt_fetchTweets->bindValue(":hashtag", "(^|,){$hashtag}(,|$)");
        }

        if ($stmt_fetchTweets->execute()) {
            $tweets = $stmt_fetchTweets->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($tweets as &$tweet) {
                $tweet['id'] = intval($tweet['id']);
                $tweet['hashtags'] = ($tweet['hashtags'] === null) ? array() : explode(',', $tweet['hashtags']);
                $date = new \DateTime($tweet['date']);
                $tweet['date'] = $date->format('d/m/Y H:i:s');
            }
            return $res->withJson($tweets);
        } else {
            return $res->withJson('could not fetch tweets', 500);
        }
    }

    public function post(ServerRequestInterface $req, ResponseInterface $res)
    {
        $reqBody = $req->getParsedBody();

        $username = $reqBody['author'];
        $message = $reqBody['message'];
        $hashtags = is_array($reqBody['hashtags']) ? $reqBody['hashtags'] : [];
        $currentDateTime = new \DateTime();

        if (isset($username, $message)) {
            $stmt_insertTweet = $this->pdo->prepare(
                "INSERT INTO `tweets`(`id`, `user`, `date`, `message`)
                VALUES (default, :user, :date, :message)"
            );
            $stmt_insertTweet->bindValue(":user", $username);
            $stmt_insertTweet->bindValue(":date", $currentDateTime->format('Y-m-d H:i:s'));
            $stmt_insertTweet->bindValue(":message", $message);
            if ($stmt_insertTweet->execute()) {
                $idTweet = $this->pdo->lastInsertId();
                $this->insertHashTags($idTweet, $hashtags, $res);
                return $res->withJson(["id" => $idTweet], 201);
            } else {
                return $res->withJson("an error occured", 500);
            }
        } else {
            return $res->withJson("required fields are [user:string, message:string]");
        }
        return $res;
    }

    private function insertHashTags(int $id, array $hashtags, ResponseInterface $res)
    {
        foreach ($hashtags as $hashtag) {
            $stmt_insertHashtag = $this->pdo->prepare("INSERT INTO `hashtags`(`idTweet`, `hashtag`) VALUES (:idTweet, :hashtag)");
            $stmt_insertHashtag->bindValue(":idTweet", $id);
            $stmt_insertHashtag->bindValue(":hashtag", $hashtag);
            if ($stmt_insertHashtag->execute() === false) {
                return $res->withJson(["id" => $id, "error" => "could not save hashtags"], 500);
            }
        }
    }
}
