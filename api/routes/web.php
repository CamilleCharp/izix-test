<?php

use App\Http\Controllers\StationTypeController;
use App\Http\Middleware\EnsureIsAdmin;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to the API',
        'documentation' => url('/docs'),
        'user' => auth()->user(),
    ]);
});

Route::prefix('stations')->group(function() {
    Route::prefix('types')->group(function() {
        Route::post('/register', [StationTypeController::class, 'register'])
            ->middleware(['auth:sanctum', EnsureIsAdmin::class]);
    });
});

require __DIR__.'/auth.php';
