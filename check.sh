#!/usr/bin/env bash

set -e

# 1) Check if .env exists
if [ -z "$(ls -A .data/mysql)" ]; then
    echo "Directory is empty"
else
    echo "Directory is NOT empty"
fi
