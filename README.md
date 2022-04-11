# Boilerplate

## _The Laravel 9 boilerplate_

## Features

- Test Driven Development (TDD).
- Auth system.
- Users CRUD.
- Roles CRUD.
- Permissions CRUD.


## Technologies

- [Laravel 9] - Laravel is a web application framework with expressive, elegant syntax.
- [MySQL] - MySQL is the world's most popular open source database.
- [PostgreSQL] - The World's Most Advanced Open Source Relational Database.
- [PHP] - PHP is a popular general-purpose scripting language that is especially suited to web development.
- [PHPUnit] - PHPUnit is a programmer-oriented testing framework for PHP.

- [Bootstrap] - The world’s most popular framework for building responsive, mobile-first sites.


### Requirements

- Composer >= 2.1.12
- Git >= 2.11
- MySQL >= 8.0 o PostgreSQL >= 12.8
- SQLite >= 3.31.1
- PHP >= 8.0
- BCMath PHP Extension
- Ctype PHP Extension
- Curl PHP Extension
- Fileinfo PHP extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- SQLite PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension


## Installation

```sh
git clone https://github.com/stalinscj/boilerplate.git
```

```sh
cd boilerplate
```

```sh
composer install
```

(If .env file was not copied automatically after installation):

```sh
cp .env.example .env
```

(If APP_KEY was not generated automatically after installation):

```sh
php artisan key:generate
```

From MySQL or PostgreSQL CLI:

```sh
CREATE DATABASE database_name;
```

In .env file set the following variables:

```sh
APP_NAME=

DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

SUPER_ADMIN_EMAIL=
SUPER_ADMIN_PASSWORD=
```

```sh
php artisan migrate --seed
```


## Running tests

```sh
php artisan test
```

## Running project

```sh
php artisan serve
```

From a browser go to http://127.0.0.1:8000


[//]: # (Links)

[Laravel 9]: <https://laravel.com>
[MySQL]: <https://www.mysql.com>
[PostgreSQL]: <https://www.postgresql.org>
[PHP]: <https://www.php.net>
[PHPUnit]: <https://phpunit.de>
[Bootstrap]: <https://getbootstrap.com>
