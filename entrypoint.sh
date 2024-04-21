#!/usr/bin/env bash

/app/bin/console doctrine:migrations:migrate first -n
yes | /app/bin/console doctrine:migrations:migrate
yes | /app/bin/console doctrine:fixtures:load

php -S 0.0.0.0:8337 -t public
