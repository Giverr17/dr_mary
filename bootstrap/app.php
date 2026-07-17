<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Prism\Prism\Exceptions\PrismRateLimitedException;
use Prism\Prism\Exceptions\PrismProviderOverloadedException;
use Prism\Prism\Exceptions\PrismServerException;
use Prism\Prism\Exceptions\PrismRequestTooLargeException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureIsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // ── AI / Prism exceptions ────────────────────────────────────────────
        // Prevent any Prism failure from bubbling up as a generic HTTP 500.
        // Livewire (AJAX) callers get a JSON error; regular browser requests
        // are redirected back with a flash message the blade can display.

        $aiExceptions = [
            PrismRateLimitedException::class,
            PrismProviderOverloadedException::class,
            PrismServerException::class,
            PrismRequestTooLargeException::class,
        ];

        foreach ($aiExceptions as $exceptionClass) {
            $exceptions->render(function ($e, $request) use ($exceptionClass) {
                if (!($e instanceof $exceptionClass)) {
                    return null; // let other handlers run
                }

                $status  = $e instanceof PrismRateLimitedException ? 429 : 503;
                $message = match (true) {
                    $e instanceof PrismRateLimitedException        => 'The AI is busy (rate limited). Please wait a moment and try again.',
                    $e instanceof PrismProviderOverloadedException => 'The AI service is overloaded. Please try again shortly.',
                    $e instanceof PrismRequestTooLargeException    => 'Your request was too large for the AI. Please shorten the input.',
                    default                                        => 'The AI service had a temporary error. Please try again.',
                };

                // JSON response for Livewire / AJAX requests
                if ($request->expectsJson() || $request->header('X-Livewire')) {
                    return response()->json(['message' => $message], $status);
                }

                // Redirect-back for normal browser requests
                return back()
                    ->withInput()
                    ->with('ai_error', $message);
            });
        }
    })->create();
