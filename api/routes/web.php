<?php

use App\Http\Controllers\LocationController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\StationTypeController;
use App\Http\Controllers\TenantController;
use App\Http\Middleware\EnsureIsAdmin;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to the API',
        'user' => auth()->user()->only(['id', 'name', 'email']),
    ]);
});

Route::prefix('stations')->group(function() {
    // ---------------
    // STATION TYPES |
    // --------------
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
    // ----------------------
    // END OF STATION TYPES |
    // ----------------------

    // ----------
    // STATIONs |
    // ----------
    Route::get('/', [StationController::class, 'index']);
    Route::get('/{station}', [StationController::class, 'show']);
    Route::post('/store', [StationController::class, 'store'])
        ->middleware(['auth:sanctum', EnsureIsAdmin::class]);
    Route::put('/update/{station}', [StationController::class, 'update'])
        ->middleware(['auth:sanctum', EnsureIsAdmin::class]);

    Route::delete('/delete/{station}', [StationController::class, 'destroy'])
        ->middleware(['auth:sanctum', EnsureIsAdmin::class]);
    // -----------------
    // END OF STATIONS |
    // -----------------
});

// ---------
// TENANTS |
// ---------
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
// ----------------
// END OF TENANTS |
// ----------------


// -----------
// LOCATIONS |
// -----------
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
// ------------------
// END OF LOCATIONS |
// ------------------

Route::prefix('vehicles')->group(function() {
    // ---------------
    // VEHICLE TYPES |
    // ---------------
    Route::prefix('types')->group(function() {
        Route::get('/', [\App\Http\Controllers\VehicleTypeController::class, 'index']);
        Route::get('/{vehicleType}', [\App\Http\Controllers\VehicleTypeController::class, 'show']);

        Route::post('/store', [\App\Http\Controllers\VehicleTypeController::class, 'store'])
            ->middleware(['auth:sanctum', EnsureIsAdmin::class]);

        Route::put('/update/{vehicleType}', [\App\Http\Controllers\VehicleTypeController::class, 'update'])
            ->middleware(['auth:sanctum', EnsureIsAdmin::class]);

        Route::delete('/delete/{vehicleType}', [\App\Http\Controllers\VehicleTypeController::class, 'destroy'])
            ->middleware(['auth:sanctum', EnsureIsAdmin::class]);
    });
    // ----------------------
    // END OF VEHICLE TYPES |
    // ----------------------

    // ----------
    // VEHICLES |
    // ----------
    Route::get('/', [\App\Http\Controllers\VehicleController::class, 'index']);
    Route::get('/{vehicle}', [\App\Http\Controllers\VehicleController::class, 'show']);
    Route::post('/store', [\App\Http\Controllers\VehicleController::class, 'store'])
        ->middleware(['auth:sanctum', EnsureIsAdmin::class]);
    Route::put('/update/{vehicle}', [\App\Http\Controllers\VehicleController::class, 'update'])
        ->middleware(['auth:sanctum', EnsureIsAdmin::class]);
    Route::delete('/delete/{vehicle}', [\App\Http\Controllers\VehicleController::class, 'destroy'])
        ->middleware(['auth:sanctum', EnsureIsAdmin::class]);
    // -----------------
    // END OF VEHICLES |
    // -----------------
});

require __DIR__.'/auth.php';
