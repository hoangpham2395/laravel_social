#Laravel Social

- Clone project
```bash
git clone 
```
- Install
```bash
composer install
cp .env.example .env
php artisan key:generate
```
- Permission
```bash
chmod -R 777 public/images
chmod -R 777 public/media
chmod -R 777 public/tmp
```

* Open file .env and config database
```bash
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret
```

* Delete cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

* Install Form collective 5.4.0
```bash
composer require "laravelcollective/html":"^5.4.0"
```

* Install Doctrine dbal for migration
```bash
composer require doctrine/dbal
```

* Install Guzzle Http
```bash
composer require guzzlehttp/guzzle
```

* Run migration
```bash
php artisan migrate
```

* Run seeder
```bash
php artisan db:seed
```
