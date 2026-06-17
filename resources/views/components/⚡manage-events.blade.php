<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;

new class extends Component
{
    use WithFileUploads;

    public $events;
    public $isEditing = false;
    public $editingId = null;

    #[Validate('required')]
    public $title = '';
    #[Validate('required|date')]
    public $date_start = '';
    public $date_end = '';
    #[Validate('required')]
    public $location = '';
    public $time = '';
    public $description = '';
    public $image;
    public $image_path;
    public $role = '';
    public $is_virtual = false;
    public $is_featured = false;
    public $registration_url = '';
    public $link_url = '';
    public $link_label = '';
    public $attendee_count = '';
    public $status = 'upcoming';
    public $order = 0;

    public function mount()
    {
        $this->date_start = date('Y-m-d');
        $this->loadEvents();
    }

    public function loadEvents()
    {
        $this->events = Event::orderBy('date_start', 'desc')->get();
    }

    public function edit($id)
    {
        $event = Event::find($id);
        $this->editingId = $id;
        $this->title = $event->title;
        $this->date_start = $event->date_start->format('Y-m-d');
        $this->date_end = $event->date_end ? $event->date_end->format('Y-m-d') : '';
        $this->location = $event->location;
        $this->time = $event->time;
        $this->description = $event->description;
        $this->image_path = $event->image_path;
        $this->role = $event->role;
        $this->is_virtual = $event->is_virtual;
        $this->is_featured = $event->is_featured;
        $this->registration_url = $event->registration_url;
        $this->link_url = $event->link_url;
        $this->link_label = $event->link_label;
        $this->attendee_count = $event->attendee_count;
        $this->status = $event->status;
        $this->order = $event->order;
        $this->isEditing = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'date_start' => $this->date_start,
            'date_end' => $this->date_end ?: null,
            'location' => $this->location,
            'time' => $this->time,
            'description' => $this->description,
            'role' => $this->role,
            'is_virtual' => $this->is_virtual,
            'is_featured' => $this->is_featured,
            'registration_url' => $this->registration_url,
            'link_url' => $this->link_url,
            'link_label' => $this->link_label,
            'attendee_count' => $this->attendee_count,
            'status' => $this->status,
            'order' => $this->order,
        ];

        if ($this->image) {
            $data['image_path'] = $this->image->store('events', 'public');
        }

        if ($this->editingId) {
            $event = Event::find($this->editingId);
            if ($this->image && $event->image_path) {
                Storage::disk('public')->delete($event->image_path);
            }
            $event->update($data);
        } else {
            Event::create($data);
        }

        $this->resetForm();
        $this->loadEvents();
        $this->dispatch('notify', 'Event saved successfully!');
    }

    public function delete($id)
    {
        $event = Event::find($id);
        if ($event->image_path) {
            Storage::disk('public')->delete($event->image_path);
        }
        $event->delete();
        $this->loadEvents();
    }

    public function resetForm()
    {
        $this->reset(['title', 'date_start', 'date_end', 'location', 'time', 'description', 'image', 'image_path', 'role', 'is_virtual', 'is_featured', 'registration_url', 'link_url', 'link_label', 'attendee_count', 'status', 'order', 'isEditing', 'editingId']);
        $this->date_start = date('Y-m-d');
        $this->status = 'upcoming';
    }
};
?>

<div>
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-2xl font-display font-bold text-navy">Manage Events</h2>
        <button wire:click="{{ $isEditing ? 'resetForm' : '$set(\'isEditing\', true)' }}" class="px-4 py-2 bg-navy text-primary font-bold rounded-lg hover:opacity-90 transition-all text-sm">
            {{ $isEditing ? 'Cancel' : 'Add New Event' }}
        </button>
    </div>

    @if($isEditing)
    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm mb-12">
        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-navy uppercase tracking-widest">Event Title</label>
                    <input wire:model="title" type="text" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-navy uppercase tracking-widest">Location</label>
                    <input wire:model="location" type="text" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-navy uppercase tracking-widest">Start Date</label>
                    <input wire:model="date_start" type="date" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-navy uppercase tracking-widest">End Date (Optional)</label>
                    <input wire:model="date_end" type="date" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-navy uppercase tracking-widest">Status</label>
                    <select wire:model="status" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none">
                        <option value="upcoming">Upcoming</option>
                        <option value="past">Past</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-8">
                <div class="flex items-center gap-2">
                    <input wire:model="is_featured" type="checkbox" id="is_featured_event" class="w-4 h-4 text-primary border-slate-300 rounded focus:ring-primary" />
                    <label for="is_featured_event" class="text-xs font-bold text-navy uppercase tracking-widest cursor-pointer">Featured</label>
                </div>
                <div class="flex items-center gap-2">
                    <input wire:model="is_virtual" type="checkbox" id="is_virtual" class="w-4 h-4 text-primary border-slate-300 rounded focus:ring-primary" />
                    <label for="is_virtual" class="text-xs font-bold text-navy uppercase tracking-widest cursor-pointer">Virtual Event</label>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-xs font-bold text-navy uppercase tracking-widest">Short Description</label>
                <textarea wire:model="description" rows="3" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none resize-none"></textarea>
            </div>

            <div class="space-y-2">
                <label class="text-xs font-bold text-navy uppercase tracking-widest">Event Header Image</label>
                <input type="file" wire:model="image" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-all" />
                @if($image)
                    <div class="mt-2 relative w-full h-40 rounded-lg overflow-hidden">
                        <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover">
                    </div>
                @elseif($image_path)
                    <div class="mt-2 relative w-full h-40 rounded-lg overflow-hidden">
                        <img src="{{ Storage::url($image_path) }}" class="w-full h-full object-cover">
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-navy uppercase tracking-widest">Your Role</label>
                    <input wire:model="role" type="text" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" placeholder="e.g. Keynote Speaker" />
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-navy uppercase tracking-widest">Attendance / Attendee Count</label>
                    <input wire:model="attendee_count" type="text" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" placeholder="e.g. 500+ attendees" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-navy uppercase tracking-widest">External Registration URL (Optional)</label>
                    <input wire:model="registration_url" type="url" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" placeholder="e.g. https://summit2025.com/register" />
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-navy uppercase tracking-widest">External Link URL (e.g. Recap or Post-event link)</label>
                    <input wire:model="link_url" type="url" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-navy uppercase tracking-widest">External Link Label (Optional)</label>
                    <input wire:model="link_label" type="text" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" placeholder="e.g. Watch Recording, Event Website" />
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="px-8 py-3 bg-navy text-primary font-bold rounded-lg hover:opacity-90 transition-all">
                    {{ $editingId ? 'Update Event' : 'Create Event' }}
                </button>
                <button type="button" wire:click="resetForm" class="px-8 py-3 bg-slate-100 text-slate-600 font-bold rounded-lg hover:bg-slate-200 transition-all">
                    Cancel
                </button>
            </div>
        </form>
    </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Event & Date</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Location</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</th>
                    <th class="px-6 py-4"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($events as $event)
                <tr class="{{ $event->status === 'past' ? 'opacity-60' : '' }}">
                    <td class="px-6 py-4">
                        <span class="block font-bold text-navy">{{ $event->title }}</span>
                        <span class="block text-xs text-slate-500">{{ $event->date_start->format('M d, Y') }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-slate-700">{{ $event->location }}</span>
                        @if($event->is_virtual) <span class="ml-2 text-[10px] bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full uppercase font-bold">Virtual</span> @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 {{ $event->status === 'upcoming' ? 'bg-green-100 text-green-600' : 'bg-slate-100 text-slate-600' }} text-[10px] font-bold rounded-full uppercase tracking-widest">{{ $event->status }}</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.events.registrations.pdf', $event->id) }}" class="p-2 text-slate-400 hover:text-primary transition-colors" title="Download Registrations">
                                <span class="material-symbols-outlined text-lg">download</span>
                            </a>
                            <button wire:click="edit({{ $event->id }})" class="p-2 text-slate-400 hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-lg">edit</span>
                            </button>
                            <button wire:confirm="Delete this event?" wire:click="delete({{ $event->id }})" class="p-2 text-slate-400 hover:text-red-500 transition-colors">
                                <span class="material-symbols-outlined text-lg">delete</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>