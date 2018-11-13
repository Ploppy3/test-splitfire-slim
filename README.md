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