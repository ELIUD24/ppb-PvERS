#!/usr/bin/env bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
ENV_FILE="${ENV_FILE:-$ROOT_DIR/.env}"
SQL_FILE="$ROOT_DIR/groups_acos_aros.sql"
USE_DOCKER=1

usage() {
  cat <<'EOF'
Usage: scripts/import_groups_acos.sh [options]

Imports the schema-only ACL tables from groups_acos_aros.sql into MySQL.

Options:
  --docker           Import through the docker compose `db` service (default)
  --local            Import through the local mysql client instead of Docker
  --sql PATH         SQL file to import (default: ./groups_acos_aros.sql)
  --host HOST        MySQL host override
  --port PORT        MySQL port override
  --user USER        MySQL user override
  --password PASS    MySQL password override
  --database NAME    Database name override
  --help             Show this help message

Examples:
  ./scripts/import_groups_acos.sh
  ./scripts/import_groups_acos.sh --local
  ./scripts/import_groups_acos.sh --docker
  ./scripts/import_groups_acos.sh --sql ./groups_acos_aros.sql --database pvers
EOF
}

require_value() {
  local option_name="$1"
  local option_value="${2:-}"

  if [[ -z "$option_value" ]]; then
    echo "Missing value for $option_name" >&2
    exit 1
  fi
}

load_env() {
  if [[ -f "$ENV_FILE" ]]; then
    set -a
    # shellcheck disable=SC1090
    . "$ENV_FILE"
    set +a
  fi
}

ensure_file_exists() {
  if [[ ! -f "$SQL_FILE" ]]; then
    echo "SQL file not found: $SQL_FILE" >&2
    exit 1
  fi
}

ensure_local_mysql() {
  if ! command -v mysql >/dev/null 2>&1; then
    echo "mysql client not found. Install MySQL client or run with --docker." >&2
    exit 1
  fi
}

ensure_docker_db() {
  if ! command -v docker >/dev/null 2>&1; then
    echo "docker is not installed. Run with --local or install Docker." >&2
    exit 1
  fi

  echo "Ensuring docker compose db service is running..."
  docker compose up -d db >/dev/null

  for attempt in $(seq 1 30); do
    if docker compose exec -T \
      -e MYSQL_PWD="$DB_PASSWORD" \
      db mysqladmin ping -h 127.0.0.1 -u "$DB_USER" --silent >/dev/null 2>&1; then
      return 0
    fi
    sleep 2
  done

  echo "Timed out waiting for the docker compose db service to become ready." >&2
  exit 1
}

create_database_local() {
  MYSQL_PWD="$DB_PASSWORD" mysql \
    --protocol=TCP \
    --default-character-set=utf8mb4 \
    -h "$DB_HOST" \
    -P "$DB_PORT" \
    -u "$DB_USER" \
    -e "CREATE DATABASE IF NOT EXISTS \`$DB_NAME\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
}

import_local() {
  MYSQL_PWD="$DB_PASSWORD" mysql \
    --protocol=TCP \
    --default-character-set=utf8mb4 \
    -h "$DB_HOST" \
    -P "$DB_PORT" \
    -u "$DB_USER" \
    "$DB_NAME" < "$SQL_FILE"
}

create_database_docker() {
  docker compose exec -T \
    -e MYSQL_PWD="$DB_PASSWORD" \
    db mysql \
    --default-character-set=utf8mb4 \
    -u "$DB_USER" \
    -e "CREATE DATABASE IF NOT EXISTS \`$DB_NAME\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
}

import_docker() {
  docker compose exec -T \
    -e MYSQL_PWD="$DB_PASSWORD" \
    db mysql \
    --default-character-set=utf8mb4 \
    -u "$DB_USER" \
    "$DB_NAME" < "$SQL_FILE"
}

load_env

DB_HOST="${DB_HOST:-127.0.0.1}"
DB_PORT="${DB_PORT:-${DB_FORWARD_PORT:-3306}}"
DB_USER="${DB_USER:-root}"
DB_PASSWORD="${DB_PASSWORD:-hatua.}"
DB_NAME="${DB_NAME:-pvers}"

while [[ $# -gt 0 ]]; do
  case "$1" in
    --docker)
      USE_DOCKER=1
      shift
      ;;
    --local)
      USE_DOCKER=0
      shift
      ;;
    --sql)
      require_value "$1" "${2:-}"
      SQL_FILE="$2"
      shift 2
      ;;
    --host)
      require_value "$1" "${2:-}"
      DB_HOST="$2"
      shift 2
      ;;
    --port)
      require_value "$1" "${2:-}"
      DB_PORT="$2"
      shift 2
      ;;
    --user)
      require_value "$1" "${2:-}"
      DB_USER="$2"
      shift 2
      ;;
    --password)
      require_value "$1" "${2:-}"
      DB_PASSWORD="$2"
      shift 2
      ;;
    --database)
      require_value "$1" "${2:-}"
      DB_NAME="$2"
      shift 2
      ;;
    --help|-h)
      usage
      exit 0
      ;;
    *)
      echo "Unknown option: $1" >&2
      usage >&2
      exit 1
      ;;
  esac
done

ensure_file_exists

echo "Import source: $SQL_FILE"

if [[ "$USE_DOCKER" -eq 1 ]]; then
  ensure_docker_db
  echo "Importing into docker MySQL database '$DB_NAME' via service 'db'..."
  create_database_docker
  import_docker
else
  ensure_local_mysql
  echo "Importing into MySQL database '$DB_NAME' on $DB_HOST:$DB_PORT..."
  create_database_local
  import_local
fi

echo "Import completed successfully."
echo "If this is a fresh ACL setup, run ./scripts/bootstrap_acl.sh once the app is up to rebuild ACOs and permissions."
