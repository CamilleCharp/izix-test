<?php

use App\Http\Controllers\ChargingSessionInertiaController;
use App\Http\Controllers\ConnectorInertiaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StationInertiaController;
use App\Http\Controllers\VehicleInertiaController;
use App\Http\Middleware\EnsureIsAdmin;
use App\Models\ChargingSession;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('vehicles')->group(function() {
        
        Route::get('/create', [VehicleInertiaController::class,'create'])->name('vehicles_create');
        Route::post('/create', [VehicleInertiaController::class,'store'])->name('vehicles_store');
        
        Route::get('/edit/{vehicle}', [VehicleInertiaController::class, 'edit'])->name('vehicles_edit');
        Route::put('/edit/{vehicle}', [VehicleInertiaController::class,'update'])->name('vehicles_update');
        
        Route::delete('/delete/{vehicle}', [VehicleInertiaController::class,'destroy'])->name('vehicles_delete');

        Route::get('/', [VehicleInertiaController::class, 'index'])->name('vehicles_index');
        Route::get('/{vehicle}', [VehicleInertiaController::class,'show'])->name('vehicles_show');
    });

    Route::prefix('connectors')->middleware(EnsureIsAdmin::class)->group(function () {
        
        Route::get('/create', [ConnectorInertiaController::class,'create'])->name('connectors_create');
        Route::post('/create', [ConnectorInertiaController::class,'store'])->name('connectors_store');
        
        Route::get('/edit/{connector}', [ConnectorInertiaController::class, 'edit'])->name('connectors_edit');
        Route::put('/edit/{connector}', [ConnectorInertiaController::class,'update'])->name('connectors_update');
        
        Route::delete('/delete/{connector}', [ConnectorInertiaController::class,'destroy'])->name('connectors_delete');
        
        Route::get('/', [ConnectorInertiaController::class, 'index'])->name('connectors_index');
        Route::get('/{connector}', [ConnectorInertiaController::class, 'show'])->name('connectors_show');
    });

    Route::prefix('charging-session')->group(function () {
        Route::get('/', [ConnectorInertiaController::class, 'index'])->name('charging_sessions_index');
        Route::get('/{session}', [ConnectorInertiaController::class, 'show'])->name('charging_sessions_show');
    });

    Route::prefix('stations')->middleware(EnsureIsAdmin::class)->group(function () {
        Route::get('/create', [StationInertiaController::class, 'create'])->name('stations_create');
        Route::post('/create', [StationInertiaController::class, 'store'])->name('stations_store');
        
        Route::get('/edit/{station}', [StationInertiaController::class, 'edit'])->name('stations_edit');
        Route::put('/edit/{station}', [StationInertiaController::class, 'update'])->name('stations_update');
        
        Route::delete('/destroy/{station}', [StationInertiaController::class, 'destroy'])->name('stations_delete');

        Route::get('/', [StationInertiaController::class, 'index'])->name('stations_index');
        Route::get('/{station}', [StationInertiaController::class, 'show'])->name('stations_show');
    });

    Route::prefix('charging-sessions')->group(function() {
        Route::get('/create', [ChargingSessionInertiaController::class,'prepareSimulation'])->name('charging-sessions_prepare');

        Route::get('/', [ChargingSessionInertiaController::class,'index'])->name('charging-sessions_index');
    });
});


require __DIR__.'/auth.php';
