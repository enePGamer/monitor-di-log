# --- BASE IMAGE con PHP + Apache ---
FROM php:8.2-apache

# Abilita estensioni PHP necessarie (PDO MySQL)
RUN docker-php-ext-install pdo pdo_mysql

# Abilita mod_rewrite se necessario
RUN a2enmod rewrite

# Copia l’applicazione nel DocumentRoot Apache
COPY . /var/www/html/

# Permessi
RUN chown -R www-data:www-data /var/www/html

# Espone la porta usata da Apache
EXPOSE 80

# Comando di avvio (già quello di apache)
CMD ["apache2-foreground"]