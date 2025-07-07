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

# Correr lo siguiente en la terminal

docker build -t laravel-image .

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

# en caso de que el dockerfile no se haya corrido
# en la carpeta raiz del proyecto, abrir la terminal y ejecutar
docker exec -it laravel_app bash
apt-get update
apt-get install -y libmariadb-dev libmariadb-dev-compat
docker-php-ext-install pdo pdo_mysql
# verificar que pdo haya sido instalado con lo siguiente
php -m | grep pdo_mysql
# reiniciar el contenedor
# sin el pdo no sirve la base de datos no se contacta con la API y esto se cae 

```

se adjunto el ambiente de pruebas se neceita instalar k6 y correrlo por terminal, no integrada en IDE.

El funcioanmiento de la API es el siguiente.

```html
<!-- herramientas url las hermmientas se crean de manera automatica al crear un producto -->
dominio/api/herramientas <!-- ->metodo get entrega todas las herraminetas-->
dominio/api/herramientas/codigo_producto <!-- -> metodo post entrega una herramienta en especifico--> 
dominio/api/herramientas/codigo_producto <!-- -> metodo delete elimina una herramienta en especifico-->
dominio/api/herramientas/codigo_producto <!-- -> metodo put modifica una herramienta en especifico-->  

<!-- productos sive tanto para materiales-seguridades-manuales  se reemplaza producto por cualquira de los tipos mencionados antes-->
dominio/api/producto <!-- -> metodo get entrega todos los elementos de la tabla-->
dominio/api/producto <!-- -> metodo POST se le entrega json con elementos de la tabla para crear el producto deben estar todos los elementos -->
dominio/api/producto/codigo_producto <!-- -> metodo post entrega un  elemento de la tabla-->
dominio/api/producto/codigo_producto <!-- -> metodo delete elimina un  elemento de la tabla-->
dominio/api/producto/codigo_producto <!-- -> metodo put modifica un  elemento de la tabla-->

<!-- 
    los metodos put de estos deben ir con json que puede contener algun elemento al modificar 
-->

<!--ruta transbank -->
dominio/api/trasnbank/create <!-- -> metodo POST se le entrega json 
{
    monto >= 50
}
-->
dominio/api/transbank/callback <!-- -> metodo POST recoge el json de regreso desde webpay
-->

<!-- stock -->
dominio/api/stock/descontar <!-- -> metodo POST requiere un json
{
    tipo -> debe ser material, manual o seguridad
    codigo_producto -> codigo del producto
    cantidad -> cantidad a descontar del stock
    sucursal -> nombre de la sucursal
}
entrega un json con el resumen del traspaso 
-->
 dominio/api/sucursales/stock/{nombre} <!-- -> metodo POST requiere nombre de la sucursal entrega el stock de la sucursal consultada

-->


 <!-- inicio de sesion-->

<!--basicamente son lo mismo solo uno tiene un json mas completo que el otro
solo veremos uno para el otro caso solo se necesito agregar /api/employees/login o register
-->

dominio/api/login <!-- -> Metodo post requiere un json
{
    email -> correo
    password -> contraseña
}
-->

dominio/api/register <!-- -> Metodo post requiere un json
{
    name -> nombre
    email -> correo
    password -> contraseña
    password_confirmation -> confirmacion de contraseña
}
-->
dominio/api/user <!-- -> devuelve con get los datos del usuario -->

<!-- divisas -->
dominio/api/divisas/convertir <!-- -> metodo POST ncesita un json
{
    divisa -> nombre de la divisa abreviacion de conversion universal
    monto -> cantidad a trasnformar
} 

funciona con api externa.
-->
```

eso deberia bastar para correr el proyecto, no es necesario descargar las imagenes en Docker Hub para que funcione
