<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResearchController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return view('pages.research', [
            'focusAreas' => \App\Models\ResearchFocusArea::orderBy('order')->get(),
            'publications' => \App\Models\Publication::orderBy('year', 'desc')->orderBy('order')->get()->groupBy('type'),
        ]);
    }
}
