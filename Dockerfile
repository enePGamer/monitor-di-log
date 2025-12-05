# Base image PHP + Apache
FROM php:8.2-apache

# Install MySQL server + utilities
RUN apt-get update && \
    DEBIAN_FRONTEND=noninteractive apt-get install -y \
        default-mysql-server \
        supervisor && \
    rm -rf /var/lib/apt/lists/*

# Enable PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy application code
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html

# Copy supervisord configuration
COPY supervisord.conf /etc/supervisor/supervisord.conf

# Expose only Apache (Render expects one port)
EXPOSE 80

# Start supervisor (which starts MySQL + Apache)
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]

# Add MySQL init script
COPY init.sql /docker-entrypoint-initdb.d/