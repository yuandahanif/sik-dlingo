<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Development

## Installation
```bash
composer install
npm install
```

## Setup sail
```bash
./vendor/bin/sail up
```

## Restore database
Aside form using migration, you can also restore database from sql file.
```bash
mysql -u root --host=127.0.0.1 --port=3306 -v --init-command="SET SESSION FOREIGN_KEY_CHECKS=0" database_name < filename.sql
```

```bash
php artisan serve
```

# Deployment
compress
```bash
tar -czvf dlingo-sik.tar.gz dlingo-sik
```
extract
```bash
tar -xzvf nama.tar.gz
```