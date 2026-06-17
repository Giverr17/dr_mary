<header class="sticky top-0 z-50 w-full bg-navy border-b border-primary/20 shadow-md">
    <div class="px-6 h-16 flex items-center justify-between max-w-7xl mx-auto">
        <div class="flex items-center gap-2">
            <a href="/"
                class="text-xl md:text-2xl font-display font-bold text-primary tracking-tight hover:text-primary/80 transition-colors">Dr.
                Uhunoma</a>
        </div>
        <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-200">
            <a class="hover:text-primary transition-colors" href="/about">About</a>
            <a class="hover:text-primary transition-colors" href="/research">Research</a>
            <a class="hover:text-primary transition-colors" href="/consulting">Consulting</a>
            <a class="hover:text-primary transition-colors" href="/events">Events</a>
            <a class="hover:text-primary transition-colors" href="/contact">Contact</a>
        </nav>
        <div class="flex items-center gap-4">
            <a href="/contact"
                class="hidden sm:flex items-center justify-center px-5 h-10 border border-primary text-primary hover:bg-primary hover:text-navy text-sm font-bold rounded-lg transition-all">
                Book a Consultation
            </a>
            <button class="md:hidden p-2 text-primary"
                onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                <span class="material-symbols-outlined">menu</span>
            </button>
        </div>
    </div>
    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-navy border-t border-primary/20 px-6 py-4">
        <nav class="flex flex-col gap-4 text-sm font-medium text-gray-200">
            <a class="hover:text-primary transition-colors py-2" href="/about">About</a>
            <a class="hover:text-primary transition-colors py-2" href="/research">Research</a>
            <a class="hover:text-primary transition-colors py-2" href="/consulting">Consulting</a>
            <a class="hover:text-primary transition-colors py-2" href="/events">Events</a>
            <a class="hover:text-primary transition-colors py-2" href="/contact">Contact</a>
            <a href="/contact"
                class="flex items-center justify-center px-5 h-10 border border-primary text-primary hover:bg-primary hover:text-navy text-sm font-bold rounded-lg transition-all mt-2">Book
                a Consultation</a>
        </nav>
    </div>
</header>
