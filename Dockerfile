# Usamos una imagen oficial de PHP con Apache
FROM php:8.1-apache

# Instalamos la extensión pdo_mysql necesaria para tu conexión
RUN docker-php-ext-install pdo pdo_mysql

# Activamos mod_rewrite por si lo necesitas en el futuro
RUN a2enmod rewrite

# Copiamos el código actual al contenedor
COPY . /var/www/html/

# Damos permisos al usuario de www-data
RUN chown -R www-data:www-data /var/www/html