#!/usr/bin/env bash

# dev db
/app/bin/console doctrine:migrations:migrate first -n
yes | /app/bin/console doctrine:migrations:migrate
yes | /app/bin/console doctrine:fixtures:load

# test db
/app/bin/console doctrine:database:create --env=test
/app/bin/console doctrine:schema:update --env=test --force
yes | /app/bin/console doctrine:fixtures:load --env=test

# run dev server
php -S 0.0.0.0:8337 -t public
