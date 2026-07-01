<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Enums\AchievementCategory;
use App\Models\Achievement;
use Illuminate\Validation\Rule;

new class extends Component {
    public $achievements;
    public $isEditing = false;
    public $editingId = null;

    public $aiRawText = '';
    public $aiWarnings = [];
    public $aiMessage = '';

    #[Validate('required|min:3')]
    public $title = '';

    #[Validate('required|min:5')]
    public $description = '';

    #[Validate('required|integer|min:1900|max:2100')]
    public $year = '';

    public $category = AchievementCategory::Award->value;

    public $issuing_body = '';
    public $link_url = '';
    public $link_label = '';
    public $is_featured = false;
    public $order = 0;

    public function rules()
    {
        return [
            'category' => ['required', Rule::enum(AchievementCategory::class)],
        ];
    }

    public function mount()
    {
        $this->year = date('Y');
        $this->loadAchievements();
    }

    public function loadAchievements()
    {
        $this->achievements = Achievement::ordered()->get();
    }

    public function edit($id)
    {
        $item = Achievement::find($id);
        $this->editingId = $id;
        $this->title = $item->title;
        $this->description = $item->description;
        $this->year = $item->year;
        $this->category = $item->category->value;
        $this->issuing_body = $item->issuing_body;
        $this->link_url = $item->link_url;
        $this->link_label = $item->link_label;
        $this->is_featured = $item->is_featured;
        $this->order = $item->order;
        $this->isEditing = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'year' => $this->year,
            'category' => $this->category,
            'issuing_body' => $this->issuing_body ?: null,
            'link_url' => $this->link_url ?: null,
            'link_label' => $this->link_label ?: null,
            'is_featured' => $this->is_featured,
            'order' => $this->order,
        ];

        if ($this->editingId) {
            Achievement::find($this->editingId)->update($data);
        } else {
            Achievement::create($data);
        }

        $this->resetForm();
        $this->loadAchievements();
        $this->dispatch('notify', 'Achievement saved successfully!');
    }

    public function delete($id)
    {
        Achievement::find($id)->delete();
        $this->loadAchievements();
        $this->dispatch('notify', 'Achievement deleted successfully!');
    }

    public function aiStructure(\App\Services\AiService $ai)
    {
        $this->aiWarnings = [];
        $this->aiMessage = '';

        if (strlen(trim($this->aiRawText)) < 15) {
            $this->aiMessage = 'Please paste an achievement description first.';
            return;
        }

        $result = $ai->structureAchievement($this->aiRawText);

        if (!$result['success']) {
            $this->aiMessage = $result['message'];
            return;
        }

        if ($result['title'])
            $this->title = $result['title'];
        if ($result['description'])
            $this->description = $result['description'];
        if ($result['year'])
            $this->year = $result['year'];
        if ($result['category'])
            $this->category = $result['category'];
        if ($result['issuing_body'])
            $this->issuing_body = $result['issuing_body'];

        $this->aiWarnings = $result['warnings'];
        $this->aiMessage = $result['message'];
    }

    public function resetForm()
    {
        $this->reset(['title', 'description', 'year', 'category', 'issuing_body', 'link_url', 'link_label', 'is_featured', 'order', 'isEditing', 'editingId', 'aiRawText', 'aiWarnings', 'aiMessage']);
        $this->year = date('Y');
        $this->category = AchievementCategory::Award->value;
    }
};
?>

<div>
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-2xl font-display font-bold text-navy">Manage Achievements & Awards</h2>
        <button wire:click="{{ $isEditing ? 'resetForm' : '$set(\'isEditing\', true)' }}"
            class="px-4 py-2 bg-navy text-primary font-bold rounded-lg hover:opacity-90 transition-all text-sm">
            {{ $isEditing ? 'Cancel' : 'Add New Achievement' }}
        </button>
    </div>

    @if($isEditing)
        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm mb-12"
            x-data="{ hasLink: @entangle('link_url') }">
            <form wire:submit="save" class="space-y-6">
                <div class="bg-primary/5 border border-primary/20 rounded-xl p-5 space-y-3">
                    <label class="text-xs font-bold text-navy uppercase tracking-widest flex items-center gap-2">
                        <span>✨</span> AI Assist — paste an award announcement or description
                    </label>
                    <textarea wire:model="aiRawText" rows="3"
                        placeholder="Paste a news release, citation, or rough notes about the award and let AI structure it…"
                        class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none resize-none text-sm"></textarea>

                    <button type="button" wire:click="aiStructure" wire:loading.attr="disabled"
                        class="px-5 py-2 bg-primary text-navy font-bold rounded-lg hover:opacity-90 transition-all text-sm disabled:opacity-50 flex items-center gap-2">
                        <span wire:loading.remove wire:target="aiStructure">✨ Structure with AI</span>
                        <span wire:loading wire:target="aiStructure">Thinking…</span>
                    </button>

                    @if($aiMessage)
                        <p class="text-xs text-slate-600">{{ $aiMessage }}</p>
                    @endif

                    @if(count($aiWarnings))
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 space-y-1">
                            <p class="text-[10px] font-bold text-amber-700 uppercase tracking-widest">Please review</p>
                            @foreach($aiWarnings as $warning)
                                <p class="text-xs text-amber-700">• {{ $warning }}</p>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-navy uppercase tracking-widest">Achievement/Award Title</label>
                        <input wire:model="title" type="text"
                            class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none"
                            placeholder="e.g. Outstanding Medical Contribution Award" />
                        @error('title') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-navy uppercase tracking-widest">Issuing Body (Optional)</label>
                        <input wire:model="issuing_body" type="text"
                            class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none"
                            placeholder="e.g. World Health Organization" />
                        @error('issuing_body') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-navy uppercase tracking-widest">Category</label>
                        <select wire:model="category"
                            class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none">
                            @foreach(\App\Enums\AchievementCategory::cases() as $case)
                                <option value="{{ $case->value }}">{{ $case->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-navy uppercase tracking-widest">Year</label>
                        <input wire:model="year" type="number"
                            class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                        @error('year') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-navy uppercase tracking-widest">Display Order</label>
                        <input wire:model="order" type="number"
                            class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                    </div>
                    <div class="flex items-center gap-2 pt-6">
                        <input wire:model="is_featured" type="checkbox" id="is_featured"
                            class="w-4 h-4 text-primary border-slate-300 rounded focus:ring-primary" />
                        <label for="is_featured"
                            class="text-xs font-bold text-navy uppercase tracking-widest cursor-pointer">Featured (Pin to
                            Top)</label>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-navy uppercase tracking-widest">Description / Written
                        Details</label>
                    <textarea wire:model="description" rows="4"
                        class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none resize-none"
                        placeholder="Provide background information, the significance, and written details about this award..."></textarea>
                    @error('description') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="border-t border-slate-100 pt-6">
                    <h4 class="text-sm font-bold text-navy mb-4">External Redirection / Reference Link (Optional)</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-navy uppercase tracking-widest">Redirect URL</label>
                            <input wire:model="link_url" type="url"
                                class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none"
                                placeholder="https://example.com/certificate-or-news-source" />
                            @error('link_url') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-navy uppercase tracking-widest">Link Label (What readers
                                click)</label>
                            <input wire:model="link_label" type="text"
                                class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none"
                                placeholder="e.g. Read News Release or View Credential" />
                            @error('link_label') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button type="submit"
                        class="px-8 py-3 bg-navy text-primary font-bold rounded-lg hover:opacity-90 transition-all">
                        {{ $editingId ? 'Update Achievement' : 'Create Achievement' }}
                    </button>
                    <button type="button" wire:click="resetForm"
                        class="px-8 py-3 bg-slate-100 text-slate-600 font-bold rounded-lg hover:bg-slate-200 transition-all">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full min-w-[820px] text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Title & Issuing
                        Body</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Category</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Year</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Featured</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Has Link</th>
                    <th class="px-6 py-4"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($achievements as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="block font-bold text-navy">{{ $item->title }}</span>
                            @if($item->issuing_body)
                                <span class="block text-xs text-slate-500 font-medium">{{ $item->issuing_body }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-[10px] font-bold rounded-full uppercase tracking-widest
                                        @if($item->category === \App\Enums\AchievementCategory::Award) bg-amber-50 text-amber-700 border border-amber-200/50
                                        @elseif($item->category === \App\Enums\AchievementCategory::Recognition) bg-blue-50 text-blue-700 border border-blue-200/50
                                        @elseif($item->category === \App\Enums\AchievementCategory::Fellowship) bg-purple-50 text-purple-700 border border-purple-200/50
                                        @elseif($item->category === \App\Enums\AchievementCategory::Certification) bg-green-50 text-green-700 border border-green-200/50
                                        @else bg-slate-50 text-slate-700 border border-slate-200/50 @endif">
                                {{ $item->category->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-700 text-sm">
                            {{ $item->year }}
                        </td>
                        <td class="px-6 py-4">
                            @if($item->is_featured)
                                <span class="material-symbols-outlined text-primary text-xl"
                                    style="font-variation-settings: 'FILL' 1">star</span>
                            @else
                                <span class="text-slate-300 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($item->link_url)
                                <a href="{{ $item->link_url }}" target="_blank"
                                    class="inline-flex items-center gap-1 text-xs text-primary hover:underline font-semibold">
                                    <span class="material-symbols-outlined text-sm">link</span>
                                    {{ $item->link_label ?: 'Visit Link' }}
                                </a>
                            @else
                                <span class="text-slate-300 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button wire:click="edit({{ $item->id }})"
                                    class="p-2 text-slate-400 hover:text-primary transition-colors">
                                    <span class="material-symbols-outlined text-lg">edit</span>
                                </button>
                                <button wire:confirm="Are you sure you want to delete this achievement?"
                                    wire:click="delete({{ $item->id }})"
                                    class="p-2 text-slate-400 hover:text-red-500 transition-colors">
                                    <span class="material-symbols-outlined text-lg">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-400">
                            <span class="material-symbols-outlined text-3xl mb-2 block">workspace_premium</span>
                            No achievements added yet. Click "Add New Achievement" to get started.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>