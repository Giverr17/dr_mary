<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return view('pages.contact', [
            'profile' => \App\Models\Profile::first(),
            'faqs' => \App\Models\Faq::orderBy('order')->get(),
        ]);
    }
}
