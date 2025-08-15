## Requirements

-   php ^8.3
-   composer
-   enable php extension (zip,intl,gd)

## Install

1. clone this repo

2. run this following command
   `composer install`

3. setup project
   `cp .env.example .env`
   `php artisan key:generate`
   `php artisan storage:link`

4. config .env file with your server (im using laragon vhost)
   `APP_URL=http://sistem-arsip.test`
   leave it default if using php artisan serve

5. run migration database
   `php artisan migrate --seed`
