<?php

use App\Http\Controllers\LocationController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\StationTypeController;
use App\Http\Controllers\TenantController;
use App\Http\Middleware\EnsureHasFakeAPIKey;
use App\Http\Middleware\EnsureHasSimulatorApiKey;
use App\Http\Middleware\EnsureIsAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(EnsureHasFakeAPIKey::class)->group(function () {
    Route::get('/', function(Request $request) {
        return response()->json([
            'user' => auth()->user()
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
                ->middleware([ EnsureIsAdmin::class]);
    
            Route::put('/update/{stationType}', [StationTypeController::class, 'update'])
                ->whereNumber('stationType')
                ->middleware([ EnsureIsAdmin::class]);
    
            Route::delete('/delete/{stationType}', [StationTypeController::class, 'destroy'])
                ->whereNumber('stationType')
                ->middleware([ EnsureIsAdmin::class]);
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
            ->middleware([ EnsureIsAdmin::class]);
            
        Route::put('/update/{station}', [StationController::class, 'update'])
            ->whereUuid('station')
            ->middleware([ EnsureIsAdmin::class]);
    
        Route::delete('/delete/{station}', [StationController::class, 'destroy'])
            ->whereUuid('station')
            ->middleware([ EnsureIsAdmin::class]);
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
            ->middleware([ EnsureIsAdmin::class]);
    
        Route::put('/update/{tenant}', [TenantController::class, 'update'])
            ->whereUuid('tenant')
            ->middleware([ EnsureIsAdmin::class]);
    
        Route::delete('/delete/{tenant}', [TenantController::class, 'destroy'])
            ->whereUuid('tenant')
            ->middleware([ EnsureIsAdmin::class]);
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
            ->middleware([ EnsureIsAdmin::class]);
    
        Route::put('/update/{location}', [LocationController::class, 'update'])
            ->whereUuid('location')
            ->middleware([ EnsureIsAdmin::class]);
    
        Route::delete('/delete/{location}', [LocationController::class, 'destroy'])
            ->whereUuid('location')
            ->middleware([ EnsureIsAdmin::class]);
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
                ->middleware([ EnsureIsAdmin::class]);
    
            Route::put('/update/{vehicleType}', [\App\Http\Controllers\VehicleTypeController::class, 'update'])
                ->whereNumber('vehicleType')
                ->middleware([ EnsureIsAdmin::class]);
    
            Route::delete('/delete/{vehicleType}', [\App\Http\Controllers\VehicleTypeController::class, 'destroy'])
                ->whereNumber('vehicleType')
                ->middleware([ EnsureIsAdmin::class]);
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
    
        Route::post('/store', [\App\Http\Controllers\VehicleController::class, 'store']);
            
        Route::put('/update/{vehicle}', [\App\Http\Controllers\VehicleController::class, 'update'])
            ->whereUuid('vehicle')
            ->middleware([ EnsureIsAdmin::class]);
    
        Route::delete('/delete/{vehicle}', [\App\Http\Controllers\VehicleController::class, 'destroy'])
            ->whereUuid('vehicle');
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
            ->middleware([ EnsureIsAdmin::class]);
        
        Route::delete('/delete/{connector}', [\App\Http\Controllers\ConnectorController::class, 'destroy'])
            ->whereUuid('connector')
            ->middleware([ EnsureIsAdmin::class]);
        // -------------------
        // END OF CONNECTORS |
        // -------------------
    });
});

Route::middleware(EnsureHasSimulatorApiKey::class)->group(function() {
    Route::prefix('charging-sessions')->group(function() {
        Route::get('/{session}', [\App\Http\Controllers\ChargingSessionController::class, 'monitor'])
            ->whereUuid('session');
    
        Route::post('/start', [\App\Http\Controllers\ChargingSessionController::class, 'start']);
    
        Route::put('/update/{session}', [\App\Http\Controllers\ChargingSessionController::class, 'update'])
            ->whereUuid('session');
    
        Route::post('/end/{session}', [\App\Http\Controllers\ChargingSessionController::class, 'end'])
            ->whereUuid('session');
    });
});