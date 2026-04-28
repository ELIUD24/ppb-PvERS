#!/usr/bin/env bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
ENV_FILE="${ENV_FILE:-$ROOT_DIR/.env}"
APP_HOST="${APP_HOST:-127.0.0.1}"
APP_PORT="${APP_PORT:-9191}"

usage() {
  cat <<'EOF'
Usage: scripts/bootstrap_acl.sh [options]

Rebuilds the CakePHP ACL tree and group permissions through the running app.

Options:
  --host HOST   App host override (default: 127.0.0.1)
  --port PORT   App port override (default: APP_PORT from .env, else 9191)
  --help        Show this help message

Example:
  ./scripts/bootstrap_acl.sh
EOF
}

load_env() {
  if [[ -f "$ENV_FILE" ]]; then
    set -a
    # shellcheck disable=SC1090
    . "$ENV_FILE"
    set +a
  fi
}

require_value() {
  local option_name="$1"
  local option_value="${2:-}"

  if [[ -z "$option_value" ]]; then
    echo "Missing value for $option_name" >&2
    exit 1
  fi
}

ensure_curl() {
  if ! command -v curl >/dev/null 2>&1; then
    echo "curl is required to bootstrap ACL permissions." >&2
    exit 1
  fi
}

load_env
APP_PORT="${APP_PORT:-9191}"

while [[ $# -gt 0 ]]; do
  case "$1" in
    --host)
      require_value "$1" "${2:-}"
      APP_HOST="$2"
      shift 2
      ;;
    --port)
      require_value "$1" "${2:-}"
      APP_PORT="$2"
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

ensure_curl

BOOTSTRAP_URL="http://${APP_HOST}:${APP_PORT}/users/initDB"

echo "Bootstrapping ACL permissions via ${BOOTSTRAP_URL} ..."
curl --fail --silent --show-error "$BOOTSTRAP_URL" >/dev/null
echo "ACL bootstrap completed successfully."
