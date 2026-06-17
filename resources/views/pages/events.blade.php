<x-app-layout>
    <x-slot name="title">Events — Dr. Uhunoma M. Isibor</x-slot>

    <!-- Header Section -->
    <section class="py-20 px-6 bg-navy text-white text-center">
        <div class="max-w-4xl mx-auto">
            <span class="text-primary font-bold tracking-widest uppercase text-sm mb-4 block">Engagements & Speaking</span>
            <h1 class="text-4xl md:text-5xl font-display font-bold mb-6">Upcoming & Past Events</h1>
            <p class="text-gray-400 text-lg md:text-xl font-light max-w-2xl mx-auto">
                Join Dr. Uhunoma at global summits, workshops, and policy forums.
            </p>
        </div>
    </section>

    <!-- Featured Event Section -->
    @if($featuredEvents->count() > 0)
    <section class="py-24 px-6 bg-white">
        <div class="max-w-7xl mx-auto space-y-16">
            <div class="mb-12">
                <span class="text-primary font-bold tracking-widest uppercase text-sm">Featured Engagement</span>
                <h2 class="text-3xl md:text-4xl font-display font-bold text-navy mt-2">Highlights</h2>
            </div>
            
            @foreach($featuredEvents as $featuredEvent)
            <div class="bg-navy rounded-[2rem] overflow-hidden flex flex-col lg:flex-row shadow-2xl">
                <div class="lg:w-1/2 p-8 md:p-16 flex flex-col justify-center">
                    <span class="inline-block bg-primary text-navy text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest mb-6 w-fit">Upcoming</span>
                    <h3 class="text-3xl md:text-5xl font-display font-bold text-white mb-6 leading-tight">{{ $featuredEvent->title }}</h3>
                    <p class="text-gray-400 text-lg mb-10 leading-relaxed">
                        {{ $featuredEvent->description }}
                    </p>
                    <div class="grid grid-cols-2 gap-8 mb-12">
                        <div class="flex flex-col gap-1">
                            <span class="text-primary text-xs uppercase font-bold tracking-tighter">Date</span>
                            <span class="text-white font-medium">{{ $featuredEvent->date_start->format('M d, Y') }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-primary text-xs uppercase font-bold tracking-tighter">Location</span>
                            <span class="text-white font-medium">{{ $featuredEvent->location }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-primary text-xs uppercase font-bold tracking-tighter">Role</span>
                            <span class="text-white font-medium">{{ $featuredEvent->role }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-primary text-xs uppercase font-bold tracking-tighter">Attendance</span>
                            <span class="text-white font-medium">{{ $featuredEvent->attendee_count }}</span>
                        </div>
                    </div>
                    @if($featuredEvent->registration_url)
                        <a href="{{ $featuredEvent->registration_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center px-10 py-4 bg-primary text-navy font-bold rounded-xl hover:bg-primary-dark transition-all shadow-xl w-fit">
                            Register for Event
                        </a>
                    @else
                        <livewire:event-registration-form :event="$featuredEvent" />
                    @endif
                </div>
                <div class="lg:w-1/2 bg-slate-800 relative min-h-[400px]">
                    @if($featuredEvent->image_path)
                        <img src="{{ Storage::url($featuredEvent->image_path) }}" alt="{{ $featuredEvent->title }}" class="absolute inset-0 w-full h-full object-cover" />
                    @else
                        <img src="https://images.unsplash.com/photo-1475721027187-4024733924f7?q=80&w=1200" alt="Featured Event" class="absolute inset-0 w-full h-full object-cover opacity-60" />
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-navy via-transparent to-transparent"></div>
                    @if($featuredEvent->stats)
                    <div class="absolute bottom-8 left-8 right-8 grid grid-cols-2 gap-4">
                        @foreach($featuredEvent->stats as $key => $val)
                        <div class="p-4 rounded-xl bg-white/10 backdrop-blur-md border border-white/10">
                            <span class="block text-primary text-xl font-bold font-display">{{ $val }}</span>
                            <span class="block text-white/60 text-[10px] uppercase font-bold">{{ $key }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Other Upcoming Events -->
    @if($upcomingEvents->count() > 0)
    <section class="py-24 px-6 bg-slate-50 border-y border-slate-200">
        <div class="max-w-7xl mx-auto">
            <div class="mb-16">
                <span class="text-primary font-bold tracking-widest uppercase text-sm">On the Calendar</span>
                <h2 class="text-3xl md:text-4xl font-display font-bold text-navy mt-2">Upcoming Appearances</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($upcomingEvents as $event)
                <div class="p-8 bg-white rounded-2xl border border-slate-100 shadow-sm flex flex-col md:flex-row gap-8">
                    <div class="md:w-32 shrink-0 text-center flex flex-col items-center justify-center border-b md:border-b-0 md:border-r border-slate-100 pb-4 md:pb-0 md:pr-8">
                        <span class="text-3xl font-display font-bold text-navy">{{ $event->date_start->format('d') }}</span>
                        <span class="text-sm font-bold text-primary uppercase tracking-widest">{{ $event->date_start->format('M') }}</span>
                        <span class="text-xs text-slate-400 mt-1">{{ $event->date_start->format('Y') }}</span>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-xl font-bold text-navy mb-3">{{ $event->title }}</h4>
                        <div class="flex flex-wrap gap-4 text-xs font-bold text-slate-500 uppercase tracking-tighter mb-6">
                            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">location_on</span>{{ $event->location }}</span>
                            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">schedule</span>{{ $event->time }}</span>
                        </div>
                        <a href="{{ $event->link_url ?? '#' }}" class="text-primary font-bold text-sm hover:underline">{{ $event->link_label ?? 'View Details' }} →</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Revisit Past Events (Media Archive) -->
    @if(count($mediaArchives) > 0)
    <section class="py-24 px-6 bg-slate-50 border-t border-slate-200">
        <div class="max-w-7xl mx-auto">
            <div class="text-center md:text-left mb-16 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <span class="text-primary font-bold tracking-widest uppercase text-sm">Media Replays</span>
                    <h2 class="text-3xl md:text-4xl font-display font-bold text-navy mt-2">Revisit Past Events</h2>
                    <p class="text-slate-500 mt-2 font-light max-w-xl">Listen to podcast episodes, watch keynote speeches, and watch video replays of previous engagements.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                @foreach($mediaArchives as $media)
                <div x-data="{ activeTab: '{{ $media->embed_url ? 'video' : 'audio' }}' }" class="bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between group">
                    <div class="p-6 md:p-8 space-y-6">
                        @if($media->embed_url && $media->audio_url)
                        <div class="flex bg-slate-100 p-1 rounded-xl gap-1">
                            <button @click="activeTab = 'video'" :class="activeTab === 'video' ? 'bg-navy text-primary shadow-sm' : 'text-slate-600 hover:text-navy'" class="flex-1 py-2 text-xs font-bold rounded-lg transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                                <span class="material-symbols-outlined text-sm">play_circle</span> Watch Video
                            </button>
                            <button @click="activeTab = 'audio'" :class="activeTab === 'audio' ? 'bg-navy text-primary shadow-sm' : 'text-slate-600 hover:text-navy'" class="flex-1 py-2 text-xs font-bold rounded-lg transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                                <span class="material-symbols-outlined text-sm">headset</span> Listen to Audio
                            </button>
                        </div>
                        @endif

                        <!-- Media Player Widget -->
                        <div class="rounded-2xl overflow-hidden shadow-md bg-slate-900 border border-slate-100/50">
                            @if($media->embed_url)
                                <div x-show="activeTab === 'video'" class="aspect-video relative w-full bg-black">
                                    <iframe src="{{ $media->clean_embed_url }}" class="absolute inset-0 w-full h-full" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                                </div>
                            @endif
                            @if($media->audio_url)
                                <div x-show="activeTab === 'audio'" class="w-full bg-slate-900">
                                    <iframe src="{{ $media->clean_audio_url }}" width="100%" height="232" frameborder="0" allowtransparency="true" allow="encrypted-media" class="w-full"></iframe>
                                </div>
                            @endif
                        </div>

                        <!-- Info and Metadata -->
                        <div class="space-y-3">
                            <div class="flex flex-wrap items-center gap-3">
                                @if($media->embed_url)
                                <span x-show="activeTab === 'video'" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-red-50 text-red-700 border border-red-200/50">
                                    <span class="material-symbols-outlined text-xs">play_circle</span>
                                    Video
                                </span>
                                @endif
                                @if($media->audio_url)
                                <span x-show="activeTab === 'audio'" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-green-50 text-green-700 border border-green-200/50">
                                    <span class="material-symbols-outlined text-xs">headset</span>
                                    Audio
                                </span>
                                @endif
                                
                                @if($media->duration)
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-full uppercase tracking-widest flex items-center gap-1">
                                    <span class="material-symbols-outlined text-xs">schedule</span>
                                    {{ $media->duration }}
                                </span>
                                @endif

                                @if($media->recorded_at)
                                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider ml-auto">
                                    {{ $media->recorded_at->format('M Y') }}
                                </span>
                                @endif
                            </div>

                            <h3 class="text-xl md:text-2xl font-bold text-navy group-hover:text-primary transition-colors leading-tight">
                                {{ $media->title }}
                            </h3>

                            @if($media->description)
                            <p class="text-slate-600 text-sm leading-relaxed font-light">
                                {{ $media->description }}
                            </p>
                            @endif

                            <div class="pt-2">
                                @if($media->embed_url)
                                <a x-show="activeTab === 'video'" href="{{ $media->embed_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider px-4 py-2 rounded-xl transition-all duration-200 border bg-red-50 text-red-700 border-red-200/50 hover:bg-red-100 hover:border-red-300">
                                    <span class="material-symbols-outlined text-sm">open_in_new</span>
                                    Watch on {{ ucfirst($media->platform) }}
                                </a>
                                @endif
                                @if($media->audio_url)
                                <a x-show="activeTab === 'audio'" href="{{ $media->audio_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider px-4 py-2 rounded-xl transition-all duration-200 border bg-emerald-50 text-emerald-700 border-emerald-200/50 hover:bg-emerald-100 hover:border-emerald-300">
                                    <span class="material-symbols-outlined text-sm">open_in_new</span>
                                    Listen on Spotify
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($media->event)
                    <div class="px-6 py-4 md:px-8 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                        <div class="flex flex-col">
                            <span class="text-[9px] text-slate-400 uppercase font-bold tracking-wider">From the Event</span>
                            <span class="text-xs font-bold text-navy truncate max-w-[250px]">{{ $media->event->title }}</span>
                        </div>
                        <span class="text-[10px] font-bold text-primary bg-primary/10 px-2.5 py-1 rounded-full uppercase tracking-widest">
                            {{ $media->event->location }}
                        </span>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>y
    </section>
    @endif

    <!-- Past Events -->
    <section class="py-24 px-6 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="mb-16">
                <span class="text-primary font-bold tracking-widest uppercase text-sm">Archive</span>
                <h2 class="text-3xl md:text-4xl font-display font-bold text-navy mt-2">Past Engagements</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200">
                            <th class="py-4 text-xs font-bold text-navy uppercase tracking-widest">Date</th>
                            <th class="py-4 text-xs font-bold text-navy uppercase tracking-widest">Event</th>
                            <th class="py-4 text-xs font-bold text-navy uppercase tracking-widest">Location</th>
                            <th class="py-4 text-xs font-bold text-navy uppercase tracking-widest">Role</th>
                            <th class="py-4"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($pastEvents as $event)
                        <tr class="group hover:bg-slate-50 transition-colors">
                            <td class="py-6 text-sm text-slate-500">{{ $event->date_start->format('M Y') }}</td>
                            <td class="py-6 font-bold text-navy">{{ $event->title }}</td>
                            <td class="py-6 text-sm text-slate-600">{{ $event->location }}</td>
                            <td class="py-6 text-sm text-slate-600">{{ $event->role }}</td>
                            <td class="py-6 text-right">
                                @if($event->link_url)
                                <a href="{{ $event->link_url }}" class="text-primary hover:underline text-sm font-bold">{{ $event->link_label ?? 'Recap' }}</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

</x-app-layout>
