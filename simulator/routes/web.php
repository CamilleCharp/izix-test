<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChargeController;

Route::get('/', function () {
    return view('welcome');
});


Route::post('/charge/start', [ChargeController::class, 'startCharging'])
    ->withoutMiddleware([Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);