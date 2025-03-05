FROM php:8.4-fpm

# Устанавливаем необходимые пакеты (если pg_isready отсутствует, его можно установить из postgresql-client)
RUN apt-get update && apt-get install -y postgresql-client

# Копируем entrypoint скрипт и делаем его исполняемым
COPY ./entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

WORKDIR /var/www/html

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]
