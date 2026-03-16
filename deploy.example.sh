#!/usr/bin/env bash

set -euo pipefail

# Copy the app to shared hosting with rsync.
# Copy this file to deploy.sh and fill in your real values locally.
REMOTE_USER="replace_me"
REMOTE_HOST="replace_me"
SSH_PORT="22"
REMOTE_PATH="/home/replace_me/public_html/words-you-today/"
LOCAL_PATH="$(cd "$(dirname "$0")" && pwd)/"

if [[ "$REMOTE_USER" == "replace_me" || "$REMOTE_HOST" == "replace_me" || "$REMOTE_PATH" == "/home/replace_me/public_html/words-you-today/" ]]; then
  echo "Copy deploy.example.sh to deploy.sh and set REMOTE_USER, REMOTE_HOST, SSH_PORT, and REMOTE_PATH first."
  exit 1
fi

rsync -avz --delete \
  -e "ssh -p ${SSH_PORT}" \
  --exclude '.git/' \
  --exclude '.DS_Store' \
  --exclude '.env' \
  --exclude '*.log' \
  --exclude 'deploy.local.sh' \
  --exclude 'wyt-config.php' \
  --exclude 'wyt-config.example.php' \
  "$LOCAL_PATH" \
  "${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_PATH}"

echo "Deploy complete."
