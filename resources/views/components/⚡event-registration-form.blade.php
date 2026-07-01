<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\EventRegistration;

new class extends Component
{
    public $event;
    public $isOpen = false;

    #[Validate('required|min:3')]
    public $full_name = '';
    #[Validate('required|email')]
    public $email = '';
    public $organization = '';
    public $job_title = '';
    public $message = '';

    public function mount($event)
    {
        $this->event = $event;
    }

    public function register()
    {
        $this->validate();

        EventRegistration::create([
            'event_id' => $this->event->id,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'organization' => $this->organization,
            'job_title' => $this->job_title,
            'message' => $this->message,
        ]);

        $this->reset(['full_name', 'email', 'organization', 'job_title', 'message']);
        $this->isOpen = false;
        $this->dispatch('notify', 'Registration successful! We will contact you soon.');
    }
};
?>

<div>
    <button @click="$wire.isOpen = true" class="inline-flex items-center justify-center px-10 py-4 bg-primary text-navy font-bold rounded-xl hover:bg-primary-dark transition-all shadow-xl w-fit">
        Register for Event
    </button>

    <div x-show="$wire.isOpen" x-cloak class="fixed inset-0 z-50 flex items-start sm:items-center justify-center p-4 bg-navy/60 backdrop-blur-sm overflow-y-auto">
        <div @click.away="$wire.isOpen = false" class="bg-white rounded-[2rem] p-8 md:p-12 max-w-2xl w-full shadow-2xl relative my-auto max-h-[90vh] overflow-y-auto">
            <button @click="$wire.isOpen = false" class="absolute top-8 right-8 text-slate-400 hover:text-navy transition-colors">
                <span class="material-symbols-outlined text-3xl">close</span>
            </button>

            <div class="mb-8">
                <span class="text-primary font-bold tracking-widest uppercase text-xs">Event Registration</span>
                <h2 class="text-2xl md:text-3xl font-display font-bold text-navy mt-2">{{ $event->title }}</h2>
                <p class="text-slate-500 mt-2 text-sm">{{ $event->date_start->format('M d, Y') }} — {{ $event->location }}</p>
            </div>

            <form wire:submit="register" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-navy uppercase tracking-widest">Full Name</label>
                        <input wire:model="full_name" type="text" class="w-full px-6 py-4 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary outline-none" placeholder="John Doe" />
                        @error('full_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-navy uppercase tracking-widest">Email Address</label>
                        <input wire:model="email" type="email" class="w-full px-6 py-4 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary outline-none" placeholder="john@example.com" />
                        @error('email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-navy uppercase tracking-widest">Organization</label>
                        <input wire:model="organization" type="text" class="w-full px-6 py-4 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary outline-none" placeholder="Company/Institution" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-navy uppercase tracking-widest">Job Title</label>
                        <input wire:model="job_title" type="text" class="w-full px-6 py-4 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary outline-none" placeholder="Director/Professor" />
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-navy uppercase tracking-widest">Message (Optional)</label>
                    <textarea wire:model="message" rows="4" class="w-full px-6 py-4 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary outline-none resize-none" placeholder="Any specific questions or requirements?"></textarea>
                </div>

                <button type="submit" class="w-full py-5 bg-navy text-primary font-bold rounded-xl hover:opacity-90 transition-all shadow-xl text-lg mt-4 flex items-center justify-center gap-3">
                    <span wire:loading.remove>Confirm Registration</span>
                    <span wire:loading class="animate-spin material-symbols-outlined">progress_activity</span>
                    <span wire:loading>Processing...</span>
                </button>
            </form>
        </div>
    </div>
</div>