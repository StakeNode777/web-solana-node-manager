#!/bin/bash

cd /var/www/html

# 1. Ensure assets folder exists
if [ ! -d "web/assets" ]; then
    echo "📌 Creating web/assets directory..."
    mkdir -p web/assets
fi

# 2. Ensure correct permissions for assets
echo "📌 Setting permissions for web/assets..."
chmod -R 777 web/assets

# 3. Install composer deps if vendor folder does not exist
if [ ! -d "vendor" ]; then
    echo "📌 Running composer install..."
    composer install --prefer-dist --no-interaction
fi

# 4. Run Yii2 migrations (ignore errors to avoid breaking container)
echo "📌 Running Yii2 migrations..."
php yii migrate --interactive=0 || true

if [[ -n "$ADMIN_PASSWORD" ]]; then
    echo "ADMIN_PASSWORD: $ADMIN_PASSWORD"
    php yii user/add-admin-user $ADMIN_EMAIL $ADMIN_PASSWORD
fi

# 5. Start Apache
#echo "📌 Starting Apache..."
#apache2-foreground

echo "Starting PHP-FPM..."
exec "$@"
