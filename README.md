# Laravel Project Setup with Docker (Sail)

## Install Dependencies
```bash
composer install
```

## Copy the .env File
Duplicate the .env.example file and rename it to .env:

## Start Laravel Sail
```bash
./vendor/bin/sail up
```

## Run Migrations
```bash
./vendor/bin/sail artisan migrate
```

## Start queue worker
```bash
./vendor/bin/sail artisan queue:work
```

## Access the Application
http://localhost
