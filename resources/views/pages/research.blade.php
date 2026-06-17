@use('Illuminate\Support\Facades\Storage')
<x-app-layout>
    <x-slot name="title">Research — Dr. Uhunoma M. Isibor</x-slot>

    <!-- Header Section -->
    <section class="py-20 px-6 bg-navy text-white text-center">
        <div class="max-w-4xl mx-auto">
            <span class="text-primary font-bold tracking-widest uppercase text-sm mb-4 block">Knowledge & Impact</span>
            <h1 class="text-4xl md:text-5xl font-display font-bold mb-6">Research & Publications</h1>
            <p class="text-gray-400 text-lg md:text-xl font-light max-w-2xl mx-auto">
                Evidence-based studies focusing on policy, entrepreneurship, and sustainable growth.
            </p>
        </div>
    </section>

    <!-- Focus Areas Section -->
    <section class="py-24 px-6 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="mb-16">
                <span class="text-primary font-bold tracking-widest uppercase text-sm">Main Focus</span>
                <h2 class="text-3xl md:text-4xl font-display font-bold text-navy mt-2">Research Specializations</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($focusAreas as $area)
                <div class="p-10 rounded-2xl bg-background-light border border-slate-100 hover:border-primary/30 transition-all group">
                    <span class="material-symbols-outlined text-primary text-5xl mb-8 group-hover:scale-110 transition-transform block">{{ $area->icon }}</span>
                    <h3 class="text-xl font-bold text-navy mb-4">{{ $area->title }}</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        {{ $area->description }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Publications Section -->
    <section class="py-24 px-6 bg-slate-50 border-y border-slate-200">
        <div class="max-w-7xl mx-auto">
            <div class="mb-16 text-center">
                <span class="text-primary font-bold tracking-widest uppercase text-sm">Full Catalog</span>
                <h2 class="text-3xl md:text-4xl font-display font-bold text-navy mt-2">Academic & Policy Publications</h2>
            </div>

            <div class="space-y-16">
                @foreach($publications as $type => $typePublications)
                <div>
                    <h3 class="text-2xl font-display font-bold text-navy mb-8 border-b-2 border-primary w-fit pb-2">{{ $type }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach($typePublications as $publication)
                        <div class="p-8 bg-white rounded-2xl shadow-sm border border-slate-100 flex flex-col">
                            <div class="flex justify-between items-start mb-4">
                                <span class="px-3 py-1 bg-primary/10 text-primary text-[10px] font-bold rounded-full uppercase tracking-widest">{{ $publication->year }}</span>
                                @if($publication->is_featured)
                                <span class="material-symbols-outlined text-primary" title="Featured">star</span>
                                @endif
                            </div>
                            <h4 class="text-xl font-bold text-navy mb-4 leading-snug">{{ $publication->title }}</h4>
                            <p class="text-slate-600 text-sm leading-relaxed mb-8 flex-1 italic">
                                "{{ $publication->abstract }}"
                            </p>
                            @if($publication->pdf_path)
                            <a href="{{ Storage::url($publication->pdf_path) }}" class="flex items-center gap-2 text-navy font-bold text-sm hover:text-primary transition-colors mt-auto">
                                <span class="material-symbols-outlined text-lg">download</span>
                                Download Publication
                            </a>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

</x-app-layout>
