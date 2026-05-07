#!/usr/bin/env bash

set -e

# Stop docker-compose
echo "Stopping docker-compose..."
docker compose down --rmi all
