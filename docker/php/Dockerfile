FROM php:8.3-fpm

# Build args for UID/GID
ARG UID=1000
ARG GID=1000

# Create generic user
RUN groupadd -g ${GID} appgroup \
 && useradd -u ${UID} -g appgroup -m appuser

WORKDIR /var/www

# Install system dependencies + PHP extensions
RUN apt-get update && apt-get install -y \
    zip unzip git curl libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy entrypoint
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Switch to appuser **before running anything that writes to /var/www**
USER appuser

# Set entrypoint
ENTRYPOINT ["entrypoint.sh"]

# Default command
CMD ["php-fpm"]
