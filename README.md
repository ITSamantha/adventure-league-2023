# Sovkombank DIGITAL

## Требования
- Docker Compose версии v2.3.3
- Docker версии 24.0.6
- ufw версии 0.36.2
- Composer версии 2.2.6
- php версии 8.1
- php8.1-dom
- php8.1-xml
- php8.1-curl
- php8.1-zip

## Инструкция по развертке приложения.

### Создайте локальную копию репозитория
```git clone https://git.codenrock.com/adventure-league-spb/cnrprod-team-61018/adventure-league-k2```

### Установите необходимые библиотеки для Laravel
```php api/artisan composer install```

### Заполните данные в файле окружения в соответствии с .env.example
``api/.env``
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

PYTHON_BOT_API_TOKEN=
NEURAL_NETWORK_API_TOKEN=
```

### Запустите контейнеры
```docker compose up -d```

### Настройте Firewall
```sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow OpenSSH

sudo ufw allow 80
sudo ufw allow 443

sudo ufw enable
```

### Сгенерируйте ключ приложения
```docker exec api_laravel php artisan key:generate```

### Запустите миграции и сидеры для базы данных
```
docker exec api_laravel php artisan migrate
docker exec api_laravel php artisan db:seed
```

### Настройте SSL сертификаты
В файле ``api/nginx/conf.d/laravel.conf`` закомментируйте строки ``ssl_certificate`` и ``ssl_certificate``. Также нужно убрать ключевое слово ssl в директиве listen 443;
После вводим комманду для обновления сертификатов:
```
docker compose run --rm --entrypoint "\
  certbot certonly --webroot -w /var/www/certbot \
    --email tyumin000@gmail.com \
    -d uvuv643.online \
    -d webhook.uvuv643.online \
    -d photos.uvuv643.online \
    --rsa-key-size 4096 \
    --agree-tos \
    --force-renewal" certbot
```

Где в опциях -d ваши домены для соответствующих сервисов.

После в выводе команды копируем пути к сертификатам, раскомментируем строки ``ssl_certificate`` и ``ssl_certificate`` в файле ``api/nginx/conf.d/laravel.conf``, добавляем соответствующие пути к сертификатам, добавляем убранное ранне ключевое слово ssl и перезапускаем сервер: ```docker compose restart nginx```

## Инструкция по обновлению приложения.
```
git pull
composer update
docker exec api_laravel php artisan migrate
docker exec api_laravel php artisan db:seed
```

## Спасибо за использование нашего сервиса!