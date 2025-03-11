# Courier Schedule Application

Приложение для создания и отображения расписания поездок курьеров в регионы. Расписание генерируется автоматически на основе данных о курьерах и регионах, где время поездки (туда и обратно) задается в часах.

## Особенности

- **Docker & Docker Compose**  
  Приложение запускается в трех контейнерах:
    - **web** – Nginx для проксирования запросов
    - **php** – PHP-FPM с Composer (автозагрузка по PSR‑4)
    - **db** – PostgreSQL для хранения данных
  

- **При запуске создается база, таблицы, и создание фейковых данных**  
 
## Установка и запуск

   ```bash
   git clone git@github.com:dvakhitov/courier.git
   cd project
   docker compose up -d --build
   docker compose exec php composer install
   ```

Для сброса и обновления данных запустите:
   ```bash
      docker compose down -v
      docker compose up -d --build
   ```

Приложение доступно по адресу http://lcoalhost:8080