# Use the latest PHP-FPM image
FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip curl libpq-dev libonig-dev libzip-dev zip libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring xml

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy project files
COPY . .

# Ensure the /app/var directory exists before setting permissions
RUN mkdir -p /app/var && chmod -R 777 /app/var

# Install Symfony CLI properly (without the broken installer)
RUN curl -sSLo /usr/local/bin/symfony https://github.com/symfony-cli/symfony-cli/releases/latest/download/symfony_linux_amd64 \
    && chmod +x /usr/local/bin/symfony

# Start PHP-FPM when the container runs
CMD ["php-fpm"]
