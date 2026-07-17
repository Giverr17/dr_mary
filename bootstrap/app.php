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
        // Laravel 11 uses reflection on the closure's first parameter type to
        // determine which exception each renderer handles — the type-hint is
        // therefore required (a generic $e without a hint throws RuntimeException).
        //
        // Livewire / AJAX callers get a JSON error body; regular browser
        // requests are redirected back with a flash message.

        $exceptions->render(function (PrismRateLimitedException $e, $request) {
            $message = 'The AI is busy (rate limited). Please wait a moment and try again.';
            if ($request->expectsJson() || $request->header('X-Livewire')) {
                return response()->json(['message' => $message], 429);
            }
            return back()->withInput()->with('ai_error', $message);
        });

        $exceptions->render(function (PrismProviderOverloadedException $e, $request) {
            $message = 'The AI service is overloaded. Please try again shortly.';
            if ($request->expectsJson() || $request->header('X-Livewire')) {
                return response()->json(['message' => $message], 503);
            }
            return back()->withInput()->with('ai_error', $message);
        });

        $exceptions->render(function (PrismRequestTooLargeException $e, $request) {
            $message = 'Your request was too large for the AI. Please shorten the input.';
            if ($request->expectsJson() || $request->header('X-Livewire')) {
                return response()->json(['message' => $message], 503);
            }
            return back()->withInput()->with('ai_error', $message);
        });

        $exceptions->render(function (PrismServerException $e, $request) {
            $message = 'The AI service had a temporary error. Please try again.';
            if ($request->expectsJson() || $request->header('X-Livewire')) {
                return response()->json(['message' => $message], 503);
            }
            return back()->withInput()->with('ai_error', $message);
        });
    })->create();
