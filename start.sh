#!/bin/sh
set -e
sh ./init-db.sh || true

# Render web services expect your app to bind to $PORT (default 10000). :contentReference[oaicite:0]{index=0}
PORT_TO_USE="${PORT:-10000}"

# Update Apache to listen on Render's port
sed -i "s/Listen 80/Listen ${PORT_TO_USE}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${PORT_TO_USE}>/" /etc/apache2/sites-available/000-default.conf

exec apache2-foreground
