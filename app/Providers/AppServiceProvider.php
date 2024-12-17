<?php

namespace App\Providers;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

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
         VerifyCsrfToken::except([
            'api/associate-plan',      // Exclude CSRF protection for this route
            // 'api/available-plans',    // Add more routes if needed
            // 'api/my-plans',
        ]);
    }
}
