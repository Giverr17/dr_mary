<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return view('pages.home', [
            'profile' => \App\Models\Profile::first(),
            'focusAreas' => \App\Models\ResearchFocusArea::orderBy('order')->get(),
            'services' => \App\Models\ConsultingService::orderBy('order')->take(3)->get(),
            'featuredEvent' => \App\Models\Event::where('is_featured', true)->where('status', 'upcoming')->first(),
            'featuredPublications' => \App\Models\Publication::where('is_featured', true)->orderBy('order')->take(3)->get(),
        ]);
    }
}
