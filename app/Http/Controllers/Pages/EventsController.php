<?php

namespace App\Http\Controllers\Pages;

use App\Enums\EventStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return view('pages.events', [
            'featuredEvents' => \App\Models\Event::where('is_featured', true)->where('status', EventStatus::Upcoming)->orderBy('date_start')->get(),
            'upcomingEvents' => \App\Models\Event::where('status', EventStatus::Upcoming)->where('is_featured', false)->orderBy('date_start')->get(),
            'pastEvents' => \App\Models\Event::where('status', EventStatus::Past)->orderBy('date_start', 'desc')->get(),
            'mediaArchives' => \App\Models\MediaArchive::orderBy('is_featured', 'desc')->orderBy('order')->orderBy('recorded_at', 'desc')->get(),
        ]);
    }
}
