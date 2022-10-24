#!/usr/bin/env sh

if [ "$1" = 'test' ]; then
    docker-compose run app php vendor/bin/behat
    docker-compose run app php vendor/bin/phpspec run
else
    docker-compose run app php app.php "$@"
fi
