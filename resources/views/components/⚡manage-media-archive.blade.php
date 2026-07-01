<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\MediaArchive;
use App\Models\Event;

new class extends Component
{
    public $archives;
    public $events = [];
    public $isEditing = false;
    public $editingId = null;

    public $title = '';
    public $description = '';
    public $media_type = 'video';
    public $embed_url = '';
    public $audio_url = '';
    public $thumbnail_url = '';
    public $event_id = null;
    public $duration = '';
    public $recorded_at = '';
    public $is_featured = false;
    public $order = 0;

    public function mount()
    {
        $this->recorded_at = date('Y-m-d');
        $this->loadData();
    }

    public function loadData()
    {
        $this->archives = MediaArchive::orderBy('is_featured', 'desc')
            ->orderBy('order', 'asc')
            ->orderBy('recorded_at', 'desc')
            ->get();
        $this->events = Event::orderBy('date_start', 'desc')->get();
    }

    public function edit($id)
    {
        $item = MediaArchive::find($id);
        $this->editingId = $id;
        $this->title = $item->title;
        $this->description = $item->description;
        $this->media_type = $item->media_type->value;
        $this->embed_url = $item->embed_url ?: '';
        $this->audio_url = $item->audio_url ?: '';
        $this->thumbnail_url = $item->thumbnail_url ?: '';
        $this->event_id = $item->event_id;
        $this->duration = $item->duration ?: '';
        $this->recorded_at = $item->recorded_at ? $item->recorded_at->format('Y-m-d') : '';
        $this->is_featured = $item->is_featured;
        $this->order = $item->order;
        $this->isEditing = true;
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|min:3',
            'embed_url' => 'nullable|url',
            'audio_url' => 'nullable|url',
            'thumbnail_url' => 'nullable|url',
            'event_id' => 'nullable|exists:events,id',
            'duration' => 'nullable|string',
            'recorded_at' => 'nullable|date',
            'order' => 'integer',
        ]);

        if (empty($this->embed_url) && empty($this->audio_url)) {
            $this->addError('embed_url', 'You must provide at least a Video URL or an Audio URL.');
            $this->addError('audio_url', 'You must provide at least a Video URL or an Audio URL.');
            return;
        }

        // Set media_type depending on what is filled (fallback/legacy)
        $mediaType = 'video';
        if (empty($this->embed_url) && !empty($this->audio_url)) {
            $mediaType = 'audio';
        }

        $data = [
            'title' => $this->title,
            'description' => $this->description ?: null,
            'media_type' => $mediaType,
            'embed_url' => $this->embed_url ?: null,
            'audio_url' => $this->audio_url ?: null,
            'thumbnail_url' => $this->thumbnail_url ?: null,
            'event_id' => $this->event_id ?: null,
            'duration' => $this->duration ?: null,
            'recorded_at' => $this->recorded_at ?: null,
            'is_featured' => $this->is_featured,
            'order' => $this->order,
        ];

        if ($this->editingId) {
            MediaArchive::find($this->editingId)->update($data);
        } else {
            MediaArchive::create($data);
        }

        $this->resetForm();
        $this->loadData();
        $this->dispatch('notify', 'Media archive item saved successfully!');
    }

    public function delete($id)
    {
        MediaArchive::find($id)->delete();
        $this->loadData();
        $this->dispatch('notify', 'Media item removed successfully!');
    }

    public function resetForm()
    {
        $this->reset(['title', 'description', 'media_type', 'embed_url', 'audio_url', 'thumbnail_url', 'event_id', 'duration', 'recorded_at', 'is_featured', 'order', 'isEditing', 'editingId']);
        $this->recorded_at = date('Y-m-d');
        $this->media_type = 'video';
    }

    /**
     * Compute clean embed URL live for the preview.
     */
    public function getPreviewUrlProperty()
    {
        $url = $this->embed_url ?: $this->audio_url;
        if (!$url || filter_var($url, FILTER_VALIDATE_URL) === false) {
            return null;
        }

        $platform = MediaArchive::detectPlatform($url);

        if ($platform === 'youtube') {
            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $url, $match)) {
                return "https://www.youtube-nocookie.com/embed/" . $match[1];
            }
            if (preg_match('/youtube\.com\/shorts\/([^"&?\/ ]{11})/i', $url, $match)) {
                return "https://www.youtube-nocookie.com/embed/" . $match[1];
            }
        }

        if ($platform === 'spotify') {
            if (str_contains($url, 'open.spotify.com/')) {
                if (str_contains($url, 'open.spotify.com/embed/')) {
                    return $url;
                }
                return str_replace('open.spotify.com/', 'open.spotify.com/embed/', $url);
            }
        }

        if ($platform === 'vimeo') {
            if (preg_match('/vimeo\.com\/([0-9]+)/i', $url, $match)) {
                return "https://player.vimeo.com/video/" . $match[1];
            }
        }

        return null;
    }
};
?>

<div>
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">
        <h2 class="text-2xl font-display font-bold text-navy">Manage Event Media Archive</h2>
        <button wire:click="{{ $isEditing ? 'resetForm' : '$set(\'isEditing\', true)' }}" class="px-4 py-2 bg-navy text-primary font-bold rounded-lg hover:opacity-90 transition-all text-sm">
            {{ $isEditing ? 'Cancel' : 'Add New Media Replay' }}
        </button>
    </div>

    @if($isEditing)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        <!-- Form Section -->
        <div class="lg:col-span-2 bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
            <form wire:submit="save" class="space-y-6">
                <div class="grid grid-cols-1 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-navy uppercase tracking-widest">Media Title</label>
                        <input wire:model="title" type="text" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" placeholder="e.g. Healthcare Leadership Summit Speech" />
                        @error('title') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <label class="text-xs font-bold text-navy uppercase tracking-widest">Video Replay Link / URL (Optional)</label>
                            <span class="text-[10px] text-slate-400 font-bold uppercase">YouTube or Vimeo</span>
                        </div>
                        <input wire:model.live.debounce.300ms="embed_url" type="url" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" placeholder="e.g. https://www.youtube.com/watch?v=..." />
                        @error('embed_url') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <label class="text-xs font-bold text-navy uppercase tracking-widest">Audio Replay Link / URL (Optional)</label>
                            <span class="text-[10px] text-slate-400 font-bold uppercase">Spotify Episode URL</span>
                        </div>
                        <input wire:model.live.debounce.300ms="audio_url" type="url" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" placeholder="e.g. https://open.spotify.com/episode/..." />
                        @error('audio_url') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-navy uppercase tracking-widest">Associated Event (Optional)</label>
                        <select wire:model="event_id" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none text-slate-700">
                            <option value="">-- No linked event --</option>
                            @foreach($events as $event)
                                <option value="{{ $event->id }}">{{ $event->title }} ({{ $event->date_start->format('M Y') }})</option>
                            @endforeach
                        </select>
                        @error('event_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-navy uppercase tracking-widest">Duration (Optional)</label>
                        <input wire:model="duration" type="text" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" placeholder="e.g. 45 mins or 1h 20m" />
                        @error('duration') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-navy uppercase tracking-widest">Recorded On (Optional)</label>
                        <input wire:model="recorded_at" type="date" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                        @error('recorded_at') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-navy uppercase tracking-widest">Custom Thumbnail URL (Optional)</label>
                        <input wire:model="thumbnail_url" type="url" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" placeholder="https://example.com/cover-image.jpg" />
                        @error('thumbnail_url') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-navy uppercase tracking-widest">Display Order</label>
                            <input wire:model="order" type="number" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                        </div>
                        <div class="flex items-center gap-2 pt-6">
                            <input wire:model="is_featured" type="checkbox" id="is_featured_media" class="w-4 h-4 text-primary border-slate-300 rounded focus:ring-primary" />
                            <label for="is_featured_media" class="text-xs font-bold text-navy uppercase tracking-widest cursor-pointer">Featured</label>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-navy uppercase tracking-widest">Short Description / Subtitle</label>
                    <textarea wire:model="description" rows="3" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none resize-none" placeholder="Provide a brief context or highlights of this video or audio recording..."></textarea>
                    @error('description') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="px-8 py-3 bg-navy text-primary font-bold rounded-lg hover:opacity-90 transition-all">
                        {{ $editingId ? 'Update Replay' : 'Add Replay' }}
                    </button>
                    <button type="button" wire:click="resetForm" class="px-8 py-3 bg-slate-100 text-slate-600 font-bold rounded-lg hover:bg-slate-200 transition-all">
                        Cancel
                    </button>
                </div>
            </form>
        </div>

        <!-- Live Preview Section -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-navy rounded-2xl p-6 text-white border border-white/5 shadow-md sticky top-24">
                <h3 class="text-lg font-display font-bold text-primary mb-2 flex items-center gap-2">
                    <span class="material-symbols-outlined text-xl">visibility</span> Live Player Preview
                </h3>
                <p class="text-xs text-white/50 mb-6">This shows how the media widget will look in the public portal.</p>

                @if($this->preview_url)
                    <div class="rounded-xl overflow-hidden shadow-2xl border border-white/10 bg-black/40">
                        @if($embed_url)
                            <div class="aspect-video w-full bg-slate-900 flex items-center justify-center relative">
                                <iframe src="{{ $this->preview_url }}" class="absolute inset-0 w-full h-full" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                            </div>
                        @else
                            <div class="w-full bg-slate-900 flex items-center justify-center">
                                <iframe src="{{ $this->preview_url }}" width="100%" height="232" frameborder="0" allowtransparency="true" allow="encrypted-media" class="w-full"></iframe>
                            </div>
                        @endif
                    </div>
                    <div class="mt-4 flex items-center justify-between text-xs text-white/60 bg-white/5 px-3 py-2 rounded-lg">
                        <span class="flex items-center gap-1 font-semibold uppercase">
                            <span class="material-symbols-outlined text-xs text-green-400">check_circle</span> URL Validated
                        </span>
                        <span class="font-mono text-[10px] text-primary">{{ strtoupper(App\Models\MediaArchive::detectPlatform($embed_url ?: $audio_url)) }}</span>
                    </div>
                @else
                    <div class="border border-dashed border-white/20 rounded-xl p-8 text-center text-white/40 flex flex-col items-center justify-center gap-3">
                        <span class="material-symbols-outlined text-4xl text-white/20 animate-pulse">play_circle</span>
                        <p class="text-sm font-semibold">Enter a valid YouTube or Spotify link to see player preview here.</p>
                        <p class="text-[10px] text-white/30 max-w-[200px]">URLs must start with http:// or https://</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full min-w-[820px] text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Media Replay & Platform</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Type</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Linked Event</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Recorded On</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Featured</th>
                    <th class="px-6 py-4"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($archives as $item)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <span class="block font-bold text-navy">{{ $item->title }}</span>
                        <div class="flex flex-col gap-0.5 mt-1">
                            @if($item->embed_url)
                            <a href="{{ $item->embed_url }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] text-slate-500 font-mono hover:text-primary truncate max-w-xs">
                                <span class="material-symbols-outlined text-[12px] text-red-500">play_circle</span>
                                {{ $item->embed_url }}
                            </a>
                            @endif
                            @if($item->audio_url)
                            <a href="{{ $item->audio_url }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] text-slate-500 font-mono hover:text-primary truncate max-w-xs">
                                <span class="material-symbols-outlined text-[12px] text-green-500">headset</span>
                                {{ $item->audio_url }}
                            </a>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-1">
                            @if($item->embed_url && $item->audio_url)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-200/50 text-[10px] font-bold uppercase tracking-wider w-fit">Video & Audio</span>
                            @elseif($item->embed_url)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-red-50 text-red-700 border border-red-200/50 text-[10px] font-bold uppercase tracking-wider w-fit">Video</span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-green-50 text-green-700 border border-green-200/50 text-[10px] font-bold uppercase tracking-wider w-fit">Audio</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600 font-medium">
                        @if($item->event)
                            <span class="block text-navy font-semibold truncate max-w-[150px]" title="{{ $item->event->title }}">
                                {{ $item->event->title }}
                            </span>
                            <span class="block text-[10px] text-slate-400">{{ $item->event->date_start->format('M Y') }}</span>
                        @else
                            <span class="text-slate-300">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-xs text-slate-500 font-medium">
                        {{ $item->recorded_at ? $item->recorded_at->format('M d, Y') : '—' }}
                        @if($item->duration)
                            <span class="block text-[10px] text-slate-400 font-semibold">{{ $item->duration }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($item->is_featured)
                            <span class="material-symbols-outlined text-primary text-xl" style="font-variation-settings: 'FILL' 1">star</span>
                        @else
                            <span class="text-slate-300 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button wire:click="edit({{ $item->id }})" class="p-2 text-slate-400 hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-lg">edit</span>
                            </button>
                            <button wire:confirm="Are you sure you want to remove this media replay?" wire:click="delete({{ $item->id }})" class="p-2 text-slate-400 hover:text-red-500 transition-colors">
                                <span class="material-symbols-outlined text-lg">delete</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-slate-400">
                        <span class="material-symbols-outlined text-3xl mb-2 block">smart_display</span>
                        No media replays archived yet. Click "Add New Media Replay" to get started.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
