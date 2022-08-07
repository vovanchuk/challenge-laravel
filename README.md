
# Adapt Coding Challenge

## Tech Stack:
- PHP 8.1
- Laravel 9

## Installation:
- `cp .env.example .env`
- `docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs` (Installing dependencies without the need to use a local environment)
- `alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail` (optional)
- `sail up`
- `sail artisan key:generate`
- `sail artisan migrate`
- `sail artisan db:seed` (seed DB, optional)

## Tests
Code coverage report can be found in the `tests/reports/coverage` folder

Run tests: `sail artisan test`

Generate new report: `sail artisan test --coverage-html tests/reports/coverage`

## Postman collection
Can bee found in the `Challenge.postman_collection.json` file
