<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use App\Models\Publication;
use Illuminate\Support\Facades\Storage;

new class extends Component
{
    use WithFileUploads;

    public $publications;
    public $isEditing = false;
    public $editingId = null;

    #[Validate('required|min:3')]
    public $title = '';
    
    #[Validate('required')]
    public $type = 'Research Paper';
    
    #[Validate('required|integer|min:1900|max:2100')]
    public $year = '';
    
    #[Validate('required|min:10')]
    public $abstract = '';
    
    public $pdf;
    public $pdf_path;
    public $is_featured = true;
    public $order = 0;

    public function mount()
    {
        $this->year = date('Y');
        $this->loadPublications();
    }

    public function loadPublications()
    {
        $this->publications = Publication::orderBy('order')->orderBy('year', 'desc')->get();
    }

    public function edit($id)
    {
        $pub = Publication::find($id);
        $this->editingId = $id;
        $this->title = $pub->title;
        $this->type = $pub->type;
        $this->year = $pub->year;
        $this->abstract = $pub->abstract;
        $this->pdf_path = $pub->pdf_path;
        $this->is_featured = $pub->is_featured;
        $this->order = $pub->order;
        $this->isEditing = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'type' => $this->type,
            'year' => $this->year,
            'abstract' => $this->abstract,
            'is_featured' => $this->is_featured,
            'order' => $this->order,
        ];

        if ($this->pdf) {
            $data['pdf_path'] = $this->pdf->store('publications', 'public');
        }

        if ($this->editingId) {
            Publication::find($this->editingId)->update($data);
        } else {
            Publication::create($data);
        }

        $this->resetForm();
        $this->loadPublications();
        $this->dispatch('notify', 'Publication saved successfully!');
    }

    public function delete($id)
    {
        $pub = Publication::find($id);
        if ($pub->pdf_path) {
            Storage::disk('public')->delete($pub->pdf_path);
        }
        $pub->delete();
        $this->loadPublications();
    }

    public function resetForm()
    {
        $this->reset(['title', 'type', 'year', 'abstract', 'pdf', 'pdf_path', 'is_featured', 'order', 'isEditing', 'editingId']);
        $this->year = date('Y');
    }
};
?>

<div>
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-2xl font-display font-bold text-navy">Manage Publications</h2>
        <button wire:click="{{ $isEditing ? 'resetForm' : '$set(\'isEditing\', true)' }}" class="px-4 py-2 bg-navy text-primary font-bold rounded-lg hover:opacity-90 transition-all text-sm">
            {{ $isEditing ? 'Cancel' : 'Add New Publication' }}
        </button>
    </div>

    @if($isEditing)
    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm mb-12">
        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-navy uppercase tracking-widest">Title</label>
                    <input wire:model="title" type="text" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                    @error('title') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-navy uppercase tracking-widest">Type</label>
                    <select wire:model="type" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none">
                        <option value="Research Paper">Research Paper</option>
                        <option value="Policy Brief">Policy Brief</option>
                        <option value="Book Chapter">Book Chapter</option>
                        <option value="Working Paper">Working Paper</option>
                        <option value="Journal Article">Journal Article</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-navy uppercase tracking-widest">Year</label>
                    <input wire:model="year" type="number" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-navy uppercase tracking-widest">Display Order</label>
                    <input wire:model="order" type="number" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                </div>
                <div class="flex items-center gap-2 pt-6">
                    <input wire:model="is_featured" type="checkbox" id="is_featured" class="w-4 h-4 text-primary border-slate-300 rounded focus:ring-primary" />
                    <label for="is_featured" class="text-xs font-bold text-navy uppercase tracking-widest cursor-pointer">Featured</label>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-xs font-bold text-navy uppercase tracking-widest">Abstract / Summary</label>
                <textarea wire:model="abstract" rows="4" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none resize-none"></textarea>
                @error('abstract') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="space-y-2">
                <label class="text-xs font-bold text-navy uppercase tracking-widest">PDF Document</label>
                <input type="file" wire:model="pdf" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-all" />
                @if($pdf_path && !$pdf)
                    <p class="text-[10px] text-slate-400 mt-1">Current file: {{ basename($pdf_path) }}</p>
                @endif
                @error('pdf') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="px-8 py-3 bg-navy text-primary font-bold rounded-lg hover:opacity-90 transition-all">
                    {{ $editingId ? 'Update Publication' : 'Create Publication' }}
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
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Title & Year</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Type</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Featured</th>
                    <th class="px-6 py-4"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($publications as $pub)
                <tr>
                    <td class="px-6 py-4">
                        <span class="block font-bold text-navy">{{ $pub->title }}</span>
                        <span class="block text-xs text-slate-500">{{ $pub->year }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-full uppercase tracking-widest">{{ $pub->type }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($pub->is_featured)
                            <span class="material-symbols-outlined text-primary text-sm">star</span>
                        @else
                            <span class="text-slate-300 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button wire:click="edit({{ $pub->id }})" class="p-2 text-slate-400 hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-lg">edit</span>
                            </button>
                            <button wire:confirm="Are you sure you want to delete this publication?" wire:click="delete({{ $pub->id }})" class="p-2 text-slate-400 hover:text-red-500 transition-colors">
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