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

# API

## POST /api/jobs

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
