FROM postgres:17.0-alpine

# Копируем наш кастомный скрипт в /usr/local/bin
COPY docker/postgresql/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Переопределяем ENTRYPOINT, чтобы сначала выполнялся наш скрипт,
# а затем стандартный запуск PostgreSQL.
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["postgres"]

