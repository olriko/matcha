FROM php:7.1-fpm
LABEL maintainer="olivier.hamon@washmee.fr"

# Installing dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    mysql-client \
    libmcrypt-dev \
    locales \
    zip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Installing extensions
RUN docker-php-ext-install mcrypt pdo_mysql

# Setting locales
RUN echo fr_FR.UTF-8 UTF-8 > /etc/locale.gen && locale-gen

RUN usermod -u 1000 www-data
RUN usermod -G staff www-data

# Changing Workdir
WORKDIR /var/www
