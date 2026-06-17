@use('Illuminate\Support\Facades\Storage')
<x-app-layout>
    <x-slot name="title">{{ $profile->full_name }} — Portfolio</x-slot>

    <!-- Hero Section -->
    <section class="relative px-6 py-12 md:py-24 bg-background-light">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div class="flex flex-col gap-8 order-2 md:order-1 text-center md:text-left">
                <div class="flex flex-col gap-4">
                    <h1 class="text-4xl md:text-6xl font-display font-bold text-navy leading-[1.15]">
                        {!! str_replace('. ', '.<br />', $profile->title_line) !!}
                    </h1>
                    <p class="text-lg md:text-xl text-slate-700 leading-relaxed font-body font-light">
                        {{ $profile->hero_tagline }}
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row flex-wrap gap-4 mt-2 justify-center md:justify-start">
                    @php
                        $latestBrief = $featuredPublications->first()?->pdf_path ? Storage::url($featuredPublications->first()->pdf_path) : '#research-focus';
                    @endphp
                    <a href="{{ $latestBrief }}" {{ $featuredPublications->first()?->pdf_path ? 'download' : '' }} class="flex items-center justify-center px-6 h-12 bg-navy hover:bg-navy/90 text-primary font-bold rounded-lg transition-all shadow-lg text-sm md:text-base border border-navy">
                        Download Research Briefs
                    </a>
                    @if($featuredEvent)
                    <a href="{{ $featuredEvent->registration_url ?? '#upcoming-event' }}" target="{{ $featuredEvent->registration_url ? '_blank' : '_self' }}" class="flex items-center justify-center px-6 h-12 bg-primary hover:bg-primary-dark text-navy font-bold rounded-lg transition-all shadow-lg text-sm md:text-base border border-primary">
                        Join the Summit
                    </a>
                    @endif
                    <a href="/contact" class="flex items-center justify-center px-6 h-12 border-2 border-navy text-navy font-bold rounded-lg hover:bg-navy hover:text-white transition-all text-sm md:text-base bg-transparent">
                        Book a Consultation
                    </a>
                </div>
            </div>
            <div class="order-1 md:order-2 flex justify-center md:justify-end">
                <div class="relative w-64 h-64 md:w-96 md:h-96 rounded-full overflow-hidden border-4 border-primary shadow-2xl">
                    <img src="{{ $profile->photo_path ? Storage::url($profile->photo_path) : 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=800' }}" alt="{{ $profile->full_name }}" class="w-full h-full object-cover" />
                </div>
            </div>
        </div>
    </section>

    <!-- Stats / Focus Areas -->
    <section class="py-16 px-6 bg-white border-y border-gray-100">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="flex flex-col gap-2 p-6 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-3xl font-display font-bold text-primary">{{ $profile->stat_years }}</span>
                <span class="text-sm font-bold text-navy uppercase tracking-widest">Experience</span>
                <p class="text-slate-600 text-sm">Of dedicated research and strategic advisory.</p>
            </div>
            <div class="flex flex-col gap-2 p-6 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-3xl font-display font-bold text-primary">{{ $profile->stat_focus }}</span>
                <span class="text-sm font-bold text-navy uppercase tracking-widest">Focus</span>
                <p class="text-slate-600 text-sm">Bridging gaps across Canada and Africa.</p>
            </div>
            <div class="flex flex-col gap-2 p-6 rounded-xl bg-slate-50 border border-slate-100">
                <span class="text-3xl font-display font-bold text-primary">{{ $profile->stat_approach }}</span>
                <span class="text-sm font-bold text-navy uppercase tracking-widest">Approach</span>
                <p class="text-slate-600 text-sm">Methodological rigor with practical impact.</p>
            </div>
        </div>
    </section>

    <!-- Research Focus Area Preview -->
    <section id="research-focus" class="py-24 px-6 scroll-mt-20">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
                <div class="max-w-2xl">
                    <span class="text-primary font-bold tracking-widest uppercase text-sm">Research Focus</span>
                    <h2 class="text-3xl md:text-4xl font-display font-bold text-navy mt-2">Pioneering Inclusive Growth</h2>
                </div>
                <a href="/research" class="text-navy font-bold flex items-center gap-2 hover:text-primary transition-colors">
                    View All Research <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($focusAreas as $area)
                <div class="p-8 rounded-2xl bg-white border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                    <span class="material-symbols-outlined text-primary text-4xl mb-6">{{ $area->icon }}</span>
                    <h3 class="text-xl font-bold text-navy mb-4">{{ $area->title }}</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        {{ $area->description }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Services Preview -->
    <section class="py-24 px-6 bg-navy text-white overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-primary font-bold tracking-widest uppercase text-sm">Consulting Services</span>
                <h2 class="text-3xl md:text-4xl font-display font-bold mt-2 text-white">Strategic Advisory & Implementation</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($services as $service)
                <div class="flex flex-col p-8 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 transition-colors relative group">
                    @if($service->is_popular)
                    <span class="absolute top-4 right-4 bg-primary text-navy text-[10px] font-bold px-2 py-1 rounded uppercase">Popular</span>
                    @endif
                    <span class="material-symbols-outlined text-primary text-3xl mb-6">{{ $service->icon }}</span>
                    <h3 class="text-xl font-bold mb-4">{{ $service->title }}</h3>
                    <p class="text-gray-400 text-sm leading-relaxed mb-6">
                        {{ $service->description }}
                    </p>
                    <ul class="space-y-3 mb-8">
                        @foreach($service->bullet_points as $point)
                        <li class="flex items-center gap-2 text-sm text-gray-300">
                            <span class="material-symbols-outlined text-primary text-lg">check_circle</span>
                            {{ $point }}
                        </li>
                        @endforeach
                    </ul>
                    <a href="/consulting" class="mt-auto flex items-center gap-2 text-primary font-bold text-sm hover:underline">
                        Learn More <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Event Banner -->
    @if($featuredEvent)
    <section id="upcoming-event" class="py-12 px-6 scroll-mt-20">
        <div class="max-w-7xl mx-auto bg-primary rounded-3xl p-8 md:p-12 flex flex-col md:flex-row items-center justify-between gap-8 relative overflow-hidden">
            <div class="relative z-10">
                <span class="bg-navy text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest">Upcoming Event</span>
                <h2 class="text-3xl md:text-5xl font-display font-bold text-navy mt-4 mb-4">{{ $featuredEvent->title }}</h2>
                <div class="flex flex-wrap gap-6 text-navy/80 font-bold">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined">calendar_today</span>
                        {{ $featuredEvent->date_start->format('M d, Y') }}
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined">location_on</span>
                        {{ $featuredEvent->location }}
                    </div>
                </div>
            </div>
            <div class="relative z-10 shrink-0">
                <a href="{{ $featuredEvent->registration_url ?? route('events') }}" target="{{ $featuredEvent->registration_url ? '_blank' : '_self' }}" class="inline-flex items-center justify-center px-10 py-4 bg-navy text-primary font-bold rounded-xl hover:bg-navy/90 transition-all shadow-xl">
                    Register Now
                </a>
            </div>
        </div>
    </section>
    @endif

</x-app-layout>
