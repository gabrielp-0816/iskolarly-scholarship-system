# Use PHP 8.2 Alpine for a lightweight, production-ready image
FROM php:8.2-alpine

# Install PostgreSQL developer libraries and PHP PDO PostgreSQL extensions
RUN apk add --no-cache postgresql-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Set the working directory in the container
WORKDIR /var/www/html

# Copy all project files to the container
COPY . .

# Expose port 10000 (standard port for Render, though dynamic PORT is used)
EXPOSE 10000

# Start PHP built-in web server routing all requests through api/index.php
# Render automatically injects the PORT environment variable.
CMD sh -c "php -S 0.0.0.0:\${PORT:-10000} api/index.php"
