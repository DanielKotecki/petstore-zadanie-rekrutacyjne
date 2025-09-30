# Bazowy obraz PHP z FPM
FROM php:8.4-fpm

# Instalacja zależności systemowych
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip curl git sudo netcat-openbsd \
    && docker-php-ext-install pdo_mysql zip

# Instalacja Composera
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Ustaw katalog roboczy
WORKDIR /var/www/html

# Skopiuj pliki projektu
COPY . .

# Skopiuj skrypty startowe i nadaj prawa wykonania
COPY _docker/scripts/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Ustaw domyślnego użytkownika na root
USER root

# Domyślne uprawnienia dla nowych plików
RUN echo "umask 000" >> /etc/profile \
    && echo "umask 000" >> /etc/bash.bashrc

# Domyślne wejście
ENTRYPOINT ["/entrypoint.sh"]
