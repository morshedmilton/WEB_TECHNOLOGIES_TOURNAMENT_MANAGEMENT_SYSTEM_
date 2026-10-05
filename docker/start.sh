#!/bin/sh
# Container entrypoint: bind Apache to $PORT (Render/Railway/Fly inject it),
# create the schema + demo data on first boot, then serve.
set -e
PORT="${PORT:-80}"
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

if [ "${RUN_DB_SETUP:-true}" = "true" ]; then
    php /var/www/html/scripts/setup.php || echo "WARNING: database setup failed - check DB_* environment variables"
fi

exec apache2-foreground
