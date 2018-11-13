<?php

require 'vendor/autoload.php';

$servername = "mysql";
$username = "root";
$password = "pwd";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=db", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully";
} catch (PDOException $e) {
    exit("Connection failed: " . $e->getMessage());
}

$app = new \Slim\App();

$container = $app->getContainer();
$container['pdo'] = $pdo;

$app->get('/tweets', controllers\TweetsController::class . ':get');
$app->post('/tweets', controllers\TweetsController::class . ':post');

$app->run();
