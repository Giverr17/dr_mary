<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Barryvdh\DomPDF\Facade\Pdf;

class EventRegistrationController extends Controller
{
    public function exportPdf(Event $event)
    {
        $registrations = $event->registrations()->latest()->get();

        $pdf = Pdf::loadView('admin.pdf.event-registrations', [
            'event' => $event,
            'registrations' => $registrations
        ]);

        return $pdf->download("registrations-{$event->id}.pdf");
    }
}
