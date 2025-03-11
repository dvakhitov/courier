## Установка и запуск


   ```bash
   git clone git@github.com:dvakhitov/courier.git
   cd project
   docker compose up -d --build
   docker compose exec php composer install
   ```
   
Приложение доступно по адресу http://lcoalhost:8080