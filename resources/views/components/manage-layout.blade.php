<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard — Dr. Mary</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-body bg-slate-50 text-slate-900" x-data="{ sidebarOpen: false }">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside 
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="w-64 bg-navy text-white flex flex-col fixed inset-y-0 left-0 z-50 transform lg:translate-x-0 lg:static lg:inset-0 transition-transform duration-300 ease-in-out"
        >
            <div class="p-6 border-b border-white/10 flex items-center justify-between">
                <div>
                    <a href="/manage" class="text-xl font-display font-bold text-primary">Dr. Mary</a>
                    <p class="text-xs text-white/40 mt-1">Management Portal</p>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden text-white/60 hover:text-white">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <a href="/manage" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/5 transition-colors {{ request()->is('manage') ? 'bg-white/10 text-primary' : '' }}">
                    <span class="material-symbols-outlined text-xl">dashboard</span>
                    Dashboard
                </a>
                
                <div class="pt-4 pb-2 px-4 text-[10px] uppercase tracking-wider text-white/30 font-bold">Content</div>
                
                <a href="/manage/profile" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/5 transition-colors {{ request()->is('manage/profile*') ? 'bg-white/10 text-primary' : '' }}">
                    <span class="material-symbols-outlined text-xl">person</span>
                    Profile
                </a>
                <a href="/manage/publications" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/5 transition-colors {{ request()->is('manage/publications*') ? 'bg-white/10 text-primary' : '' }}">
                    <span class="material-symbols-outlined text-xl">library_books</span>
                    Publications
                </a>
                <a href="/manage/focus-areas" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/5 transition-colors {{ request()->is('manage/focus-areas*') ? 'bg-white/10 text-primary' : '' }}">
                    <span class="material-symbols-outlined text-xl">science</span>
                    Focus Areas
                </a>
                <a href="/manage/core-values" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/5 transition-colors {{ request()->is('manage/core-values*') ? 'bg-white/10 text-primary' : '' }}">
                    <span class="material-symbols-outlined text-xl">verified</span>
                    Core Values
                </a>
                <a href="/manage/achievements" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/5 transition-colors {{ request()->is('manage/achievements*') ? 'bg-white/10 text-primary' : '' }}">
                    <span class="material-symbols-outlined text-xl">workspace_premium</span>
                    Achievements
                </a>
                <a href="/manage/media-archive" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/5 transition-colors {{ request()->is('manage/media-archive*') ? 'bg-white/10 text-primary' : '' }}">
                    <span class="material-symbols-outlined text-xl">video_library</span>
                    Media Archive
                </a>
                <a href="/manage/events" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/5 transition-colors {{ request()->is('manage/events*') ? 'bg-white/10 text-primary' : '' }}">
                    <span class="material-symbols-outlined text-xl">event</span>
                    Events
                </a>
                <a href="/manage/services" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/5 transition-colors {{ request()->is('manage/services*') ? 'bg-white/10 text-primary' : '' }}">
                    <span class="material-symbols-outlined text-xl">handshake</span>
                    Services
                </a>
                <a href="/manage/testimonials" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/5 transition-colors {{ request()->is('manage/testimonials*') ? 'bg-white/10 text-primary' : '' }}">
                    <span class="material-symbols-outlined text-xl">reviews</span>
                    Testimonials
                </a>
                <a href="/manage/credentials" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/5 transition-colors {{ request()->is('manage/credentials*') ? 'bg-white/10 text-primary' : '' }}">
                    <span class="material-symbols-outlined text-xl">school</span>
                    Credentials
                </a>
                
                <div class="pt-4 pb-2 px-4 text-[10px] uppercase tracking-wider text-white/30 font-bold">Inbox</div>
                
                <a href="/manage/messages" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/5 transition-colors {{ request()->is('manage/messages*') ? 'bg-white/10 text-primary' : '' }}">
                    <span class="material-symbols-outlined text-xl">mail</span>
                    Messages
                </a>
                <a href="/manage/newsletter" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/5 transition-colors {{ request()->is('manage/newsletter*') ? 'bg-white/10 text-primary' : '' }}">
                    <span class="material-symbols-outlined text-xl">campaign</span>
                    Newsletter
                </a>
            </nav>

            <div class="p-4 border-t border-white/10">
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-4 py-2 w-full text-left rounded-lg hover:bg-red-500/10 text-red-400 transition-colors">
                        <span class="material-symbols-outlined text-xl">logout</span>
                        Sign Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- Overlay -->
        <div 
            x-show="sidebarOpen" 
            @click="sidebarOpen = false" 
            class="fixed inset-0 bg-navy/60 backdrop-blur-sm z-40 lg:hidden"
            x-transition:enter="transition opacity-0"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition opacity-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        ></div>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-w-0">
            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 lg:px-8 sticky top-0 z-40">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 text-slate-500 hover:text-navy">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                    <h1 class="text-sm lg:text-lg font-semibold text-slate-800 truncate">{{ $header ?? 'Overview' }}</h1>
                </div>
                
                <div class="flex items-center gap-4">
                    <a href="/" target="_blank" class="hidden sm:flex text-xs text-slate-500 hover:text-primary transition-colors items-center gap-1">
                        View Site <span class="material-symbols-outlined text-sm">open_in_new</span>
                    </a>
                    <div class="h-8 w-8 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold text-xs">
                        {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                    </div>
                </div>
            </header>

            <div class="p-4 lg:p-8">
                {{ $slot }}
            </div>
        </main>
    </div>

    @livewireScripts
</body>
</html>
