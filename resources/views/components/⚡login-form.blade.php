<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    #[Validate('required|email')]
    public $email = '';

    #[Validate('required')]
    public $password = '';

    public $remember = false;

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            return redirect()->intended('/manage');
        }

        $this->addError('email', 'The provided credentials do not match our records.');
    }
};
?>

<div class="w-full max-w-md mx-auto">
    <form wire:submit="login" class="bg-white p-10 rounded-3xl border border-slate-200 shadow-xl space-y-6">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-display font-bold text-navy">Admin Access</h1>
            <p class="text-slate-500 text-sm">Please enter your credentials to continue.</p>
        </div>

        <div class="space-y-2">
            <label class="text-xs font-bold text-navy uppercase tracking-widest">Email Address</label>
            <input wire:model="email" type="email" class="w-full px-5 py-3 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-primary outline-none transition-all" placeholder="admin@druhunoma.com" />
            @error('email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="space-y-2">
            <label class="text-xs font-bold text-navy uppercase tracking-widest">Password</label>
            <input wire:model="password" type="password" class="w-full px-5 py-3 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-primary outline-none transition-all" placeholder="••••••••" />
            @error('password') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input wire:model="remember" type="checkbox" class="w-4 h-4 text-primary border-slate-300 rounded focus:ring-primary" />
                <span class="text-xs font-bold text-navy uppercase tracking-widest">Remember Me</span>
            </label>
        </div>

        <button type="submit" class="w-full py-4 bg-navy text-primary font-bold rounded-xl hover:bg-navy/90 transition-all shadow-lg flex items-center justify-center gap-2">
            <span>Sign In</span>
            <span class="material-symbols-outlined text-xl">login</span>
        </button>
    </form>
</div>