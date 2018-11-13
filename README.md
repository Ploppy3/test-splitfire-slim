
# Test-Splitfire-Slim

[Objectifs](https://gist.github.com/helitik/8e198adf0f7c82b067af89132a29a7ff)

## Installation w/ Docker

Install project dependencies with composer:

`./prebuild.sh`

Build php-apache Docker image:

`docker-compose build`

## Start servers

`docker-compose up`

## Restore database

Open Adminer at `localhost:8080` in your web browser

Sign in:

- System: MySQL
- Server: mysql
- Username: root
- Password: pwd

Select the `db` database, import the `database.sql` dump file

## Test endpoints

`[GET] localhost/tweets`

`[POST] localhost/tweets`
