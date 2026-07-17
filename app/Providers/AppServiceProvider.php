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
        // Force HTTPS for all generated URLs when:
        //   a) APP_ENV is 'production', OR
        //   b) the request is already arriving over HTTPS (or forwarded as HTTPS
        //      by the Nginx reverse proxy via the X-Forwarded-Proto header, which
        //      is trusted globally in bootstrap/app.php via trustProxies(at: '*')).
        //
        // This keeps Livewire AJAX endpoints, session cookies, asset URLs and
        // redirect targets consistent with the browser-facing HTTPS URL so that
        // the admin /manage pages work correctly in production.
        if (app()->environment('production') || request()->isSecure()) {
            URL::forceScheme('https');
        }
    }
}
