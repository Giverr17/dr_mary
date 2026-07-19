@php
    use Illuminate\Support\Facades\Storage;
    $profile = \App\Models\Profile::first();
@endphp
<footer class="bg-background-light border-t border-gray-200">
    <div class="py-20 px-6 bg-navy relative overflow-hidden">
        <!-- Background decorative element -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-primary/5 rounded-full -ml-32 -mb-32 blur-3xl"></div>
        
        <div class="max-w-4xl mx-auto text-center relative z-10">
            <span class="text-primary font-bold tracking-[0.2em] uppercase text-xs mb-4 block">Newsletter</span>
            <h2 class="text-3xl md:text-4xl font-display font-bold text-white mb-4">Stay Connected</h2>
            <p class="text-gray-400 mb-10 text-lg max-w-xl mx-auto font-light leading-relaxed">
                Join 15,000+ subscribers receiving weekly insights on history, business, and policy.
            </p>
            <form id="subscribe_form" class="max-w-md mx-auto" onsubmit="return false;">
                <div class="flex flex-col sm:flex-row gap-0 group">
                    <input id="subscribe_email"
                        class="flex-1 px-6 py-4 rounded-t-xl sm:rounded-l-xl sm:rounded-tr-none border-0 bg-white/10 text-white placeholder-white/40 focus:bg-white/20 focus:ring-0 outline-none transition-all text-sm"
                        placeholder="Enter your professional email" type="email" />
                    <button id="subscribe_btn"
                        class="px-8 py-4 bg-primary text-navy font-bold rounded-b-xl sm:rounded-r-xl sm:rounded-bl-none hover:bg-primary-dark transition-all duration-300 shadow-xl flex items-center justify-center gap-2 group-focus-within:ring-2 ring-primary ring-offset-2 ring-offset-navy"
                        type="button">
                        <span>Subscribe</span>
                        <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </button>
                </div>
                <div id="subscribe_feedback" class="mt-4 text-sm font-medium hidden"></div>
            </form>
        </div>
    </div>
    <div class="max-w-7xl mx-auto px-6 py-16 grid grid-cols-1 md:grid-cols-4 gap-12 text-center md:text-left">
        <div class="col-span-1 md:col-span-1">
            <a href="/"
                class="text-xl font-display font-bold text-navy mb-4 block hover:text-primary transition-colors">Dr. Mary</a>
            <p class="text-sm text-slate-500 leading-relaxed mb-6">
                {{ $profile?->footer_tagline ?? 'Bridging the gap between academic research and actionable business strategies for a more equitable future.' }}
            </p>
            <div class="flex flex-wrap gap-4 justify-center md:justify-start">
                @if($profile?->social_links)
                    @foreach($profile->social_links as $link)
                    <a class="w-10 h-10 rounded-full bg-navy text-primary flex items-center justify-center hover:bg-primary hover:text-navy transition-colors"
                        href="{{ $link['url'] }}" target="_blank" title="{{ ucfirst($link['platform']) }}">
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
                        <span class="material-symbols-outlined text-lg">{{ $icon }}</span>
                    </a>
                    @endforeach
                @endif
                
                @if($profile?->email)
                <a class="w-10 h-10 rounded-full bg-navy text-primary flex items-center justify-center hover:bg-primary hover:text-navy transition-colors"
                    href="mailto:{{ $profile->email }}" title="Email Me">
                    <span class="material-symbols-outlined text-lg">mail</span>
                </a>
                @endif
            </div>
        </div>
        <div>
            <h4 class="font-bold text-navy mb-6 font-display text-lg">Quick Links</h4>
            <ul class="space-y-3 text-sm text-slate-600">
                <li><a class="hover:text-primary transition-colors" href="/about">About Dr. Mary</a></li>
                <li><a class="hover:text-primary transition-colors" href="/research">Research &amp;
                        Publications</a></li>
                <li><a class="hover:text-primary transition-colors" href="/consulting">Consulting
                        Services</a></li>
                <li><a class="hover:text-primary transition-colors" href="/events">Speaking Engagements</a>
                </li>
            </ul>
        </div>
        <div>
            <h4 class="font-bold text-navy mb-6 font-display text-lg">Resources</h4>
            <ul class="space-y-3 text-sm text-slate-600">
                <li><a class="hover:text-primary transition-colors" href="/research">Blog &amp; Insights</a>
                </li>
                <li><a class="hover:text-primary transition-colors" href="#">Podcast Episodes</a></li>
                <li><a class="hover:text-primary transition-colors" href="/research">Download Briefs</a>
                </li>
                @if($profile?->speaker_kit_path)
                <li><a class="hover:text-primary transition-colors" href="{{ Storage::url($profile->speaker_kit_path) }}" target="_blank">Press Kit</a></li>
                @else
                <li><span class="text-slate-400">Press Kit</span></li>
                @endif
            </ul>
        </div>
        <div>
            <h4 class="font-bold text-navy mb-6 font-display text-lg">Contact</h4>
            <ul class="space-y-4 text-sm text-slate-600">
                <li class="flex items-center justify-center md:justify-start gap-3">
                    <span class="material-symbols-outlined text-primary">mail</span>
                    {{ $profile?->email ?? 'hello@druhunoma.com' }}
                </li>
                <li class="flex items-center justify-center md:justify-start gap-3">
                    <span class="material-symbols-outlined text-primary">location_on</span>
                    {{ $profile?->location ?? 'Atlanta, GA' }}
                </li>
            </ul>
        </div>
    </div>
    <div class="bg-navy py-6 text-center">
        <p class="text-xs text-white/60">
            © {{ date('Y') }} Dr. Mary. All rights reserved. 
            <span class="mx-2 opacity-30">|</span> 
            <a href="/manage" class="hover:text-primary transition-colors">Admin Portal</a>
        </p>
    </div>
    <script>
        (function(){
            const btn = document.getElementById('subscribe_btn');
            const input = document.getElementById('subscribe_email');
            const form = document.getElementById('subscribe_form');
            const feedback = document.getElementById('subscribe_feedback');
            if(!btn || !input || !form || !feedback) return;

            const showFeedback = (msg, isError = false) => {
                feedback.textContent = msg;
                feedback.className = `mt-4 text-sm font-medium ${isError ? 'text-red-400' : 'text-primary'}`;
                feedback.classList.remove('hidden');
                setTimeout(() => feedback.classList.add('hidden'), 5000);
            };

            const submitForm = async () => {
                const email = input.value.trim();
                if(!email){
                    showFeedback('Please enter your email address.', true);
                    input.focus();
                    return;
                }
                btn.disabled = true;
                btn.innerHTML = '<span class="animate-spin material-symbols-outlined text-sm">progress_activity</span> <span>Sending...</span>';
                feedback.classList.add('hidden');

                try {
                    const token = document.querySelector('meta[name=csrf-token]')?.getAttribute('content');
                    const res = await fetch('/subscribe', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token || ''
                        },
                        body: JSON.stringify({ email })
                    });
                    if(res.ok){
                        showFeedback('Success! Thank you for subscribing.');
                        input.value = '';
                    } else {
                        const data = await res.json();
                        showFeedback(data.message || 'Subscription failed. Please try again.', true);
                    }
                } catch (e) {
                    showFeedback('Something went wrong. Please try again later.', true);
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = '<span>Subscribe</span><span class="material-symbols-outlined text-sm">arrow_forward</span>';
                }
            };

            btn.addEventListener('click', submitForm);
            input.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    submitForm();
                }
            });
        })();
    </script>
</footer>
