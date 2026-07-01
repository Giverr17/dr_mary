<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;

new class extends Component
{
    use WithFileUploads;

    public $profile;

    #[Validate('required')]
    public $full_name;
    #[Validate('required')]
    public $title_line;
    #[Validate('required')]
    public $hero_tagline;
    #[Validate('required')]
    public $bio_paragraph_1;
    #[Validate('required')]
    public $bio_paragraph_2;
    #[Validate('required')]
    public $bio_paragraph_3;
    
    public $expertise_tags = [];
    public $newTag = '';

    public $stat_years;
    public $stat_focus;
    public $stat_approach;

    public $email;
    public $location;
    public $response_time;
    public $website_url;
    public $scholar_url;
    public $linkedin_url;
    public $social_links = [];
    public $newSocialPlatform = '';
    public $newSocialUrl = '';

    public $photo;
    public $photo_path;
    public $speaker_kit;
    public $speaker_kit_path;
    
    public $footer_tagline;

    public function mount()
    {
        $this->profile = Profile::first() ?? new Profile();
        $this->full_name = $this->profile->full_name;
        $this->title_line = $this->profile->title_line;
        $this->hero_tagline = $this->profile->hero_tagline;
        $this->bio_paragraph_1 = $this->profile->bio_paragraph_1;
        $this->bio_paragraph_2 = $this->profile->bio_paragraph_2;
        $this->bio_paragraph_3 = $this->profile->bio_paragraph_3;
        $this->expertise_tags = $this->profile->expertise_tags ?? [];
        $this->stat_years = $this->profile->stat_years;
        $this->stat_focus = $this->profile->stat_focus;
        $this->stat_approach = $this->profile->stat_approach;
        $this->email = $this->profile->email;
        $this->location = $this->profile->location;
        $this->response_time = $this->profile->response_time;
        $this->website_url = $this->profile->website_url;
        $this->scholar_url = $this->profile->scholar_url;
        $this->linkedin_url = $this->profile->linkedin_url;
        $this->social_links = $this->profile->social_links ?? [];
        $this->photo_path = $this->profile->photo_path;
        $this->speaker_kit_path = $this->profile->speaker_kit_path;
        $this->footer_tagline = $this->profile->footer_tagline;
    }

    public function addTag()
    {
        if ($this->newTag && !in_array($this->newTag, $this->expertise_tags)) {
            $this->expertise_tags[] = $this->newTag;
            $this->newTag = '';
        }
    }

    public function removeTag($index)
    {
        unset($this->expertise_tags[$index]);
        $this->expertise_tags = array_values($this->expertise_tags);
    }

    public function addSocialLink()
    {
        if ($this->newSocialPlatform && $this->newSocialUrl) {
            $this->social_links[] = [
                'platform' => $this->newSocialPlatform,
                'url' => $this->newSocialUrl
            ];
            $this->newSocialPlatform = '';
            $this->newSocialUrl = '';
        }
    }

    public function removeSocialLink($index)
    {
        unset($this->social_links[$index]);
        $this->social_links = array_values($this->social_links);
    }

    public function save()
    {
        $this->validate();

        $data = [
            'full_name' => $this->full_name,
            'title_line' => $this->title_line,
            'hero_tagline' => $this->hero_tagline,
            'bio_paragraph_1' => $this->bio_paragraph_1,
            'bio_paragraph_2' => $this->bio_paragraph_2,
            'bio_paragraph_3' => $this->bio_paragraph_3,
            'expertise_tags' => $this->expertise_tags,
            'stat_years' => $this->stat_years,
            'stat_focus' => $this->stat_focus,
            'stat_approach' => $this->stat_approach,
            'email' => $this->email,
            'location' => $this->location,
            'response_time' => $this->response_time,
            'website_url' => $this->website_url,
            'scholar_url' => $this->scholar_url,
            'linkedin_url' => $this->linkedin_url,
            'social_links' => $this->social_links,
            'footer_tagline' => $this->footer_tagline,
        ];

        if ($this->photo) {
            $data['photo_path'] = $this->photo->store('profiles', 'public');
        }

        if ($this->speaker_kit) {
            $data['speaker_kit_path'] = $this->speaker_kit->store('profiles', 'public');
        }

        Profile::updateOrCreate(['id' => 1], $data);

        $this->dispatch('notify', 'Profile updated successfully!');
    }
};
?>

<div class="space-y-12 pb-20">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <h2 class="text-2xl font-display font-bold text-navy">Professional Profile</h2>
        <button wire:click="save" class="px-8 py-3 bg-navy text-primary font-bold rounded-lg hover:opacity-90 transition-all shadow-lg">
            Save Changes
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Sidebar: Assets -->
        <div class="space-y-8">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <h3 class="text-xs font-bold text-navy uppercase tracking-widest mb-6">Profile Photo</h3>
                <div class="flex flex-col items-center gap-6">
                    <div class="w-48 h-48 rounded-full overflow-hidden border-4 border-primary/20 bg-slate-50 relative group">
                        @if($photo)
                            <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover" />
                        @elseif($photo_path)
                            <img src="{{ Storage::url($photo_path) }}" class="w-full h-full object-cover" />
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                <span class="material-symbols-outlined text-6xl">person</span>
                            </div>
                        @endif
                        <label class="absolute inset-0 bg-navy/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                            <span class="text-white text-xs font-bold">Change Photo</span>
                            <input type="file" wire:model="photo" class="hidden" />
                        </label>
                    </div>
                    <p class="text-[10px] text-slate-400 text-center">Recommended: 800x800px. JPG or PNG.</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <h3 class="text-xs font-bold text-navy uppercase tracking-widest mb-6">Speaker Kit</h3>
                <div class="space-y-4">
                    @if($speaker_kit_path)
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">picture_as_pdf</span>
                            <span class="text-xs font-bold text-navy truncate max-w-[120px]">{{ basename($speaker_kit_path) }}</span>
                        </div>
                        <a href="{{ Storage::url($speaker_kit_path) }}" target="_blank" class="text-primary hover:underline">
                            <span class="material-symbols-outlined text-lg">download</span>
                        </a>
                    </div>
                    @endif
                    <input type="file" wire:model="speaker_kit" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-all" />
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
                <h3 class="text-xs font-bold text-navy uppercase tracking-widest mb-8 border-b border-slate-100 pb-4">General Information</h3>
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Full Name</label>
                            <input wire:model="full_name" type="text" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Title Line</label>
                            <input wire:model="title_line" type="text" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Hero Tagline</label>
                        <input wire:model="hero_tagline" type="text" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
                <h3 class="text-xs font-bold text-navy uppercase tracking-widest mb-8 border-b border-slate-100 pb-4">Biography</h3>
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Paragraph 1 (Main Bio)</label>
                        <textarea wire:model="bio_paragraph_1" rows="3" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none resize-none"></textarea>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Paragraph 2</label>
                        <textarea wire:model="bio_paragraph_2" rows="3" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none resize-none"></textarea>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Paragraph 3</label>
                        <textarea wire:model="bio_paragraph_3" rows="3" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none resize-none"></textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
                <h3 class="text-xs font-bold text-navy uppercase tracking-widest mb-8 border-b border-slate-100 pb-4">Expertise & Stats</h3>
                <div class="space-y-8">
                    <div class="space-y-4">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Expertise Tags</label>
                        <div class="flex flex-wrap gap-2 mb-4">
                            @foreach($expertise_tags as $index => $tag)
                            <span class="px-3 py-1 bg-slate-100 rounded-full text-xs font-bold text-navy flex items-center gap-2">
                                {{ $tag }}
                                <button wire:click="removeTag({{ $index }})" class="hover:text-red-500"><span class="material-symbols-outlined text-xs">close</span></button>
                            </span>
                            @endforeach
                        </div>
                        <div class="flex gap-2">
                            <input wire:model="newTag" wire:keydown.enter.prevent="addTag" type="text" placeholder="Add a tag..." class="flex-1 px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                            <button wire:click="addTag" type="button" class="px-4 py-2 bg-slate-100 text-navy font-bold rounded-lg hover:bg-slate-200 transition-all text-xs uppercase">Add</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Years Stat</label>
                            <input wire:model="stat_years" type="text" placeholder="e.g. 10+" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Focus Stat</label>
                            <input wire:model="stat_focus" type="text" placeholder="e.g. Global" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Approach Stat</label>
                            <input wire:model="stat_approach" type="text" placeholder="e.g. Evidence-Based" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
                <h3 class="text-xs font-bold text-navy uppercase tracking-widest mb-8 border-b border-slate-100 pb-4">Contact & Social Links</h3>
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Public Email</label>
                            <input wire:model="email" type="email" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Location</label>
                            <input wire:model="location" type="text" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                        </div>
                    </div>

                    <div class="space-y-4">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Social Media & Research Profiles</label>
                        <div class="space-y-3">
                            @foreach($social_links as $index => $link)
                            <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100 group">
                                <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center">
                                    @php
                                        $icon = match($link['platform']) {
                                            'linkedin' => 'link',
                                            'twitter' => 'share',
                                            'instagram' => 'photo_camera',
                                            'scholar' => 'school',
                                            'researchgate' => 'menu_book',
                                            'github' => 'code',
                                            default => 'public'
                                        };
                                    @endphp
                                    <span class="material-symbols-outlined text-sm text-primary">{{ $icon }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <span class="block text-[10px] font-bold text-navy uppercase tracking-tighter">{{ $link['platform'] }}</span>
                                    <span class="block text-xs text-slate-500 truncate">{{ $link['url'] }}</span>
                                </div>
                                <button wire:click="removeSocialLink({{ $index }})" class="p-2 text-slate-400 hover:text-red-500 transition-colors">
                                    <span class="material-symbols-outlined text-sm">delete</span>
                                </button>
                            </div>
                            @endforeach
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 pt-2">
                            <select wire:model="newSocialPlatform" class="px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none text-xs">
                                <option value="">Select Platform...</option>
                                <option value="linkedin">LinkedIn</option>
                                <option value="twitter">X (Twitter)</option>
                                <option value="instagram">Instagram</option>
                                <option value="scholar">Google Scholar</option>
                                <option value="researchgate">ResearchGate</option>
                                <option value="github">GitHub</option>
                                <option value="other">Other Website</option>
                            </select>
                            <input wire:model="newSocialUrl" type="url" placeholder="https://..." class="md:col-span-2 px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none text-xs" />
                        </div>
                        <button wire:click="addSocialLink" type="button" class="w-full py-2 bg-slate-100 text-navy font-bold rounded-lg hover:bg-slate-200 transition-all text-xs uppercase">
                            Add Social Link
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-1 gap-6 pt-4 border-t border-slate-100">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Response Time Tag</label>
                            <input wire:model="response_time" type="text" placeholder="e.g. Typically replies in 24h" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Footer Tagline</label>
                        <input wire:model="footer_tagline" type="text" class="w-full px-4 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-primary outline-none" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



