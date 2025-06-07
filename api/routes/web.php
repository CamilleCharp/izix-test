<?php

use App\Http\Controllers\LocationController;
use App\Http\Controllers\StationTypeController;
use App\Http\Controllers\TenantController;
use App\Http\Middleware\EnsureIsAdmin;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to the API',
    ]);
});

Route::prefix('stations')->group(function() {
    Route::prefix('types')->group(function() {
        Route::get('/', [StationTypeController::class, 'index']);
        Route::get('/{stationType}', [StationTypeController::class, 'show']);

        Route::post('/register', [StationTypeController::class, 'store'])
            ->middleware(['auth:sanctum', EnsureIsAdmin::class]);

        Route::put('/update/{stationType}', [StationTypeController::class, 'update'])
            ->middleware(['auth:sanctum', EnsureIsAdmin::class]);

        Route::delete('/delete/{stationType}', [StationTypeController::class, 'destroy'])
            ->middleware(['auth:sanctum', EnsureIsAdmin::class]);
    });
});

Route::prefix('tenants')->group(function() {
    Route::get('/', [TenantController::class,'index']);
    Route::get('/{tenant}', [TenantController::class, 'show']);

    Route::post('/store/', [TenantController::class, 'store'])
        ->middleware(['auth:sanctum', EnsureIsAdmin::class]);

    Route::put('/update/{tenant}', [TenantController::class, 'update'])
        ->middleware(['auth:sanctum', EnsureIsAdmin::class]);

    Route::delete('/delete/{tenant}', [TenantController::class, 'destroy'])
        ->middleware(['auth:sanctum', EnsureIsAdmin::class]);
});

Route::prefix('locations')->group(function() {
    Route::get('/', [LocationController::class, 'index']);
    Route::get('/{location}', [LocationController::class, 'show']);

    Route::post('/store', [LocationController::class, 'store'])
        ->middleware(['auth:sanctum', EnsureIsAdmin::class]);

    Route::put('/update/{location}', [LocationController::class, 'update'])
        ->middleware(['auth:sanctum', EnsureIsAdmin::class]);

    Route::delete('/delete/{location}', [LocationController::class, 'destroy'])
        ->middleware(['auth:sanctum', EnsureIsAdmin::class]);
});

require __DIR__.'/auth.php';
