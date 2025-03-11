#!/bin/bash
set -e

# Установка зависимостей через Composer
echo "Установка зависимостей через Composer..."
composer install

echo "Ожидание подключения к PostgreSQL..."

# Экспортируем переменную для psql
export PGPASSWORD="$POSTGRES_PASSWORD"

# Ожидание подключения к PostgreSQL (хост: db, порт: 5432)
until pg_isready -h db -p 5432 -U "$POSTGRES_USER" -d "$POSTGRES_DB" &>/dev/null; do
  echo "PostgreSQL не готов, ждем 2 секунды..."
  sleep 2
done

echo "PostgreSQL готов. Запуск PHP-FPM..."
echo "POSTGRES_USER=$POSTGRES_USER"
echo "POSTGRES_DB=$POSTGRES_DB"

# Функция проверки таблицы
check_table() {
  local table_name="$1"
  local expected_cols="$2"
  local actual_cols
  actual_cols=$(psql -h db -p 5432 -U "$POSTGRES_USER" -d "$POSTGRES_DB" -t -c \
    "SELECT count(*) FROM information_schema.columns WHERE table_schema = 'public' AND table_name = '$table_name';" | xargs)
  if [ -z "$actual_cols" ] || [ "$actual_cols" -ne "$expected_cols" ]; then
    echo "Таблица '$table_name' отсутствует или схема не соответствует (найдено $actual_cols колонок, ожидалось $expected_cols)."
    return 1
  else
    echo "Таблица '$table_name' существует и схема соответствует (количество колонок: $actual_cols)."
    return 0
  fi
}

need_init=false

if ! check_table "couriers" 2; then
  need_init=true
fi

if ! check_table "regions" 3; then
  need_init=true
fi

if ! check_table "trips" 5; then
  need_init=true
fi

if [ "$need_init" = true ]; then
    echo "Не все таблицы существуют или схема не соответствует. Выполняется импорт схемы и начальных данных."
    SCHEMA_FILE="/docker-entrypoint-initdb.d/schema.sql"
    SEED_FILE="/docker-entrypoint-initdb.d/seed.sql"

    if [ -f "$SCHEMA_FILE" ]; then
      echo "Импорт схемы из $SCHEMA_FILE..."
      psql -h db -p 5432 -U "$POSTGRES_USER" -d "$POSTGRES_DB" -f "$SCHEMA_FILE"
    else
      echo "Файл схемы $SCHEMA_FILE не найден!"
    fi

    if [ -f "$SEED_FILE" ]; then
      echo "Импорт начальных данных из $SEED_FILE..."
      psql -h db -p 5432 -U "$POSTGRES_USER" -d "$POSTGRES_DB" -f "$SEED_FILE"
    else
      echo "Файл с начальными данными $SEED_FILE не найден!"
    fi

    echo "Инициализация базы данных завершена."
else
    echo "База данных и все таблицы существуют, и схема соответствует. Инициализация не требуется."
fi

echo "Запуск PHP-FPM..."
exec php-fpm
