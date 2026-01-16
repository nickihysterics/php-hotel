#!/usr/bin/env bash
set -euo pipefail

root_dir="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$root_dir"

docker compose up -d --build

wait_for_url() {
  local url="$1"
  local timeout="${2:-60}"
  local start="$SECONDS"

  until curl -fsS --max-time 2 "$url" >/dev/null 2>&1; do
    if (( SECONDS - start >= timeout )); then
      echo "Не удалось дождаться: $url"
      return 1
    fi
    sleep 1
  done
}

open_url() {
  local url="$1"

  if command -v open >/dev/null 2>&1; then
    open "$url"
    return 0
  fi

  if command -v xdg-open >/dev/null 2>&1; then
    xdg-open "$url"
    return 0
  fi

  echo "Открой вручную: $url"
}

urls=(
  "http://localhost/"
  "http://localhost/admin/login.php"
  "http://localhost:8081/"
)

for url in "${urls[@]}"; do
  wait_for_url "$url" 60
done

for url in "${urls[@]}"; do
  open_url "$url"
done
