
## About Project

This project is a Laravel-based Fault Management System that provides APIs and web 
interfaces to manage incidents, categories, and people involved.

## Requirements
PHP >= 8.3
Composer
PostgreSQL
Laravel = 13.x

## Requirements

Clone the repository

```bash
git clone https://github.com/msnsg/fault-mgnt.git
cd fault-mgnt
```

## Install PHP dependencies
```bash
composer install
```

## Environment Setup
```bash
Copy .env file:
cp .env.example .env
```
## Generate application key
```bash
php artisan key:generate
```

## Update database configuration in .env:
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=your_db
DB_USERNAME=your_user
DB_PASSWORD=your_password

## Database Setup
## Run migrations
```bash
php artisan migrate --force
```
## Seed database (category reference data)
```bash
php artisan db:seed
php artisan migrate --force
```
## Run Application
## Start Laravel development server
```bash
php artisan serve
```
## Access the application
http://127.0.0.1:8000

## Testing

## Run tests using
```bash
php artisan test
```
## Test Reporting
Test results are displayed in the console
Any failed test will Show detailed error output

## API Error Handling
400	Malformed JSON / Bad Request
422	Validation errors
404	Resource not found
500	Internal server error

The system uses standardized HTTP response codes.
