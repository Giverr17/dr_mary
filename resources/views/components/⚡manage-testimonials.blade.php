<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\Testimonial;

new class extends Component
{
    public $testimonials;
    public $isEditing = false;
    public $editingId = null;

    #[Validate('required|min:5')]
    public $quote = '';
    
    #[Validate('required|min:2')]
    public $author_name = '';
    
    #[Validate('required|min:2')]
    public $author_title = '';
    
    #[Validate('required|integer')]
    public $order = 0;

    public function mount()
    {
        $this->loadTestimonials();
    }

    public function loadTestimonials()
    {
        $this->testimonials = Testimonial::orderBy('order')->get();
    }

    public function edit($id)
    {
        $testimonial = Testimonial::find($id);
        $this->editingId = $id;
        $this->quote = $testimonial->quote;
        $this->author_name = $testimonial->author_name;
        $this->author_title = $testimonial->author_title;
        $this->order = $testimonial->order;
        $this->isEditing = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'quote' => $this->quote,
            'author_name' => $this->author_name,
            'author_title' => $this->author_title,
            'order' => $this->order,
        ];

        if ($this->editingId) {
            Testimonial::find($this->editingId)->update($data);
        } else {
            Testimonial::create($data);
        }

        $this->resetForm();
        $this->loadTestimonials();
        $this->dispatch('notify', 'Testimonial saved successfully!');
    }

    public function delete($id)
    {
        Testimonial::find($id)->delete();
        $this->loadTestimonials();
        $this->dispatch('notify', 'Testimonial deleted successfully!');
    }

    public function resetForm()
    {
        $this->reset(['quote', 'author_name', 'author_title', 'order', 'isEditing', 'editingId']);
    }
};
?>

<div>
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">
        <h2 class="text-2xl font-display font-bold text-navy">Testimonials & Feedback</h2>
        <button wire:click="{{ $isEditing ? 'resetForm' : '$set(\'isEditing\', true)' }}" class="px-4 py-2 bg-navy text-primary font-bold rounded-lg hover:opacity-90 transition-all text-sm">
            {{ $isEditing ? 'Cancel' : 'Add New Testimonial' }}
        </button>
    </div>

    @if($isEditing)
    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm mb-12">
        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-navy uppercase tracking-widest">Author Name</label>
                    <input wire:model="author_name" type="text" placeholder="e.g. John Smith" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                    @error('author_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="space-y-2">
                    <label class="text-xs font-bold text-navy uppercase tracking-widest">Author Title / Organization</label>
                    <input wire:model="author_title" type="text" placeholder="e.g. CEO, Sustainability Inc." class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                    @error('author_title') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-xs font-bold text-navy uppercase tracking-widest">Feedback / Quote</label>
                <textarea wire:model="quote" rows="4" placeholder="Enter client's testimonial or feedback here..." class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none resize-none"></textarea>
                @error('quote') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="space-y-2 w-32">
                <label class="text-xs font-bold text-navy uppercase tracking-widest">Display Order</label>
                <input wire:model="order" type="number" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                @error('order') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="px-8 py-3 bg-navy text-primary font-bold rounded-lg hover:opacity-90 transition-all">
                    {{ $editingId ? 'Update Testimonial' : 'Create Testimonial' }}
                </button>
                <button type="button" wire:click="resetForm" class="px-8 py-3 bg-slate-100 text-slate-600 font-bold rounded-lg hover:bg-slate-200 transition-all">
                    Cancel
                </button>
            </div>
        </form>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @forelse($testimonials as $testimonial)
        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm relative group flex flex-col justify-between">
            <span class="material-symbols-outlined text-primary/10 text-6xl absolute top-6 right-8 pointer-events-none">format_quote</span>
            
            <div class="relative z-10 flex-1">
                <div class="flex justify-between items-start mb-6">
                    <span class="text-[10px] text-slate-400 uppercase font-bold tracking-widest bg-slate-100 px-2.5 py-1 rounded-full">Order: {{ $testimonial->order }}</span>
                    <div class="flex gap-1">
                        <button wire:click="edit({{ $testimonial->id }})" class="p-1 text-slate-400 hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-lg">edit</span>
                        </button>
                        <button wire:confirm="Are you sure you want to delete this testimonial?" wire:click="delete({{ $testimonial->id }})" class="p-1 text-slate-400 hover:text-red-500 transition-colors">
                            <span class="material-symbols-outlined text-lg">delete</span>
                        </button>
                    </div>
                </div>
                
                <p class="text-slate-700 italic leading-relaxed mb-6">
                    "{{ $testimonial->quote }}"
                </p>
            </div>
            
            <div class="border-t border-slate-100 pt-4 mt-4">
                <h4 class="font-bold text-navy text-sm">{{ $testimonial->author_name }}</h4>
                <p class="text-xs text-primary font-semibold mt-0.5">{{ $testimonial->author_title }}</p>
            </div>
        </div>
        @empty
        <div class="col-span-2 bg-white p-12 text-center rounded-3xl border border-slate-200 shadow-sm text-slate-400">
            <span class="material-symbols-outlined text-4xl mb-2 block">format_quote</span>
            No testimonials added yet. Click "Add New Testimonial" to create one.
        </div>
        @endforelse
    </div>
</div>
