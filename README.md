# Laravel Project Setup with Docker (Sail)

## Install Dependencies
```bash
composer install
```

## Copy the .env File
Duplicate the .env.example file and rename it to .env:

## Start Laravel Sail
```bash
./vendor/bin/sail up -d
```

## Run Migrations
```bash
./vendor/bin/sail artisan migrate --seed
```
In storage/logs/laravel.log file should be API TOKEN KEY. Use it to authenticate when sending requests to API

## Start queue worker
```bash
./vendor/bin/sail artisan queue:work
```

## Access the Application
http://localhost

# API

## POST /api/jobs

### Authentication
Add to header Bearer Token
```bash
"Authorization": "Bearer {YOUR_AUTH_KEY}"
```

### Body example
```json
{
    "scrape": [
        {
            "url": "https://tandemum.lt/apie-mus",
            "selectors": {
                "wrapper": ".team__member",
                "map": {
                    "name": ".team__member-name",
                    "role": ".team__member-role"
                }

            }
        }
    ]
}
```
