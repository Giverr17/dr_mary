@use('Illuminate\Support\Facades\Storage')
<x-app-layout>
    <x-slot name="title">Contact — Dr. Uhunoma M. Isibor</x-slot>


    <!-- Header Section -->
    <section class="py-20 px-6 bg-navy text-white text-center">
        <div class="max-w-4xl mx-auto">
            <span class="text-primary font-bold tracking-widest uppercase text-sm mb-4 block">Get in Touch</span>
            <h1 class="text-4xl md:text-5xl font-display font-bold mb-6">Let's Collaborate</h1>
            <p class="text-gray-400 text-lg md:text-xl font-light max-w-2xl mx-auto">
                Whether you're interested in research collaboration, consulting, or speaking engagements.
            </p>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-24 px-6 bg-white">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16">
            <!-- Contact Info -->
            <div class="space-y-12">
                <div>
                    <h2 class="text-3xl font-display font-bold text-navy mb-6">Contact Information</h2>
                    <p class="text-slate-600 leading-relaxed mb-10">
                        I aim to respond to all inquiries within {{ $profile->response_time }}. For urgent media requests, please specify "URGENT" in the subject line.
                    </p>
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-primary shrink-0">
                                <span class="material-symbols-outlined">mail</span>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-navy uppercase tracking-widest mb-1">Email Address</span>
                                <a href="mailto:{{ $profile->email }}" class="text-lg font-bold text-slate-700 hover:text-primary transition-colors">{{ $profile->email }}</a>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-primary shrink-0">
                                <span class="material-symbols-outlined">location_on</span>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-navy uppercase tracking-widest mb-1">Office Location</span>
                                <span class="text-lg font-bold text-slate-700">{{ $profile->location }}</span>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-primary shrink-0">
                                <span class="material-symbols-outlined">link</span>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-navy uppercase tracking-widest mb-1">Social Media</span>
                                <div class="flex flex-wrap gap-4 mt-2">
                                    @if($profile->social_links)
                                        @foreach($profile->social_links as $link)
                                        <a href="{{ $link['url'] }}" target="_blank" class="text-sm font-bold text-primary hover:underline">{{ ucfirst($link['platform']) }}</a>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                

                <!-- Speaker Kit Card -->
                @if($profile->speaker_kit_path)
                <div class="p-8 rounded-3xl bg-background-light border border-slate-200 relative overflow-hidden">
                    <div class="relative z-10 flex items-center justify-between gap-6">
                        <div>
                            <h3 class="text-xl font-display font-bold text-navy mb-2">Speaker Kit</h3>
                            <p class="text-slate-500 text-sm">
                                Download bio, headshots, and session descriptions for event organizers.
                            </p>
                        </div>
                        <a href="{{ Storage::url($profile->speaker_kit_path) }}" target="_blank" class="shrink-0 inline-flex items-center gap-2 px-6 py-3 bg-navy text-primary font-bold rounded-xl hover:bg-navy/90 transition-all shadow-lg">
                            <span class="material-symbols-outlined text-lg">download</span>
                            Download
                        </a>
                    </div>
                    <span class="material-symbols-outlined text-[8rem] absolute -bottom-6 -right-6 text-navy/5 pointer-events-none">description</span>
                </div>
                @endif
            </div>

            <!-- Contact Form (Placeholder for Livewire) -->
            <div class="bg-slate-50 p-8 md:p-12 rounded-3xl border border-slate-100">
                <h2 class="text-2xl font-display font-bold text-navy mb-8">Send a Message</h2>
                <livewire:contact-form />
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-24 px-6 bg-background-light border-t border-gray-100">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-16">
                <span class="text-primary font-bold tracking-widest uppercase text-sm">Common Questions</span>
                <h2 class="text-3xl md:text-4xl font-display font-bold text-navy mt-2">Frequently Asked Questions</h2>
            </div>
            <div class="space-y-4">
                @foreach($faqs as $faq)
                <div x-data="{ open: false }" class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
                    <button @click="open = !open" class="w-full px-8 py-6 flex items-center justify-between text-left group">
                        <span class="font-bold text-navy group-hover:text-primary transition-colors">{{ $faq->question }}</span>
                        <span class="material-symbols-outlined text-primary transition-transform" :class="open ? 'rotate-180' : ''">expand_more</span>
                    </button>
                    <div x-show="open" x-cloak x-collapse class="px-8 pb-6 text-slate-600 text-sm leading-relaxed">
                        {{ $faq->answer }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

</x-app-layout>
