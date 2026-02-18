#!/bin/sh
set -e

PORT_TO_USE="${PORT:-10000}"

sed -i "s/Listen 80/Listen ${PORT_TO_USE}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${PORT_TO_USE}>/" /etc/apache2/sites-available/000-default.conf

sh ./init-db.sh || true

exec apache2-foreground
