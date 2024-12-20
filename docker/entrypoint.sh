#!/bin/bash

if [ ! -f "vendor/autoload.php" ]; then
    composer install --no-progress --no-interaction
fi

if [ ! -f ".env" ]; then
    echo "Creating env file for env $APP_ENV"
    cp .env.example .env
else
    echo "env file exists."
fi

role=${CONTAINER_ROLE:-app}

if [ "$role" = "app" ]; then
    php -v
    composer install
    php artisan migrate
    php db:seed
    php artisan key:generate
    php artisan cache:clear
    php artisan config:clear
    php artisan route:clear
    exec docker-php-entrypoint "$@"
fi
