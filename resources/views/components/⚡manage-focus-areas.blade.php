<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\ResearchFocusArea;

new class extends Component
{
    public $areas;
    public $isEditing = false;
    public $editingId = null;

    #[Validate('required')]
    public $title = '';
    #[Validate('required')]
    public $icon = 'analytics';
    #[Validate('required')]
    public $description = '';
    public $order = 0;

    public function mount()
    {
        $this->loadAreas();
    }

    public function loadAreas()
    {
        $this->areas = ResearchFocusArea::orderBy('order')->get();
    }

    public function edit($id)
    {
        $area = ResearchFocusArea::find($id);
        $this->editingId = $id;
        $this->title = $area->title;
        $this->icon = $area->icon;
        $this->description = $area->description;
        $this->order = $area->order;
        $this->isEditing = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'icon' => $this->icon,
            'description' => $this->description,
            'order' => $this->order,
        ];

        if ($this->editingId) {
            ResearchFocusArea::find($this->editingId)->update($data);
        } else {
            ResearchFocusArea::create($data);
        }

        $this->resetForm();
        $this->loadAreas();
        $this->dispatch('notify', 'Focus area saved successfully!');
    }

    public function delete($id)
    {
        ResearchFocusArea::find($id)->delete();
        $this->loadAreas();
    }

    public function resetForm()
    {
        $this->reset(['title', 'icon', 'description', 'order', 'isEditing', 'editingId']);
    }
};
?>

<div>
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-2xl font-display font-bold text-navy">Research Focus Areas</h2>
        <button wire:click="{{ $isEditing ? 'resetForm' : '$set(\'isEditing\', true)' }}" class="px-4 py-2 bg-navy text-primary font-bold rounded-lg hover:opacity-90 transition-all text-sm">
            {{ $isEditing ? 'Cancel' : 'Add New Area' }}
        </button>
    </div>

    @if($isEditing)
    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm mb-12">
        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-navy uppercase tracking-widest">Title</label>
                    <input wire:model="title" type="text" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-navy uppercase tracking-widest">Icon (Material Symbol)</label>
                    <input wire:model="icon" type="text" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-xs font-bold text-navy uppercase tracking-widest">Description</label>
                <textarea wire:model="description" rows="3" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none resize-none"></textarea>
            </div>

            <div class="space-y-2 w-32">
                <label class="text-xs font-bold text-navy uppercase tracking-widest">Display Order</label>
                <input wire:model="order" type="number" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
            </div>

            <div class="flex gap-4">
                <button type="submit" class="px-8 py-3 bg-navy text-primary font-bold rounded-lg hover:opacity-90 transition-all">
                    {{ $editingId ? 'Update Area' : 'Create Area' }}
                </button>
                <button type="button" wire:click="resetForm" class="px-8 py-3 bg-slate-100 text-slate-600 font-bold rounded-lg hover:bg-slate-200 transition-all">
                    Cancel
                </button>
            </div>
        </form>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($areas as $area)
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative group">
            <div class="flex justify-between items-start mb-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                        <span class="material-symbols-outlined">{{ $area->icon }}</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-navy">{{ $area->title }}</h3>
                        <span class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">Order: {{ $area->order }}</span>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button wire:click="edit({{ $area->id }})" class="p-2 text-slate-400 hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-lg">edit</span>
                    </button>
                    <button wire:confirm="Delete this area?" wire:click="delete({{ $area->id }})" class="p-2 text-slate-400 hover:text-red-500 transition-colors">
                        <span class="material-symbols-outlined text-lg">delete</span>
                    </button>
                </div>
            </div>
            <p class="text-sm text-slate-600 leading-relaxed">{{ $area->description }}</p>
        </div>
        @endforeach
    </div>
</div>