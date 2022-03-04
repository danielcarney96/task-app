# Task board

This is a Laravel 9 app using InertiaJS, React, Tailwind and MySQL.

## Starting the project

1. Checkout the project
2. Enter the project directory
3. Run `docker-compose up`
4. Run `npm run watch` in the container
5. Go to `localhost`

## Entering container

To enter the container, use `docker-compose exec app bash`

## Connecting to the database

- host: 127.0.0.1
- username: root
- database: app
- port: 3306

## Code quality tools

The project uses a number of tools to ensure common issues are caught and that code is consistent.

They should be called manually before creating a PR, if you forget though, the github action will pick it up.

**php-cs-fixer:** `./vendor/bin/php-cs-fixer fix`

**PHPStan:** `./vendor/bin/phpstan analyse`

## Testing

Pest is used for PHP unit testing in this project. Testing can be done in the container using one of two commands:

`php artisan test`

`./vendor/bin/pest`

Testing is also done automatically with a github action.
