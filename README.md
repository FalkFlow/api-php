# Para correr el projecto

## Paso 1 

Se debe corregir el **.env** del projecto por lo siguiente

```git
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:5prdgT0e96CU6mtvzVaO0a84BY/XBR6yooe8BPr4Zuo=
APP_DEBUG=true
APP_URL=http://localhost

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
# APP_MAINTENANCE_STORE=database

PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=mysql-contenedor
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=12345

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
# CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"

AUTH_PASSWORD_TIMEOUT= 100

TRANSBANK_ENVIROMENT="integration"
TRANBANK_COMMERCE_CODE=597055555532
TRANBANK_API_KEY="579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C"

EXCHANGE_API_KEY="c37d76d3ff2cbf0f414897d0d5626af8"
```

## Paso 2

Recordar Tener instalado **Docker Desktop** y **Git**

``` Git
git clone https://github.com/FalkFlow/api-php
cd api-php

# 2. Copian el .env por el que esta arriba

docker-compose up -d
docker exec -it laravel_app bash
composer install
php artisan key:generate
php artisan migrate
```

verificar que dentro de **nginx/conf.d/default.conf** se vea lo siguiente
```
server{
    listen 80;
    index index.php index.html;
    server_name localhost;
    root /var/www/html/public;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $document_root;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

eso deberia bastar para correr el projecto, no es necesario descargar las imagenes en Docker Hub para que funcione
