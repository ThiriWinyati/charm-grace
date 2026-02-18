#!/usr/bin/env sh
set -e

# Only run if enabled
if [ "$RUN_DB_INIT" != "true" ]; then
  echo "RUN_DB_INIT is not true, skipping DB import"
  exit 0
fi

echo "Importing database..."
mysql --ssl-mode=REQUIRED \
  -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" -p"$DB_PASS" \
  "$DB_NAME" < cosmetics_store.sql

echo "DB import finished"
