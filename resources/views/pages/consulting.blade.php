<x-app-layout>
    <x-slot name="title">Consulting — Dr. Mary</x-slot>

    <!-- Header Section -->
    <section class="py-20 px-6 bg-navy text-white text-center">
        <div class="max-w-4xl mx-auto">
            <span class="text-primary font-bold tracking-widest uppercase text-sm mb-4 block">Strategic Partnership</span>
            <h1 class="text-4xl md:text-5xl font-display font-bold mb-6">Consulting Services</h1>
            <p class="text-gray-400 text-lg md:text-xl font-light max-w-2xl mx-auto">
                Tailored strategies and research-backed advisory for organizations and individuals.
            </p>
        </div>
    </section>

    <!-- Services Grid -->
    <section class="py-24 px-6 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($services as $service)
                <div class="flex flex-col p-10 rounded-2xl border {{ $service->is_popular ? 'border-primary shadow-xl ring-1 ring-primary/20' : 'border-slate-100 shadow-sm' }} relative group">
                    @if($service->is_popular)
                    <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-primary text-navy text-[10px] font-bold px-4 py-1 rounded-full uppercase tracking-widest">Most Popular</span>
                    @endif
                    <span class="material-symbols-outlined text-primary text-4xl mb-6">{{ $service->icon }}</span>
                    <h3 class="text-2xl font-bold text-navy mb-4">{{ $service->title }}</h3>
                    <p class="text-slate-600 text-sm leading-relaxed mb-8 flex-1">
                        {{ $service->description }}
                    </p>
                    <ul class="space-y-4 mb-10">
                        @foreach($service->bullet_points as $point)
                        <li class="flex items-start gap-3 text-sm text-slate-700">
                            <span class="material-symbols-outlined text-primary text-xl">check_circle</span>
                            {{ $point }}
                        </li>
                        @endforeach
                    </ul>
                    <a href="/contact" class="inline-flex items-center justify-center px-6 py-3 {{ $service->is_popular ? 'bg-primary text-navy' : 'bg-navy text-primary' }} font-bold rounded-lg hover:opacity-90 transition-all">
                        Inquire Now
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section class="py-24 px-6 bg-background-light">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <span class="text-primary font-bold tracking-widest uppercase text-sm">How We Work</span>
                <h2 class="text-3xl md:text-4xl font-display font-bold text-navy mt-2">Consultation Process</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 relative">
                <!-- Connector Line (Desktop) -->
                <div class="hidden md:block absolute top-1/2 left-0 w-full h-0.5 bg-primary/20 -translate-y-1/2"></div>
                
                @foreach($steps as $step)
                <div class="relative z-10 flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-full bg-navy text-primary flex items-center justify-center text-2xl font-bold border-4 border-primary shadow-xl mb-6">
                        {{ $step->step_number }}
                    </div>
                    <h3 class="text-xl font-bold text-navy mb-4">{{ $step->title }}</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        {{ $step->description }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-24 px-6 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <span class="text-primary font-bold tracking-widest uppercase text-sm">Client Feedback</span>
                <h2 class="text-3xl md:text-4xl font-display font-bold text-navy mt-2">What Partners Say</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($testimonials as $testimonial)
                <div class="p-10 rounded-3xl bg-slate-50 border border-slate-100 relative">
                    <p class="text-slate-700 text-lg italic leading-relaxed mb-8 relative z-10">
                        "{{ $testimonial->quote }}"
                    </p>
                    <div>
                        <h4 class="font-bold text-navy">{{ $testimonial->author_name }}</h4>
                        <p class="text-sm text-primary font-medium">{{ $testimonial->author_title }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

</x-app-layout>
