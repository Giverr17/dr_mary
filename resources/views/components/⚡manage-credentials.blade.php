<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\Credential;

new class extends Component
{
    public $credentials;
    public $isEditing = false;
    public $editingId = null;

    #[Validate('required|min:3')]
    public $title = '';

    #[Validate('required|min:3')]
    public $institution = '';

    #[Validate('required|min:5')]
    public $description = '';

    #[Validate('required|integer')]
    public $order = 0;

    public function mount()
    {
        $this->loadCredentials();
    }

    public function loadCredentials()
    {
        $this->credentials = Credential::orderBy('order')->get();
    }

    public function edit($id)
    {
        $credential = Credential::find($id);
        $this->editingId = $id;
        $this->title = $credential->title;
        $this->institution = $credential->institution;
        $this->description = $credential->description;
        $this->order = $credential->order;
        $this->isEditing = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'institution' => $this->institution,
            'description' => $this->description,
            'order' => $this->order,
        ];

        if ($this->editingId) {
            Credential::find($this->editingId)->update($data);
        } else {
            Credential::create($data);
        }

        $this->resetForm();
        $this->loadCredentials();
        $this->dispatch('notify', 'Credential saved successfully!');
    }

    public function delete($id)
    {
        Credential::find($id)->delete();
        $this->loadCredentials();
        $this->dispatch('notify', 'Credential deleted successfully!');
    }

    public function resetForm()
    {
        $this->reset(['title', 'institution', 'description', 'order', 'isEditing', 'editingId']);
    }
};
?>

<div>
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">
        <h2 class="text-2xl font-display font-bold text-navy">Manage Credentials</h2>
        <button wire:click="{{ $isEditing ? 'resetForm' : '$set(\'isEditing\', true)' }}" class="px-4 py-2 bg-navy text-primary font-bold rounded-lg hover:opacity-90 transition-all text-sm">
            {{ $isEditing ? 'Cancel' : 'Add New Credential' }}
        </button>
    </div>

    @if($isEditing)
    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm mb-12">
        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-navy uppercase tracking-widest">Degree / Certification Title</label>
                    <input wire:model="title" type="text" placeholder="e.g. Ph.D. in Strategic Management" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                    @error('title') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-navy uppercase tracking-widest">Institution / Issuing Body</label>
                    <input wire:model="institution" type="text" placeholder="e.g. University of Georgia" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                    @error('institution') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-xs font-bold text-navy uppercase tracking-widest">Description</label>
                <textarea wire:model="description" rows="3" placeholder="Briefly describe what this credential entails..." class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none resize-none"></textarea>
                @error('description') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="space-y-2 w-32">
                <label class="text-xs font-bold text-navy uppercase tracking-widest">Display Order</label>
                <input wire:model="order" type="number" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                @error('order') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="px-8 py-3 bg-navy text-primary font-bold rounded-lg hover:opacity-90 transition-all">
                    {{ $editingId ? 'Update Credential' : 'Create Credential' }}
                </button>
                <button type="button" wire:click="resetForm" class="px-8 py-3 bg-slate-100 text-slate-600 font-bold rounded-lg hover:bg-slate-200 transition-all">
                    Cancel
                </button>
            </div>
        </form>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @forelse($credentials as $credential)
        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm relative group flex flex-col justify-between hover:border-primary/30 transition-all duration-300">
            <div>
                <div class="flex justify-between items-start mb-4">
                    <span class="text-[10px] text-slate-400 uppercase font-bold tracking-widest bg-slate-100 px-2.5 py-1 rounded-full">Order: {{ $credential->order }}</span>
                    <div class="flex gap-1">
                        <button wire:click="edit({{ $credential->id }})" class="p-1 text-slate-400 hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-lg">edit</span>
                        </button>
                        <button wire:confirm="Are you sure you want to delete this credential?" wire:click="delete({{ $credential->id }})" class="p-1 text-slate-400 hover:text-red-500 transition-colors">
                            <span class="material-symbols-outlined text-lg">delete</span>
                        </button>
                    </div>
                </div>
                
                <h3 class="text-xl font-bold text-navy mb-2">{{ $credential->title }}</h3>
                <p class="text-primary font-bold text-sm mb-4">{{ $credential->institution }}</p>
                <p class="text-slate-600 text-sm leading-relaxed mb-6 font-light">
                    {{ $credential->description }}
                </p>
            </div>
        </div>
        @empty
        <div class="col-span-2 bg-white p-12 text-center rounded-2xl border border-slate-200 shadow-sm text-slate-400">
            <span class="material-symbols-outlined text-4xl mb-2 block font-light">school</span>
            No credentials added yet. Click "Add New Credential" to create one.
        </div>
        @endforelse
    </div>
</div>
