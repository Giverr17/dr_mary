<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\ConsultingService;

new class extends Component
{
    public $services;
    public $isEditing = false;
    public $editingId = null;

    #[Validate('required')]
    public $title = '';
    public $icon = 'strategy';
    #[Validate('required')]
    public $description = '';
    public $bullet_points = [];
    public $newPoint = '';
    public $is_popular = false;
    public $order = 0;

    public function mount()
    {
        $this->loadServices();
    }

    public function loadServices()
    {
        $this->services = ConsultingService::orderBy('order')->get();
    }

    public function addPoint()
    {
        if ($this->newPoint) {
            $this->bullet_points[] = $this->newPoint;
            $this->newPoint = '';
        }
    }

    public function removePoint($index)
    {
        unset($this->bullet_points[$index]);
        $this->bullet_points = array_values($this->bullet_points);
    }

    public function edit($id)
    {
        $service = ConsultingService::find($id);
        $this->editingId = $id;
        $this->title = $service->title;
        $this->icon = $service->icon;
        $this->description = $service->description;
        $this->bullet_points = $service->bullet_points ?? [];
        $this->is_popular = $service->is_popular;
        $this->order = $service->order;
        $this->isEditing = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'icon' => $this->icon,
            'description' => $this->description,
            'bullet_points' => $this->bullet_points,
            'is_popular' => $this->is_popular,
            'order' => $this->order,
        ];

        if ($this->editingId) {
            ConsultingService::find($this->editingId)->update($data);
        } else {
            ConsultingService::create($data);
        }

        $this->resetForm();
        $this->loadServices();
        $this->dispatch('notify', 'Service saved successfully!');
    }

    public function delete($id)
    {
        ConsultingService::find($id)->delete();
        $this->loadServices();
    }

    public function resetForm()
    {
        $this->reset(['title', 'icon', 'description', 'bullet_points', 'newPoint', 'is_popular', 'order', 'isEditing', 'editingId']);
        $this->icon = 'strategy';
    }
};
?>

<div>
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-2xl font-display font-bold text-navy">Consulting Services</h2>
        <button wire:click="{{ $isEditing ? 'resetForm' : '$set(\'isEditing\', true)' }}" class="px-4 py-2 bg-navy text-primary font-bold rounded-lg hover:opacity-90 transition-all text-sm">
            {{ $isEditing ? 'Cancel' : 'Add New Service' }}
        </button>
    </div>

    @if($isEditing)
    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm mb-12">
        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-navy uppercase tracking-widest">Service Title</label>
                    <input wire:model="title" type="text" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-navy uppercase tracking-widest">Icon Name (Material Symbol)</label>
                    <input wire:model="icon" type="text" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-xs font-bold text-navy uppercase tracking-widest">Description</label>
                <textarea wire:model="description" rows="3" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none resize-none"></textarea>
            </div>

            <div class="space-y-4">
                <label class="text-xs font-bold text-navy uppercase tracking-widest block">Service Highlights (Bullet Points)</label>
                <div class="space-y-2">
                    @foreach($bullet_points as $index => $point)
                    <div class="flex items-center gap-2">
                        <input type="text" value="{{ $point }}" readonly class="flex-1 px-4 py-2 rounded-lg border border-slate-100 bg-slate-50 text-sm outline-none" />
                        <button type="button" wire:click="removePoint({{ $index }})" class="p-2 text-slate-400 hover:text-red-500"><span class="material-symbols-outlined">delete</span></button>
                    </div>
                    @endforeach
                    <div class="flex gap-2">
                        <input wire:model="newPoint" wire:keydown.enter.prevent="addPoint" type="text" placeholder="Add a highlight..." class="flex-1 px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                        <button type="button" wire:click="addPoint" class="px-4 py-2 bg-slate-100 text-navy font-bold rounded-lg hover:bg-slate-200 transition-all text-xs uppercase">Add</button>
                    </div>
                </div>
            </div>

            <div class="flex gap-8">
                <div class="flex items-center gap-2">
                    <input wire:model="is_popular" type="checkbox" id="is_popular_service" class="w-4 h-4 text-primary border-slate-300 rounded focus:ring-primary" />
                    <label for="is_popular_service" class="text-xs font-bold text-navy uppercase tracking-widest cursor-pointer">Popular Service</label>
                </div>
                <div class="space-x-2">
                    <label class="text-xs font-bold text-navy uppercase tracking-widest">Order</label>
                    <input wire:model="order" type="number" class="w-20 px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="px-8 py-3 bg-navy text-primary font-bold rounded-lg hover:opacity-90 transition-all">
                    {{ $editingId ? 'Update Service' : 'Create Service' }}
                </button>
                <button type="button" wire:click="resetForm" class="px-8 py-3 bg-slate-100 text-slate-600 font-bold rounded-lg hover:bg-slate-200 transition-all">
                    Cancel
                </button>
            </div>
        </form>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($services as $service)
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative group">
            <div class="flex justify-between items-start mb-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                        <span class="material-symbols-outlined">{{ $service->icon }}</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-navy">{{ $service->title }}</h3>
                        @if($service->is_popular) <span class="text-[10px] bg-primary text-navy px-2 py-0.5 rounded-full uppercase font-bold">Popular</span> @endif
                    </div>
                </div>
                <div class="flex gap-2">
                    <button wire:click="edit({{ $service->id }})" class="p-2 text-slate-400 hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-lg">edit</span>
                    </button>
                    <button wire:confirm="Delete this service?" wire:click="delete({{ $service->id }})" class="p-2 text-slate-400 hover:text-red-500 transition-colors">
                        <span class="material-symbols-outlined text-lg">delete</span>
                    </button>
                </div>
            </div>
            <p class="text-sm text-slate-600 line-clamp-2 mb-4">{{ $service->description }}</p>
            <div class="flex flex-wrap gap-2">
                @foreach($service->bullet_points ?? [] as $point)
                <span class="text-[10px] px-2 py-1 bg-slate-50 text-slate-400 rounded border border-slate-100">{{ $point }}</span>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>