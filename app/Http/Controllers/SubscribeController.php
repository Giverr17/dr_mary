<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubscribeNotification;
use App\Models\Profile;

class SubscribeController extends Controller
{
    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
        ]);

        $profile = Profile::first();
        $recipient = $profile?->email ?? config('mail.from.address');

        try {
            // Save subscription to database
            \App\Models\Subscription::updateOrCreate(
                ['email' => $data['email']],
                ['subscribed_at' => now()]
            );

            // Optional: Still notify Dr. Mary
            Mail::to($recipient)->send(new SubscribeNotification($data['email']));

            return response()->json(['message' => 'Thanks! You have been subscribed.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Subscription failed.'], 500);
        }
    }
}
