## Install

- В консоли:
composer install

- Для Unix:
sudo chmod -r 0777 storage && sudo chmod -r 0777 bootstrap/cache

- В консоли:
php artisan key:generate

- На основе .env.example создать .env

- В .env настроить параметры:
APP_NAME
APP_URL
DB_HOST
DB_DATABASE
DB_USERNAME
DB_PASSWORD

- В консоли:
php artisan migrate

- В консоли:
php artisan storage:link

- В консоли:
npm install

- В консоли:
npm run dev

- Настроить локальный домен на папку public
