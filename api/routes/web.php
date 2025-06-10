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
        
        Route::get('/{stationType}', [StationTypeController::class, 'show'])
            ->whereNumber('stationType');

        Route::post('/register', [StationTypeController::class, 'store'])
            ->middleware(['auth:sanctum', EnsureIsAdmin::class]);

        Route::put('/update/{stationType}', [StationTypeController::class, 'update'])
            ->whereNumber('stationType')
            ->middleware(['auth:sanctum', EnsureIsAdmin::class]);

        Route::delete('/delete/{stationType}', [StationTypeController::class, 'destroy'])
            ->whereNumber('stationType')
            ->middleware(['auth:sanctum', EnsureIsAdmin::class]);
    });
    // ----------------------
    // END OF STATION TYPES |
    // ----------------------

    // ----------
    // STATIONs |
    // ----------
    Route::get('/', [StationController::class, 'index']);

    Route::get('/{station}', [StationController::class, 'show'])
        ->whereUuid('station');

    Route::post('/store', [StationController::class, 'store'])
        ->middleware(['auth:sanctum', EnsureIsAdmin::class]);
        
    Route::put('/update/{station}', [StationController::class, 'update'])
        ->whereUuid('station')
        ->middleware(['auth:sanctum', EnsureIsAdmin::class]);

    Route::delete('/delete/{station}', [StationController::class, 'destroy'])
        ->whereUuid('station')
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

    Route::get('/{tenant}', [TenantController::class, 'show'])
        ->whereUuid('tenant');

    Route::post('/store/', [TenantController::class, 'store'])
        ->middleware(['auth:sanctum', EnsureIsAdmin::class]);

    Route::put('/update/{tenant}', [TenantController::class, 'update'])
        ->whereUuid('tenant')
        ->middleware(['auth:sanctum', EnsureIsAdmin::class]);

    Route::delete('/delete/{tenant}', [TenantController::class, 'destroy'])
        ->whereUuid('tenant')
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

    Route::get('/{location}', [LocationController::class, 'show'])
        ->whereUuid('location');

    Route::post('/store', [LocationController::class, 'store'])
        ->middleware(['auth:sanctum', EnsureIsAdmin::class]);

    Route::put('/update/{location}', [LocationController::class, 'update'])
        ->whereUuid('location')
        ->middleware(['auth:sanctum', EnsureIsAdmin::class]);

    Route::delete('/delete/{location}', [LocationController::class, 'destroy'])
        ->whereUuid('location')
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

        Route::get('/{vehicleType}', [\App\Http\Controllers\VehicleTypeController::class, 'show'])
            ->whereNumber('vehicleType');

        Route::post('/store', [\App\Http\Controllers\VehicleTypeController::class, 'store'])
            ->middleware(['auth:sanctum', EnsureIsAdmin::class]);

        Route::put('/update/{vehicleType}', [\App\Http\Controllers\VehicleTypeController::class, 'update'])
            ->whereNumber('vehicleType')
            ->middleware(['auth:sanctum', EnsureIsAdmin::class]);

        Route::delete('/delete/{vehicleType}', [\App\Http\Controllers\VehicleTypeController::class, 'destroy'])
            ->whereNumber('vehicleType')
            ->middleware(['auth:sanctum', EnsureIsAdmin::class]);
    });
    // ----------------------
    // END OF VEHICLE TYPES |
    // ----------------------

    // ----------
    // VEHICLES |
    // ----------
    Route::get('/', [\App\Http\Controllers\VehicleController::class, 'index']);

    Route::get('/{vehicle}', [\App\Http\Controllers\VehicleController::class, 'show'])
        ->whereUuid('vehicle');

    Route::post('/store', [\App\Http\Controllers\VehicleController::class, 'store'])
        ->middleware(['auth:sanctum', EnsureIsAdmin::class]);
        
    Route::put('/update/{vehicle}', [\App\Http\Controllers\VehicleController::class, 'update'])
        ->whereUuid('vehicle')
        ->middleware(['auth:sanctum', EnsureIsAdmin::class]);

    Route::delete('/delete/{vehicle}', [\App\Http\Controllers\VehicleController::class, 'destroy'])
        ->whereUuid('vehicle')
        ->middleware(['auth:sanctum', EnsureIsAdmin::class]);
    // -----------------
    // END OF VEHICLES |
    // -----------------
});


Route::prefix('connectors')->group(function() {
    // -----------------
    // CONNECTOR TYPES |
    // -----------------
    Route::prefix('types')->group(function() {
        Route::get('/', [\App\Http\Controllers\ConnectorTypeController::class, 'index']);

        Route::get('/{connectorType}', [\App\Http\Controllers\ConnectorTypeController::class, 'show'])
        ->whereNumber('connectorType');
    });
    // -----------------------
    // END OF CONNECTOR TYPES |
    // -----------------------

    // ------------
    // CONNECTORS |
    // ------------
    Route::get('/', [\App\Http\Controllers\ConnectorController::class, 'index']);
    
    Route::get('/{connector}', [\App\Http\Controllers\ConnectorController::class, 'show'])
        ->whereUuid('connector');

    Route::post('/store', [\App\Http\Controllers\ConnectorController::class, 'store'])
        ->middleware(['auth:sanctum', EnsureIsAdmin::class]);
    
    Route::delete('/delete/{connector}', [\App\Http\Controllers\ConnectorController::class, 'destroy'])
        ->whereUuid('connector')
        ->middleware(['auth:sanctum', EnsureIsAdmin::class]);
    // -------------------
    // END OF CONNECTORS |
    // -------------------
});

Route::prefix('charging-sessions')->group(function() {
    Route::get('/{session}', [\App\Http\Controllers\ChargingSessionController::class, 'monitor'])
        ->whereUuid('session')
        ->middleware(['auth:sanctum']);

    Route::post('/start', [\App\Http\Controllers\ChargingSessionController::class, 'start'])
        ->withoutMiddleware([Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

    Route::put('/update/{session}', [\App\Http\Controllers\ChargingSessionController::class, 'update'])
        ->whereUuid('session')
        ->withoutMiddleware([Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

    Route::post('/end/{session}', [\App\Http\Controllers\ChargingSessionController::class, 'end'])
        ->whereUuid('session')
        ->withoutMiddleware([Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
});


require __DIR__.'/auth.php';
