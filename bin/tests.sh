#!/bin/bash

export APP_ENV="test"

COLOR_GREEN='\033[0;32m'
COLOR_DEFAULT='\033[0m'
COLOR_RED='\033[0;31m'

CONTAINER_NAME=$(docker ps --format "{{.Names}}" | grep -E 'app|wine-metrics' | head -n 1)

set -e

function echo_color() {
    echo ${2:-$COLOR_DEFAULT}
    echo $1
    echo $COLOR_DEFAULT
}

if [ -f "vendor/bin/php-cs-fixer" ]; then
        echo_color "Running PHP-CS-Fixer.." $COLOR_GREEN
        ./vendor/bin/php-cs-fixer fix || exit 1
fi

if [ -f "vendor/bin/phpstan" ]; then
        echo_color "Running PHPStan.." $COLOR_GREEN
        ./vendor/bin/phpstan analyse -c phpstan.dist.neon || exit 1
fi

if [ -f "vendor/bin/phpcs" ]; then
        echo_color "Running PHP_CodeSniffer.." $COLOR_GREEN
        ./vendor/bin/phpcs -n || exit 1
fi

if [ -f "vendor/bin/rector" ]; then
        echo_color "Running Rector.." $COLOR_GREEN
        ./vendor/bin/rector || exit 1
fi

if [ -z "$CONTAINER_NAME" ]; then
    echo_color "🐳 ❗ No running Symfony container found!" $COLOR_RED

    echo_color "🖥️ Running tests locally.." $COLOR_GREEN

    ./vendor/bin/phpunit
    ./vendor/bin/behat
    exit
fi

echo_color "🐳 Running tests in docker container.." $COLOR_GREEN

docker exec -t "$CONTAINER_NAME" ./vendor/bin/phpunit
docker exec -t "$CONTAINER_NAME" ./vendor/bin/behat
exit
