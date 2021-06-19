<?php

/** @var Router $router */

$router->get('/')->uses(Ahmadwaleed\Blanket\Http\Controllers\ViewLogController::class);
$router->get('/logs')->uses([Ahmadwaleed\Blanket\Http\Controllers\LogController::class, 'index']);
$router->post('/logs')->uses([Ahmadwaleed\Blanket\Http\Controllers\LogController::class, 'store']);
$router->delete('/logs/truncate')->uses(Ahmadwaleed\Blanket\Http\Controllers\TruncateLogController::class);
$router->delete('/logs/{log}')->uses([Ahmadwaleed\Blanket\Http\Controllers\LogController::class, 'destroy']);
$router->post('/logs/{log}/retry')->uses(Ahmadwaleed\Blanket\Http\Controllers\RetryLogController::class);
$router->get('/hosts/filter')->uses(Ahmadwaleed\Blanket\Http\Controllers\FilterHostController::class);