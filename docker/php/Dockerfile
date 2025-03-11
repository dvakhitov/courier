FROM php:8.4-fpm

# Устанавливаем необходимые пакеты: postgresql-client, git, libpq-dev (для pdo_pgsql)
RUN apt-get update && apt-get install -y \
    postgresql-client \
    git \
    libpq-dev

# Устанавливаем расширение pdo_pgsql
RUN docker-php-ext-install pdo_pgsql

# Копируем entrypoint скрипт и делаем его исполняемым
COPY ./entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Сделаем composer глобально доступным
RUN ln -s /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]
