<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return view('pages.about', [
            'profile' => \App\Models\Profile::first(),
            'credentials' => \App\Models\Credential::orderBy('order')->get(),
            'coreValues' => \App\Models\CoreValue::orderBy('order')->get(),
            'achievements' => \App\Models\Achievement::ordered()->get(),
        ]);
    }
}
