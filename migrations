#!/usr/bin/env bash

php artisan down --message="Upgrading Database" --retry=60

php artisan migrate:fresh --seed

php artisan db:import

php artisan up
