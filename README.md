# Products Management App

## Description

This project is an attempt to build a rest api app using no framework

## Requirements

- php ^7.4
- composer ^2.0
- php-pdo extension
- mysql ^5.7
- PHP_CodeSniffer from squizlabs installed globally

## Verifying PSR1 & PSR12 coding standards

```bash
phpcs --colors --standard=PSR12 --ignore=./vendor/* ./
```

```bash
phpcs --colors --standard=PSR12 --ignore=./vendor/* ./
```

## Setup

- Install composer dependencies

```bash
composer install
```

## Run the project
```bash
php -S localhost:8000 public/index.php
```
