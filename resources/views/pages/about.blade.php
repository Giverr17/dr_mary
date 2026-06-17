@use('Illuminate\Support\Facades\Storage')
<x-app-layout>
    <x-slot name="title">About — Dr. Uhunoma M. Isibor</x-slot>

    <!-- Header Section -->
    <section class="py-20 px-6 bg-navy text-white text-center">
        <div class="max-w-4xl mx-auto">
            <span class="text-primary font-bold tracking-widest uppercase text-sm mb-4 block">Meet Dr. Uhunoma</span>
            <h1 class="text-4xl md:text-5xl font-display font-bold mb-6">Bridging Research & Strategy</h1>
            <p class="text-gray-400 text-lg md:text-xl font-light max-w-2xl mx-auto">
                Dedicated to empowering Black women entrepreneurs and advancing inclusive growth models.
            </p>
        </div>
    </section>

    <!-- Bio Section -->
    <section class="py-24 px-6 bg-white">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
            <div class="space-y-8">
                <div class="prose prose-slate prose-lg max-w-none">
                    <p class="text-slate-700 leading-relaxed">{{ $profile->bio_paragraph_1 }}</p>
                    <p class="text-slate-700 leading-relaxed">{{ $profile->bio_paragraph_2 }}</p>
                    <p class="text-slate-700 leading-relaxed">{{ $profile->bio_paragraph_3 }}</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    @foreach($profile->expertise_tags as $tag)
                    <span class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-full text-xs font-bold text-navy uppercase tracking-wider">{{ $tag }}</span>
                    @endforeach
                </div>
                @if($profile->speaker_kit_path)
                <div class="pt-4">
                    <a href="{{ Storage::url($profile->speaker_kit_path) }}" target="_blank" class="inline-flex items-center gap-3 px-6 py-3 bg-navy text-primary font-bold rounded-xl hover:bg-navy/90 transition-all shadow-lg group">
                        <span class="material-symbols-outlined text-xl">download</span>
                        Download Speaker Kit
                        <span class="material-symbols-outlined text-sm opacity-60 group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </a>
                </div>
                @endif
            </div>
            <div class="relative group">
                <div class="absolute -inset-4 bg-primary/10 rounded-2xl group-hover:bg-primary/20 transition-colors"></div>
                <img src="{{ $profile->photo_path ? Storage::url($profile->photo_path) : 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=800' }}" alt="Dr. Uhunoma" class="relative rounded-xl w-full h-[600px] object-cover shadow-2xl" />
            </div>
        </div>
    </section>

    <!-- Credentials Section -->
    <section class="py-24 px-6 bg-background-light">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <span class="text-primary font-bold tracking-widest uppercase text-sm">Academic & Professional</span>
                <h2 class="text-3xl md:text-4xl font-display font-bold text-navy mt-2">Credentials</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($credentials as $credential)
                <div class="p-8 rounded-2xl bg-white border border-gray-100 shadow-sm">
                    <h3 class="text-xl font-bold text-navy mb-2">{{ $credential->title }}</h3>
                    <p class="text-primary font-bold text-sm mb-4">{{ $credential->institution }}</p>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        {{ $credential->description }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Achievements & Awards Section -->
    @if(count($achievements) > 0)
    <section class="py-24 px-6 bg-slate-50 border-t border-gray-100">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <span class="text-primary font-bold tracking-widest uppercase text-sm">Honors & Milestones</span>
                <h2 class="text-3xl md:text-4xl font-display font-bold text-navy mt-2">Achievements & Awards</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($achievements as $achievement)
                <div class="p-8 rounded-2xl bg-white border border-slate-100 hover:border-primary/30 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between group">
                    <div>
                        <div class="flex justify-between items-start gap-4 mb-4">
                            <span class="px-3 py-1 text-[10px] font-bold rounded-full uppercase tracking-wider
                                {{ [
                                    'Award' => 'bg-amber-50 text-amber-700 border border-amber-200/50',
                                    'Recognition' => 'bg-blue-50 text-blue-700 border border-blue-200/50',
                                    'Fellowship' => 'bg-purple-50 text-purple-700 border border-purple-200/50',
                                    'Certification' => 'bg-green-50 text-green-700 border border-green-200/50',
                                ][$achievement->category] ?? 'bg-slate-50 text-slate-700 border border-slate-200/50' }}">
                                {{ $achievement->category }}
                            </span>
                            <span class="text-sm font-bold text-primary">{{ $achievement->year }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-navy mb-2 group-hover:text-primary transition-colors">{{ $achievement->title }}</h3>
                        @if($achievement->issuing_body)
                            <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider mb-4">{{ $achievement->issuing_body }}</p>
                        @endif
                        <p class="text-slate-600 text-sm leading-relaxed mb-6 font-light">
                            {{ $achievement->description }}
                        </p>
                    </div>

                    @if($achievement->link_url)
                    <div class="pt-4 border-t border-slate-50 space-y-4">
                        @if($achievement->link_preview_title)
                        <a href="{{ $achievement->link_url }}" target="_blank" class="block rounded-xl overflow-hidden border border-slate-100 bg-slate-50/50 hover:bg-slate-50 hover:border-primary/20 transition-all flex gap-3 p-2.5 group/preview select-none">
                            @if($achievement->link_preview_image)
                            <div class="w-16 h-16 shrink-0 rounded-lg overflow-hidden bg-slate-100">
                                <img src="{{ $achievement->link_preview_image }}" alt="Preview" class="w-full h-full object-cover group-hover/preview:scale-105 transition-transform duration-300" onerror="this.style.display='none';" />
                            </div>
                            @endif
                            <div class="flex-1 min-w-0 flex flex-col justify-center">
                                <span class="text-[9px] font-bold text-primary uppercase tracking-widest block mb-0.5">{{ parse_url($achievement->link_url, PHP_URL_HOST) }}</span>
                                <h4 class="text-xs font-bold text-navy truncate group-hover/preview:text-primary transition-colors leading-tight mb-1" title="{{ $achievement->link_preview_title }}">{{ $achievement->link_preview_title }}</h4>
                                @if($achievement->link_preview_description)
                                <p class="text-[10px] text-slate-500 line-clamp-2 leading-relaxed font-light">{{ $achievement->link_preview_description }}</p>
                                @endif
                            </div>
                        </a>
                        @else
                        <!-- Gorgeous Fallback Preview if crawling fails or server is offline -->
                        @php
                            $fallbackImages = [
                                'Award' => 'https://images.unsplash.com/photo-1578575437130-527eed3abbec?q=80&w=150',
                                'Recognition' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?q=80&w=150',
                                'Fellowship' => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=150',
                                'Certification' => 'https://images.unsplash.com/photo-1589330273594-fade1ee91647?q=80&w=150',
                            ];
                            $fallbackImage = $fallbackImages[$achievement->category] ?? 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?q=80&w=150';
                        @endphp
                        <a href="{{ $achievement->link_url }}" target="_blank" class="block rounded-xl overflow-hidden border border-slate-100 bg-slate-50/50 hover:bg-slate-50 hover:border-primary/20 transition-all flex gap-3 p-2.5 group/preview select-none">
                            <div class="w-16 h-16 shrink-0 rounded-lg overflow-hidden bg-slate-100 border border-slate-200/50">
                                <img src="{{ $fallbackImage }}" alt="Preview" class="w-full h-full object-cover group-hover/preview:scale-105 transition-transform duration-300" />
                            </div>
                            <div class="flex-1 min-w-0 flex flex-col justify-center">
                                <span class="text-[9px] font-bold text-primary uppercase tracking-widest block mb-0.5">{{ parse_url($achievement->link_url, PHP_URL_HOST) }}</span>
                                <h4 class="text-xs font-bold text-navy truncate group-hover/preview:text-primary transition-colors leading-tight mb-1">
                                    {{ $achievement->link_label ?: 'External Reference' }}
                                </h4>
                                <p class="text-[10px] text-slate-500 line-clamp-2 leading-relaxed font-light">Open reference link: {{ $achievement->link_url }}</p>
                            </div>
                        </a>
                        @endif

                        <a href="{{ $achievement->link_url }}" target="_blank" class="inline-flex items-center gap-2 text-xs font-bold text-navy hover:text-primary transition-colors group/link">
                            <span class="material-symbols-outlined text-sm">open_in_new</span>
                            {{ $achievement->link_label ?: 'View Reference' }}
                            <span class="material-symbols-outlined text-xs opacity-0 group-hover/link:opacity-100 group-hover/link:translate-x-1 transition-all">arrow_forward</span>
                        </a>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Core Values Section -->
    <section class="py-24 px-6 bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <span class="text-primary font-bold tracking-widest uppercase text-sm">Guided By</span>
                <h2 class="text-3xl md:text-4xl font-display font-bold text-navy mt-2">Core Values</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($coreValues as $value)
                <div class="text-center p-8 rounded-2xl hover:bg-slate-50 transition-colors">
                    <span class="material-symbols-outlined text-primary text-5xl mb-6">{{ $value->icon }}</span>
                    <h3 class="text-xl font-bold text-navy mb-4">{{ $value->title }}</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        {{ $value->description }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

</x-app-layout>
