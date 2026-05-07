#!/usr/bin/env bash

set -e

# 1) Check if .env exists
if [ ! -f ".env" ]; then
    echo ".env file not found. Creating from .env.example..."

    if [ -d ".data/mysql" ] && [ ! -z "$(ls -A .data/mysql)" ]; then
        echo "There's a data in .data/mysql."
        echo "Setup .env manually OR remove MSQL data."
        echo "Exit..."
        exit 0
    fi

    # 2) Copy .env.example → .env
    cp .env.example .env

    # Generate random password (32 chars, alphanumeric)
    NEW_PASSWORD=$(openssl rand -base64 24 | tr -dc 'A-Za-z0-9' | head -c 32)

    # Replace the DB_PASSWORD line
    # Assumes the exact string "DB_PASSWORD=<input password there>"
    sed -i "s|DB_PASSWORD=<input password there>|DB_PASSWORD=${NEW_PASSWORD}|" .env

    echo ".env generated with random DB_PASSWORD."

    # get token
    read -p "Enter VALIDATORS_APP_TOKEN: " VALIDATORS_APP_TOKEN
    sed -i "s|VALIDATORS_APP_TOKEN=|VALIDATORS_APP_TOKEN=${VALIDATORS_APP_TOKEN}|" .env

    # get token
    read -p "Enter admin email: " ADMIN_EMAIL
    ADMIN_PASSWORD=$(openssl rand -base64 24 | tr -dc 'A-Za-z0-9' | head -c 32)
fi

############
## caddy ###
############
source "./setup_domain.sh"

# --- 1. Если Caddyfile существует — просто стартуем ---
if [[ -f "Caddyfile" ]]; then
  echo "✅ Caddyfile exists, starting 'docker compose'"
  ADMIN_EMAIL=$ADMIN_EMAIL ADMIN_PASSWORD=$ADMIN_PASSWORD docker compose up -d --build
  exit 0
fi

# --- 2. Запрос домена ---
read -p "Enter domain (example.com or localhost): " DOMAIN

# --- 3. localhost ---
if [[ "$DOMAIN" == "localhost" ]]; then
  echo "ℹ️ Using localhost configuration"
  write_localhost_caddyfile
  ADMIN_EMAIL=$ADMIN_EMAIL ADMIN_PASSWORD=$ADMIN_PASSWORD docker compose up -d --build
  exit 0
fi

# --- 4. Обычный домен ---
if ! validate_domain "$DOMAIN"; then
  exit 1
fi

echo "✅ Domain validated"

write_domain_caddyfile "$DOMAIN"

# 3) Run 'docker compose'
echo "Starting 'docker compose...'"
ADMIN_EMAIL=$ADMIN_EMAIL ADMIN_PASSWORD=$ADMIN_PASSWORD docker compose up -d --build
