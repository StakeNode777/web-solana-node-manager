# Use a PHP-Apache image as the base
#FROM php:8.2-apache
FROM php:8.2-fpm

# Install system dependencies and PHP extensions required by Yii2
# `libicu-dev` for the `intl` extension
# `libonig-dev` for the `mbstring` extension
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libicu-dev \
    libonig-dev \
    curl \
    #libssl-dev \
    pkg-config \
    build-essential \
    #ca-certificates \
    imagemagick \
    libmagickwand-dev \
    --no-install-recommends \
    && rm -rf /var/lib/apt/lists/*

RUN pecl install imagick \
    && docker-php-ext-enable imagick

RUN sh -c "$(curl -sSfL https://release.anza.xyz/stable/install)"

ENV PATH="/root/.local/share/solana/install/active_release/bin:${PATH}"

# Install the PHP extensions
# `docker-php-ext-install` is a helper script provided by the official PHP images
RUN docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) pdo_mysql opcache intl mbstring

# Enable Apache rewrite module
#RUN a2enmod rewrite

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

    # Set the working directory inside the container
    #WORKDIR /var/www/html

    # Adjust user/group for file permissions
    # The default user for php:apache images is www-data
    # Let's make sure it matches the host user for consistency
    #RUN usermod -u 1000 www-data

# Copy entrypoint script
COPY ./docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

WORKDIR /var/www/html

ENTRYPOINT ["/entrypoint.sh"]

# Start PHP-FPM
CMD ["php-fpm"]