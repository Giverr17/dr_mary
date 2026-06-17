<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\Message;

new class extends Component
{
    #[Validate('required|min:3')]
    public $name = '';

    #[Validate('required|email')]
    public $email = '';

    #[Validate('required')]
    public $subject = '';

    public $organization = '';

    #[Validate('required|min:10')]
    public $body = '';

    public $success = false;

    public function save()
    {
        $this->validate();

        Message::create([
            'name' => $this->name,
            'email' => $this->email,
            'subject' => $this->subject,
            'organization' => $this->organization,
            'body' => $this->body,
        ]);

        $this->reset(['name', 'email', 'subject', 'organization', 'body']);
        $this->success = true;

        $this->dispatch('message-sent');
    }
};
?>

<div>
    @if($success)
        <div class="bg-primary/10 border border-primary/20 p-6 rounded-2xl text-navy mb-8 animate-in fade-in slide-in-from-top-4 duration-500">
            <div class="flex items-center gap-3 mb-2">
                <span class="material-symbols-outlined text-primary">check_circle</span>
                <span class="font-bold text-lg">Message Sent Successfully!</span>
            </div>
            <p class="text-sm opacity-80">Thank you for reaching out, Dr. Uhunoma. We will get back to you shortly.</p>
            <button wire:click="$set('success', false)" class="mt-4 text-xs font-bold uppercase tracking-widest hover:underline">Send another message</button>
        </div>
    @else
        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-navy uppercase tracking-widest">Full Name</label>
                    <input wire:model="name" type="text" class="w-full px-5 py-3 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all" placeholder="Jane Doe" />
                    @error('name') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-navy uppercase tracking-widest">Email Address</label>
                    <input wire:model="email" type="email" class="w-full px-5 py-3 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all" placeholder="jane@example.com" />
                    @error('email') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-navy uppercase tracking-widest">Subject</label>
                    <select wire:model="subject" class="w-full px-5 py-3 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all">
                        <option value="">Select a subject</option>
                        <option value="Consulting Inquiry">Consulting Inquiry</option>
                        <option value="Speaking Engagement">Speaking Engagement</option>
                        <option value="Research Collaboration">Research Collaboration</option>
                        <option value="Media / Press Inquiry">Media / Press Inquiry</option>
                        <option value="Other">Other</option>
                    </select>
                    @error('subject') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-navy uppercase tracking-widest">Organization (Optional)</label>
                    <input wire:model="organization" type="text" class="w-full px-5 py-3 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all" placeholder="Company Name" />
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-xs font-bold text-navy uppercase tracking-widest">Message</label>
                <textarea wire:model="body" rows="6" class="w-full px-5 py-3 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all resize-none" placeholder="How can I help you?"></textarea>
                @error('body') <span class="text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="w-full py-4 bg-navy text-primary font-bold rounded-xl hover:bg-navy/90 transition-all shadow-lg flex items-center justify-center gap-2 group">
                <span>Send Message</span>
                <span class="material-symbols-outlined text-xl group-hover:translate-x-1 transition-transform">send</span>
                <div wire:loading class="animate-spin rounded-full h-4 w-4 border-2 border-primary border-t-transparent ml-2"></div>
            </button>
        </form>
    @endif
</div>