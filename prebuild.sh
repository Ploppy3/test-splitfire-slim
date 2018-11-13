cd slim
docker run --rm --interactive --tty --volume ${PWD}:/app composer:1.7.3 install || docker run --rm --interactive --tty --volume /${PWD}:/app composer:1.7.3 install
cd ..