<?php

namespace App\Providers;

use App\Services\AiService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind AiService as a singleton — the Gemini → Groq fallback chain is
        // fully encapsulated inside it; callers just type-hint AiService.
        $this->app->singleton(AiService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS only in production so local dev routes work over plain HTTP.
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
