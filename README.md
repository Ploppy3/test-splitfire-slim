# test-slim

## installation

`cd slim`

Install php dependencies with composer:

`docker run --rm --interactive --tty --volume ${PWD}:/app composer install`

Create php docker image:

`cd ..`

`docker-compose build`

## start server

`docker-compose up`

## restore database

Open `localhost:8080` in your web browser

```
System: MySQL
Server: mysql
Username: root
Password: pwd
```

Once signed-in, select the `db` database, import the `database.sql` file

## test

`[GET] localhost/tweets`

`[POST] localhost/tweets`
