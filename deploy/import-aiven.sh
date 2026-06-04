#!/bin/bash
# Import local CRM dump into Aiven MySQL (managed DB — no SUPER privileges).
# Usage: export AIVEN_DB_PASSWORD='your-password' && ./deploy/import-aiven.sh

set -e

BACKUP="${1:-$HOME/rise_crm_backup.sql}"
CLEANED="${BACKUP%.sql}_aiven.sql"

if [[ ! -f "$BACKUP" ]]; then
  echo "Backup not found: $BACKUP"
  exit 1
fi

if [[ -z "$AIVEN_DB_PASSWORD" ]]; then
  echo "Set your Aiven password first:"
  echo "  export AIVEN_DB_PASSWORD='your-password'"
  exit 1
fi

echo "Cleaning dump for Aiven..."
# Disable primary key requirement for the session (Aiven default is ON)
echo "SET @@SESSION.sql_require_primary_key = 0;" > "$CLEANED"
sed -e '/^SET @@SESSION.SQL_LOG_BIN/d' \
    -e '/^SET @MYSQLDUMP_TEMP_LOG_BIN/d' \
    -e '/^SET @@GLOBAL.GTID_PURGED/d' \
    -e '/^SET @@SESSION.SQL_LOG_BIN = @MYSQLDUMP_TEMP_LOG_BIN/d' \
    -e '/^CREATE DATABASE/d' \
    -e '/^USE `/d' \
    -e 's/DEFINER=[^*]*\*/\*/g' \
    -e 's/SQL SECURITY DEFINER/SQL SECURITY INVOKER/g' \
    "$BACKUP" >> "$CLEANED"

echo "Importing into Aiven..."
mysql \
  --host="${AIVEN_DB_HOST:-mysql-3532f2b9-rise-crm.l.aivencloud.com}" \
  --port="${AIVEN_DB_PORT:-11975}" \
  --user="${AIVEN_DB_USER:-avnadmin}" \
  --password="$AIVEN_DB_PASSWORD" \
  --ssl-mode=REQUIRED \
  "${AIVEN_DB_NAME:-defaultdb}" < "$CLEANED"

echo "Done. Imported from $CLEANED"
