<?php

namespace App\Providers;

use App\Enums\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        Inertia::share('simulator_url', env('SIMULATOR_URL'));
        
        Inertia::share([
            'auth' => [
                'user' => fn (Request $request) => $request->user() ? $request->user()->only(['id', 'name', 'role']) : null,
                'admin' => fn (Request $request) => $request->user() ? $request->user()->hasRole(Roles::ADMIN) : false,
            ]
        ]);
    }
}
