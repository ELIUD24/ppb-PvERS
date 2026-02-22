#!/bin/sh
set -eu

if [ ! -f /var/www/html/app/Config/database.php ] && [ -f /var/www/html/app/Config/database.php.default ]; then
    cp /var/www/html/app/Config/database.php.default /var/www/html/app/Config/database.php
fi

exec "$@"
