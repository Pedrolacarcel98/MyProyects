# Usamos una imagen oficial de PHP 8.2 con Apache
FROM php:8.2-apache

# Instalar las extensiones de PHP que he visto en tu código
# mysqli -> para connectDb2.php
# pdo_mysql -> para conectDb.php
# fileinfo -> para videos.php (validar MIME)
RUN docker-php-ext-install mysqli pdo pdo_mysql fileinfo

# Habilitamos mod_rewrite de Apache (buena práctica para URLs amigables)
RUN a2enmod rewrite