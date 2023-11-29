#!/usr/bin/env bash

php bin/console doctrine:database:create --no-interaction
php bin/console doctrine:migrations:migrate --no-interaction

php bin/console doctrine:database:create --no-interaction --env=test
php bin/console doctrine:migrations:migrate --no-interaction --env=test

for i in $(seq 1 $1); do
  TEST_TOKEN=$i php bin/console doctrine:database:create --no-interaction --env=test
  TEST_TOKEN=$i php bin/console doctrine:migrations:migrate --no-interaction --env=test
done