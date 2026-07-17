<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Pages\HomeController;
use App\Http\Controllers\Pages\AboutController;
use App\Http\Controllers\Pages\ResearchController;
use App\Http\Controllers\Pages\ConsultingController;
use App\Http\Controllers\Pages\EventsController;
use App\Http\Controllers\Pages\ContactController;

// Public Routes
Route::get('/', HomeController::class)->name('home');
Route::get('/about', AboutController::class)->name('about');
Route::get('/research', ResearchController::class)->name('research');
Route::get('/consulting', ConsultingController::class)->name('consulting');
Route::get('/events', EventsController::class)->name('events');
Route::get('/contact', ContactController::class)->name('contact');

// Auth Routes (outside admin middleware group)
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('manage')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('manage.dashboard', [
            'messageCount' => \App\Models\Message::whereNull('read_at')->count(),
            'eventCount' => \App\Models\Event::where('status', 'upcoming')->count(),
            'recentMessages' => \App\Models\Message::latest()->take(5)->get(),
        ]);
    })->name('dashboard');

    Route::get('/profile', function () { return view('manage.profile'); })->name('profile');
    Route::get('/publications', function () { return view('manage.publications'); })->name('publications');
    Route::get('/focus-areas', function () { return view('manage.focus-areas'); })->name('focus-areas');
    Route::get('/core-values', function () { return view('manage.core-values'); })->name('core-values');
    Route::get('/achievements', function () { return view('manage.achievements'); })->name('achievements');
    Route::get('/media-archive', function () { return view('manage.media-archive'); })->name('media-archive');
    Route::get('/events', function () { return view('manage.events'); })->name('events');
    Route::get('/events/{event}/registrations/pdf', [\App\Http\Controllers\Admin\EventRegistrationController::class, 'exportPdf'])->name('events.registrations.pdf');
    Route::get('/services', function () { return view('manage.services'); })->name('services');
    Route::get('/testimonials', function () { return view('manage.testimonials'); })->name('testimonials');
    Route::get('/messages', function () { return view('manage.messages'); })->name('messages');
    Route::get('/newsletter', function () { return view('manage.newsletter'); })->name('newsletter');
});

// Subscribe endpoint used by footer subscribe form
Route::post('/subscribe', [\App\Http\Controllers\SubscribeController::class, 'subscribe']);
