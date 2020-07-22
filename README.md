fashin-tv
======================

Find your favorite tv show

# Table of Contents

1. [Requirements](#-requirements)
2. [Installation](#-installation)
3. [Built with](#-built-with)
4. [REST API](#-api-platform)
5. [To-do](#-to-do)
6. [WIP](#-WIP)


## Requirements

- PHP 7.x
- Composer
- Laravel


> ⚠️ Caution: If use Laravel valet then do the required modifications

## Install the dependencies
### composer
[Install Composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos)

### laravel
[Install Laravel](https://laravel.com/docs/7.x/installation)

## clone project
### Start the app in development mode
```bash
git clone git@github.com:Atemndobs/fashion-tv.git
```
### cd into project
```bash
cd fashion-tv
```

### install the composer packages
```bash
composer install
```


### start project
```bash
php artisan serve
```
or

```bash
make start
```


### run tests

```bash
make test
```


### visit api url
```bash
make api
```

## REST API

[local api](localhost:8000/api)

### search tv show by giving title as query parameter
e.g search for outlander
localhost:8000/api?q=outlander

[outlander](http://localhost:8000/api?q=outlander)

## To-do

- Dockerise application to ease installation on different environment
- Configure application for deployment / creating CI/CD pipeline for automated deployment 
- Generate api documentation e.g using [apidoc-generator](https://github.com/mpociot/laravel-apidoc-generator)

## WIP
- Update tests
- Update docs
- updating CI/CD
