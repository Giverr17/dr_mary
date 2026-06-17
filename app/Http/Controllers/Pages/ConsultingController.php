<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConsultingController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return view('pages.consulting', [
            'services' => \App\Models\ConsultingService::orderBy('order')->get(),
            'steps' => \App\Models\ProcessStep::orderBy('step_number')->get(),
            'testimonials' => \App\Models\Testimonial::orderBy('order')->get(),
        ]);
    }
}
