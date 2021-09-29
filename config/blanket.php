<?php
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
    | Encrypt data
    |--------------------------------------------------------------------------
    |
    | Here you can specify whether to encrypt the request and response data
    | which is stored in the database.
    |
    */

    'encrypt_data' => env('BLANKET_ENCRYPT_DATA', false),

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

    /*
    |--------------------------------------------------------------------------
    | Prune Blanket Logs Duration
    |--------------------------------------------------------------------------
    |
    | Duration in which blanket logs will be deleted periodically
    | that are no longer needed. You should schedule the model:prune
    | Artisan command in your application's App\Console\Kernel class.
    | You are free to specify the appropriate interval here at which
    | this command should be run:
    | ```
    | $schedule->command('model:prune', [
    |   '--model' => [Ahmadwaleed\Blanket\Models\Log::class],
    | ])->daily();
    |
    */

    'prune_logs_duration' => env('PRUNE_LOGS_DURATION', now()->subMonth()),
];
