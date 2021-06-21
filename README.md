![Banner](https://banners.beyondco.de/Laravel%20Blanket.png?theme=light&packageManager=composer+require&packageName=ahmadwaleed%2Flaravel-blanket&pattern=architect&style=style_1&description=A+blanket+which+wraps+your+laravel+HTTP+client+and+provide+logs.&md=1&showWatermark=1&fontSize=100px&images=https%3A%2F%2Flaravel.com%2Fimg%2Flogomark.min.svg)

## Dashboard to view your http client requests in laravel application

![Packagist License](https://img.shields.io/packagist/l/ahmadwaleed/laravel-blanket?style=for-the-badge)
![Packagist Version](https://img.shields.io/packagist/v/ahmadwaleed/laravel-blanket?style=for-the-badge)
![GitHub repo size](https://img.shields.io/github/repo-size/ahmadwaleed/laravel-blanket?style=for-the-badge)
![Packagist Downloads](https://img.shields.io/packagist/dt/ahmadwaleed/laravel-blanket?style=for-the-badge)

Laravel Blanket is a package with wraps laravel http client requests and provide logs for request and response, also give option to retry any request from dashboard and more...

## Desclaimer
This is highly opinionated fun project which provides very simple web interface and log monitoring. If you need advance monitoring tools consider trying [Debugbar](https://github.com/barryvdh/laravel-debugbar), [Sentry](https://sentry.io/) and [Bugsnag](https://www.bugsnag.com/).

## Live Demo
Checkout the demo [here](http://blanketapp.darazhunt.com/blanket) to find out more options and feature...

## Screenshots
![screen shot light](https://github.com/ahmadwaleed/laravel-blanket/blob/main/screenshot-light.png?raw=true)
![screen shot dark](https://github.com/ahmadwaleed/laravel-blanket/blob/main/screenshot-dark.png?raw=true)

## Requirements

- PHP >= 8.0
- Laravel >= 8.45

## Installation

You can install the package via composer:

```bash
composer require ahmadwaleed/laravel-blanket
```

The package will automatically register a service provider.

After installing Blanket, publish its assets using the blanket:wrap Artisan command.

```bash
php artisan blanket:wrap
```

This package comes with a migration to store all outgoing http client requests. You can publish the migration file using:

```bash
php artisan vendor:publish --provider="Ahmadwaleed\Blanket\BlanketServiceProvider" --tag="blanket-migrations"
```

Run the migrations with:

```bash
php artisan migrate
```

Optionally you can publish the blanket configuration file:

```bash
php artisan vendor:publish --provider="Ahmadwaleed\Blanket\BlanketServiceProvider" --tag="blanket-config"
```

This is the contents of the published config file that will be published as `config/blanket.php`
```php
return [
/*
    |--------------------------------------------------------------------------
    | Dashboard Enabled
    |--------------------------------------------------------------------------
    |
    | Here you can specify whether to show dashboard or not.
    |
    */

    'enabled' => env('BLANKET_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Blanket Path
    |--------------------------------------------------------------------------
    |
    | This is the URI path where Blanket will be accessible from. Feel free
    | to change this path to anything you like.
    |
    */

    'path' => env('BLANKET_PATH', 'blanket'),

    /*
    |--------------------------------------------------------------------------
    | Blanket Route Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware will be assigned to every Blanket route, giving you
    | the chance to add your own middleware to this list or change any of
    | the existing middleware. Or, you can simply stick with this list.
    |
    */

    'middlewares' => [
        // 'web',
        \Ahmadwaleed\Blanket\Http\Middlewares\Authorize::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Log Response limit
    |--------------------------------------------------------------------------
    |
    | This is maximum limit blanket is allowed to log response content,
    | if response content exceed this limit the response should be purged.
    | The default limit is 64 KB which is max limit, feel free to set lower limit.
    |
    */

    'log_response_limit' => env('BLANKET_RESPONSE_LIMIT', 64),

    /*
    |--------------------------------------------------------------------------
    | Logs Per Page
    |--------------------------------------------------------------------------
    |
    | How many logs should be fetched per page for dashboard, setting this option
    | to a big number may reduce dashboard performance.
    |
    */

    'logs_per_page' => env('BLANKET_LOGS_PER_PAGE', 100),
];
```

## Credits

- [AhmadWaleed](https://github.com/ahmadwaleed)
- [All Contributors]()

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
