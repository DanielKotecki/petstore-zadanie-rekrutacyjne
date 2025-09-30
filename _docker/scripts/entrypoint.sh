#!/bin/sh
set -e

# Ustaw domyślne uprawnienia dla nowych plików i katalogów
umask 000

# Napraw właściciela całego projektu, jeśli podano HOST_UID/HOST_GID
if [ -n "$HOST_UID" ] && [ -n "$HOST_GID" ]; then
    echo ">> Ustawiam właściciela projektu na $HOST_UID:$HOST_GID"
    find /var/www/html ! -path "/var/www/html/storage*" ! -path "/var/www/html/bootstrap/cache*" ! -path "/var/www/html/_docker/mysql-data*" \
        -exec chown $HOST_UID:$HOST_GID {} +
fi

# Uprawnienia dla storage i bootstrap/cache (Laravel musi móc pisać)
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Instalacja zależności, jeśli vendor nie istnieje
if [ ! -d "/var/www/html/vendor" ]; then
    composer install
fi

# Uruchom php-fpm
exec php-fpm
