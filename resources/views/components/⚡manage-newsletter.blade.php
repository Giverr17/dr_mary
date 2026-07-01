<?php

use Livewire\Component;
use App\Models\Subscription;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewsletterEmail;

new class extends Component
{
    public $subject = '';
    public $content = '';
    public $statusMessage = null;
    public $isSuccess = false;

    public function send()
    {
        $this->validate([
            'subject' => 'required|min:5',
            'content' => 'required|min:20',
        ]);

        $subscribers = Subscription::all();

        if ($subscribers->isEmpty()) {
            $this->statusMessage = 'No subscribers found.';
            $this->isSuccess = false;
            return;
        }

        try {
            foreach ($subscribers as $subscriber) {
                Mail::to($subscriber->email)->queue(new NewsletterEmail($this->subject, $this->content));
            } 

            $this->statusMessage = 'Newsletter sent to ' . $subscribers->count() . ' subscribers successfully.';
            $this->isSuccess = true;
            $this->reset(['subject', 'content']);
        } catch (\Exception $e) {
            $this->statusMessage = 'Failed to send newsletter: ' . $e->getMessage();
            $this->isSuccess = false;
        }
    }

    public function getSubscriberCountProperty()
    {
        return Subscription::count();
    }
};
?>

<div>
    <div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <h2 class="text-2xl font-display font-bold text-navy">Send Newsletter</h2>
        <div class="px-4 py-2 bg-primary/10 rounded-lg border border-primary/20">
            <span class="text-xs font-bold text-navy uppercase tracking-widest block">Total Subscribers</span>
            <span class="text-xl font-display font-bold text-primary">{{ $this->subscriberCount }}</span>
        </div>
    </div>

    @if($statusMessage)
        <div class="mb-8 p-6 rounded-2xl {{ $isSuccess ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-red-50 text-red-700 border border-red-100' }} animate-in fade-in slide-in-from-top-4 duration-500">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined">{{ $isSuccess ? 'check_circle' : 'error' }}</span>
                <span class="font-bold">{{ $statusMessage }}</span>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden">
        <div class="p-8">
            <form wire:submit.prevent="send" class="space-y-6">
                <div>
                    <label class="block text-xs font-bold text-navy uppercase tracking-widest mb-2 ml-1">Email Subject</label>
                    <input wire:model="subject" type="text" class="w-full px-5 py-4 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all text-sm font-medium" placeholder="Weekly Insights: Navigating Modern Entrepreneurship" />
                    @error('subject') <span class="text-xs text-red-500 mt-1 block ml-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-navy uppercase tracking-widest mb-2 ml-1">Newsletter Content</label>
                    <textarea wire:model="content" rows="12" class="w-full px-5 py-4 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all text-sm leading-relaxed resize-none" placeholder="Write your professional insights here..."></textarea>
                    @error('content') <span class="text-xs text-red-500 mt-1 block ml-1">{{ $message }}</span> @enderror
                </div>

                <div class="pt-4 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="text-sm text-slate-500 italic">
                        <span class="material-symbols-outlined text-sm align-middle mr-1">info</span>
                        This will send an email to all {{ $this->subscriberCount }} active subscribers.
                    </div>
                    
                    <button type="submit" wire:loading.attr="disabled" class="w-full md:w-auto px-12 py-4 bg-navy text-primary font-bold rounded-xl hover:bg-navy/90 transition-all shadow-lg flex items-center justify-center gap-3 group disabled:opacity-50">
                        <span wire:loading.remove wire:target="send">Broadcast Newsletter</span>
                        <span wire:loading wire:target="send" class="animate-spin material-symbols-outlined">progress_activity</span>
                        <span wire:loading wire:target="send">Processing...</span>
                        <span wire:loading.remove wire:target="send" class="material-symbols-outlined group-hover:translate-x-1 transition-transform">campaign</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
