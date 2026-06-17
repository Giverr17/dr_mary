<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Dr. Uhunoma M. Isibor — Historian, Scholar, Consultant</title>
    <meta name="description"
        content="Dr. Uhunoma M. Isibor — empowering Black women entrepreneurs through research, strategy, and policy engagement in Canada and Africa." />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&amp;family=Inter:wght@300;400;500;600;700&amp;display=swap"
        rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#C9A84C", "primary-dark": "#b0924b",
                        "navy": "#1B2A4A", "background-light": "#F9F7F4", "background-dark": "#1B2A4A",
                    },
                    fontFamily: {
                        "display": ["Playfair Display", "serif"],
                        "body": ["Inter", "sans-serif"]
                    },
                    borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        body {
            min-height: max(884px, 100dvh);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.7s ease-out both;
        }

        .delay-100 {
            animation-delay: 0.1s;
        }

        .delay-200 {
            animation-delay: 0.2s;
        }

        .delay-300 {
            animation-delay: 0.3s;
        }
    </style>
</head>

<body
    class="bg-background-light text-slate-900 font-body transition-colors duration-200 selection:bg-primary selection:text-navy">
    <header class="sticky top-0 z-50 w-full bg-navy border-b border-primary/20 shadow-md">
        <div class="px-6 h-16 flex items-center justify-between max-w-7xl mx-auto">
            <div class="flex items-center gap-2">
                <a href="index.html"
                    class="text-xl md:text-2xl font-display font-bold text-primary tracking-tight hover:text-primary/80 transition-colors">Dr.
                    Uhunoma</a>
            </div>
            <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-200">
                <a class="hover:text-primary transition-colors" href="about.html">About</a>
                <a class="hover:text-primary transition-colors" href="research.html">Research</a>
                <a class="hover:text-primary transition-colors" href="consulting.html">Consulting</a>
                <a class="hover:text-primary transition-colors" href="events.html">Events</a>
                <a class="hover:text-primary transition-colors" href="contact.html">Contact</a>
            </nav>
            <div class="flex items-center gap-4">
                <a href="contact.html"
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
                <a class="hover:text-primary transition-colors py-2" href="about.html">About</a>
                <a class="hover:text-primary transition-colors py-2" href="research.html">Research</a>
                <a class="hover:text-primary transition-colors py-2" href="consulting.html">Consulting</a>
                <a class="hover:text-primary transition-colors py-2" href="events.html">Events</a>
                <a class="hover:text-primary transition-colors py-2" href="contact.html">Contact</a>
                <a href="contact.html"
                    class="flex items-center justify-center px-5 h-10 border border-primary text-primary hover:bg-primary hover:text-navy text-sm font-bold rounded-lg transition-all mt-2">Book
                    a Consultation</a>
            </nav>
        </div>
    </header>
    <main class="w-full flex flex-col overflow-x-hidden">
        <section class="relative px-6 py-12 md:py-24 bg-background-light">
            <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="flex flex-col gap-8 order-2 md:order-1 text-center md:text-left">
                    <div class="flex flex-col gap-4">
                        <h1 class="text-4xl md:text-6xl font-display font-bold text-navy leading-[1.15]">
                            Historian. Scholar. <br />Consultant.
                        </h1>
                        <p class="text-lg md:text-xl text-slate-700 leading-relaxed font-body font-light">
                            Empowering Black Women Entrepreneurs through Research, Strategy, and Policy Engagement in
                            Canada and Africa.
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row flex-wrap gap-4 mt-2 justify-center md:justify-start">
                        <button
                            class="flex items-center justify-center px-6 h-12 bg-navy hover:bg-navy/90 text-primary font-bold rounded-lg transition-all shadow-lg text-sm md:text-base border border-navy">
                            Download Research Briefs
                        </button>
                        <button
                            class="flex items-center justify-center px-6 h-12 bg-primary hover:bg-primary-dark text-navy font-bold rounded-lg transition-all shadow-lg text-sm md:text-base border border-primary">
                            Join the Summit
                        </button>
                        <button
                            class="flex items-center justify-center px-6 h-12 border-2 border-navy text-navy font-bold rounded-lg hover:bg-navy hover:text-white transition-all text-sm md:text-base bg-transparent">
                            Book a Consultation
                        </button>
                    </div>
                </div>
                <div class="order-1 md:order-2 flex justify-center md:justify-end">
                    <div
                        class="relative w-64 h-64 md:w-96 md:h-96 rounded-full overflow-hidden border-4 border-primary shadow-2xl">
                        <img alt="Professional portrait of Dr. Uhunoma smiling warmly in business attire"
                            class="w-full h-full object-cover"
                            data-alt="Professional portrait of Dr. Uhunoma smiling warmly in business attire"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDGGf3XqrvS1TKv4qoWqcRV80PKSUfPAnlRKr_diiDg47s2jVhI4xC1O06FRinF6DbeMXsKuwl46yq9zn4lJ9Big_5KJQRxub-UpfpVLQoZOj3Zr-ceD6bKjDSW7H1kVX6SJ74H1LJrcFv-0MxtzbUby2dh5Ue_b1kpfDUazTb0ZivJcYTgUCJ7iNqdakb6EF_SMy21EswsZrV64R-F1bqSJwhvoGcQM05Sj4JFGeEEx9Jip4FDq3ACn_ZOw5--xVYFJamLpHPx143M" />
                    </div>
                </div>
            </div>
        </section>
        <div class="w-full bg-navy border-t border-b border-primary/30">
            <div class="max-w-7xl mx-auto px-6 py-8">
                <div class="flex flex-col md:flex-row items-center justify-center md:justify-between gap-6 md:gap-0">
                    <div class="flex-1 text-center px-4">
                        <span class="text-xl md:text-2xl font-display font-bold text-white block">10+ Years
                            Research</span>
                    </div>
                    <div class="hidden md:block w-px h-12 bg-primary/60"></div>
                    <div class="block md:hidden w-16 h-px bg-primary/60"></div>
                    <div class="flex-1 text-center px-4">
                        <span class="text-xl md:text-2xl font-display font-bold text-white block">Canada &amp; Africa
                            Focus</span>
                    </div>
                    <div class="hidden md:block w-px h-12 bg-primary/60"></div>
                    <div class="block md:hidden w-16 h-px bg-primary/60"></div>
                    <div class="flex-1 text-center px-4">
                        <span class="text-xl md:text-2xl font-display font-bold text-white block">Policy-Driven
                            Strategy</span>
                    </div>
                </div>
            </div>
        </div>
        <section class="bg-white py-16 px-6">
            <div class="max-w-3xl mx-auto text-center flex flex-col gap-6">
                <h2 class="text-3xl font-display font-bold text-navy">Bridging the Gap</h2>
                <p class="text-slate-700 text-lg md:text-xl font-body leading-relaxed">
                    Dr. Uhunoma M. Isibor is a dedicated scholar-consultant bridging the gap between historical insights
                    and modern entrepreneurship. With a focus on Black women's economic empowerment, she translates
                    rigorous academic research into actionable policy strategies. Her work fosters sustainable growth
                    and equitable opportunities across Canada and the African continent.
                </p>
                <div class="pt-4">
                    <button
                        class="inline-flex items-center gap-2 text-navy font-semibold hover:text-primary transition-colors group">
                        <a href="about.html"
                            class="inline-flex items-center gap-2 text-navy font-semibold hover:text-primary transition-colors">Read
                            Dr. Uhunoma's Full Bio</a>
                        <span
                            class="material-symbols-outlined text-lg group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </button>
                </div>
            </div>
        </section>
        <section class="px-6 py-16 bg-background-light">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col md:flex-row justify-between items-end gap-4 mb-10">
                    <div class="max-w-xl">
                        <span class="text-primary font-bold tracking-wider uppercase text-sm mb-2 block">Latest
                            Findings</span>
                        <h2 class="text-3xl font-display font-bold text-navy">Research &amp; Insights</h2>
                    </div>
                    <a class="text-slate-600 hover:text-navy font-medium flex items-center gap-1 text-sm transition-colors"
                        href="#">
                        View All Research <span class="material-symbols-outlined text-base">arrow_forward</span>
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <article
                        class="group bg-white rounded-xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300">
                        <div class="aspect-[16/9] overflow-hidden bg-gray-100 relative">
                            <img alt="Close up of financial documents and graphs on a desk"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                data-alt="Close up of financial documents and graphs on a desk"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuDK-XPko8eU5vLHJ8AOIimI-8sF3fVYabiMt95ZKQb6qkGt3aOnfvW5BPu3VHOJ4GQvybtwxA6AETRYG7CPlkg3invoIQSJnDtmfTv_vOrDtJdcbTm7qtzu3VPhTvW1O10S_OTgjUGZwu498QAso5WcfaZ2Lsqr7s28lNvV_P3UFflVrpoz1Qaijx4ZWa39yvnuXs2k1ZbB1kuSi1kjdUipOnhW0mWmkIBPH7ozhEnTZBh5_RsuIfU_HuNWq85BPDKmiGL9BKTBYCww" />
                            <div class="absolute inset-0 bg-navy/10 group-hover:bg-transparent transition-colors"></div>
                        </div>
                        <div class="p-6 flex flex-col gap-4 h-full">
                            <h3
                                class="text-xl font-display font-bold text-navy leading-tight group-hover:text-primary transition-colors">
                                The Economic Impact of Black Women Enterprises (2023)
                            </h3>
                            <p class="text-slate-600 text-sm line-clamp-3">
                                A comprehensive analysis of how Black women are reshaping the global economy despite
                                systemic barriers in funding and access.
                            </p>
                            <button
                                class="mt-auto pt-4 flex items-center gap-2 text-navy font-bold text-sm hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-lg">download</span> Download PDF
                            </button>
                        </div>
                    </article>
                    <article
                        class="group bg-white rounded-xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300">
                        <div class="aspect-[16/9] overflow-hidden bg-gray-100 relative">
                            <img alt="Group of diverse women having a business meeting in a modern office"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                data-alt="Group of diverse women having a business meeting in a modern office"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuDdtTiBTIU9qnJAL9jMtns7zJ4uuZGutcvh_KXOHybNrVE_lyri0dESRpmk_ne_r0ELoU97C9x_jxKH-912ys3cMKsMRwy0Z3ciNfvEfBH10oYShl8jd-vzB_4ZEiTJ-GY-YaxRLBGnAZA6ZcNQwsqIOOB3IM7-qqeqx4fUsll2OWqWANWV8NBdEcRPspElGTQ9O50dz2eMKzXVrOhhL9fgeqAS5ui0l_vYgHDW9YSWpnsu1JerlbZti0QZozDs8dOyjrboIlsRyxih" />
                            <div class="absolute inset-0 bg-navy/10 group-hover:bg-transparent transition-colors"></div>
                        </div>
                        <div class="p-6 flex flex-col gap-4 h-full">
                            <h3
                                class="text-xl font-display font-bold text-navy leading-tight group-hover:text-primary transition-colors">
                                Historical Lending Practices &amp; Modern Equity
                            </h3>
                            <p class="text-slate-600 text-sm line-clamp-3">
                                Tracing the roots of financial exclusion and proposing modern policy frameworks to
                                create equitable lending environments.
                            </p>
                            <button
                                class="mt-auto pt-4 flex items-center gap-2 text-navy font-bold text-sm hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-lg">download</span> Download PDF
                            </button>
                        </div>
                    </article>
                    <article
                        class="group bg-white rounded-xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300">
                        <div class="aspect-[16/9] overflow-hidden bg-gray-100 relative">
                            <img alt="Hands holding pens over a conference table during a strategy session"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                data-alt="Hands holding pens over a conference table during a strategy session"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuDsdc7ZoSyPsszYYbHlRdBtRRJOLicgrSvbWD9Jq-SW2_GIf6WwhzfWDSZKMUOKJgTRrPSUBpe_iLeU3q3yIpXskIa30TPjVO5x5UaVv9AgtOhNzrj3eTol0RUqc8oM_zll1-a57FHJBXUWo6l43xXdNwlmhxLU3QMSFtUPcazJZRmU5z5KPDgpEPcCrQ5HROhmOCBPSRnVLk_kSGapTagd4apQGCebwKaQmUbw81f50sao7qWL9164KEzzL3a-ZN2M-tXlAeJyRQz7" />
                            <div class="absolute inset-0 bg-navy/10 group-hover:bg-transparent transition-colors"></div>
                        </div>
                        <div class="p-6 flex flex-col gap-4 h-full">
                            <h3
                                class="text-xl font-display font-bold text-navy leading-tight group-hover:text-primary transition-colors">
                                Policy Brief: Sustainable Growth Models
                            </h3>
                            <p class="text-slate-600 text-sm line-clamp-3">
                                Key recommendations for policymakers to support the longevity and scalability of
                                minority-owned businesses.
                            </p>
                            <button
                                class="mt-auto pt-4 flex items-center gap-2 text-navy font-bold text-sm hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-lg">download</span> Download PDF
                            </button>
                        </div>
                    </article>
                </div>
            </div>
        </section>
        <section class="bg-navy py-20 px-6 relative overflow-hidden">
            <div
                class="absolute top-0 right-0 w-96 h-96 bg-primary/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2">
            </div>
            <div
                class="absolute bottom-0 left-0 w-64 h-64 bg-primary/5 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2">
            </div>
            <div class="relative z-10 max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <span class="text-primary font-bold tracking-wider uppercase text-sm mb-3 block">Services</span>
                    <h2 class="text-3xl md:text-4xl font-display font-bold text-white mb-6">Strategic Consulting</h2>
                    <p class="text-slate-300 max-w-2xl mx-auto text-lg">
                        Tailored guidance for organizations and individuals looking to navigate the intersection of
                        history, equity, and business growth.
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div
                        class="bg-white/5 backdrop-blur-sm border border-primary/20 p-8 rounded-xl hover:bg-white/10 transition-colors group">
                        <div
                            class="w-14 h-14 rounded-lg bg-transparent border border-primary flex items-center justify-center text-primary mb-6 group-hover:bg-primary group-hover:text-navy transition-all">
                            <span class="material-symbols-outlined text-3xl">trending_up</span>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3 font-display">Growth Strategy</h3>
                        <p class="text-slate-300 text-sm mb-8 leading-relaxed">
                            Data-driven strategic planning for scaling businesses while maintaining core mission values
                            and historical integrity.
                        </p>
                        <button
                            class="w-full py-3 rounded-lg border border-primary text-primary hover:bg-primary hover:text-navy font-bold text-sm transition-colors uppercase tracking-wide">
                            Book Consultation
                        </button>
                    </div>
                    <div
                        class="bg-white/5 backdrop-blur-sm border border-primary/20 p-8 rounded-xl hover:bg-white/10 transition-colors group">
                        <div
                            class="w-14 h-14 rounded-lg bg-transparent border border-primary flex items-center justify-center text-primary mb-6 group-hover:bg-primary group-hover:text-navy transition-all">
                            <span class="material-symbols-outlined text-3xl">account_balance</span>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3 font-display">Investment Advisory</h3>
                        <p class="text-slate-300 text-sm mb-8 leading-relaxed">
                            Guidance for VC firms and angel investors on identifying high-potential, historically
                            underfunded opportunities.
                        </p>
                        <button
                            class="w-full py-3 rounded-lg border border-primary text-primary hover:bg-primary hover:text-navy font-bold text-sm transition-colors uppercase tracking-wide">
                            Book Consultation
                        </button>
                    </div>
                    <div
                        class="bg-white/5 backdrop-blur-sm border border-primary/20 p-8 rounded-xl hover:bg-white/10 transition-colors group">
                        <div
                            class="w-14 h-14 rounded-lg bg-transparent border border-primary flex items-center justify-center text-primary mb-6 group-hover:bg-primary group-hover:text-navy transition-all">
                            <span class="material-symbols-outlined text-3xl">groups</span>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3 font-display">Corporate Workshops</h3>
                        <p class="text-slate-300 text-sm mb-8 leading-relaxed">
                            Educational sessions on economic history and inclusive leadership for corporate teams and
                            executive boards.
                        </p>
                        <button
                            class="w-full py-3 rounded-lg border border-primary text-primary hover:bg-primary hover:text-navy font-bold text-sm transition-colors uppercase tracking-wide">
                            Book Consultation
                        </button>
                    </div>
                </div>
            </div>
        </section>
        <section class="py-16 px-6 bg-white">
            <div
                class="max-w-7xl mx-auto rounded-2xl bg-gradient-to-r from-navy to-[#2a3f66] shadow-xl overflow-hidden relative">
                <div
                    class="absolute top-0 right-0 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5">
                </div>
                <div
                    class="relative z-10 px-8 py-12 md:py-16 md:px-16 flex flex-col md:flex-row items-center justify-between gap-10">
                    <div class="max-w-2xl text-center md:text-left">
                        <span
                            class="inline-block py-1 px-4 rounded-full bg-primary/20 text-primary text-xs font-bold tracking-widest mb-4 border border-primary/30 uppercase">Upcoming
                            Event</span>
                        <h2 class="text-3xl md:text-4xl font-display font-bold text-white mb-4">Scale &amp; Serve Summit
                            2024</h2>
                        <p class="text-primary text-lg font-medium mb-3">October 14-16, 2024 • Atlanta, GA</p>
                        <p class="text-slate-300 mb-0 max-w-lg leading-relaxed">The premier gathering for Black women
                            entrepreneurs ready to amplify their impact and revenue through historical insights.</p>
                    </div>
                    <div class="shrink-0">
                        <button
                            class="bg-primary text-navy hover:bg-white hover:text-navy font-bold py-4 px-10 rounded-lg shadow-lg transform transition hover:-translate-y-1">
                            Register Now
                        </button>
                    </div>
                </div>
            </div>
        </section>
        <footer class="bg-background-light border-t border-gray-200">
            <div class="py-16 px-6 bg-white">
                <div class="max-w-2xl mx-auto text-center">
                    <h2 class="text-2xl font-display font-bold text-navy mb-3">Stay Connected</h2>
                    <p class="text-slate-600 mb-8">
                        Join 15,000+ subscribers receiving weekly insights on history, business, and policy.
                    </p>
                    <form class="flex flex-col sm:flex-row gap-3">
                        <input
                            class="flex-1 px-5 py-3 rounded-lg border border-gray-300 bg-white text-slate-900 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all"
                            placeholder="Your email address" type="email" />
                        <button
                            class="px-8 py-3 bg-navy text-primary font-bold rounded-lg hover:bg-navy/90 transition-colors"
                            type="button">
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>
            <div class="max-w-7xl mx-auto px-6 py-16 grid grid-cols-1 md:grid-cols-4 gap-12 text-center md:text-left">
                <div class="col-span-1 md:col-span-1">
                    <a href="index.html"
                        class="text-xl font-display font-bold text-navy mb-4 block hover:text-primary transition-colors">Dr.
                        Uhunoma</a>
                    <p class="text-sm text-slate-500 leading-relaxed mb-6">
                        Bridging the gap between academic research and actionable business strategies for a more
                        equitable future.
                    </p>
                    <div class="flex gap-4 justify-center md:justify-start">
                        <a class="w-10 h-10 rounded-full bg-navy text-primary flex items-center justify-center hover:bg-primary hover:text-navy transition-colors"
                            href="#">
                            <span class="material-symbols-outlined text-lg">public</span>
                        </a>
                        <a class="w-10 h-10 rounded-full bg-navy text-primary flex items-center justify-center hover:bg-primary hover:text-navy transition-colors"
                            href="#">
                            <span class="material-symbols-outlined text-lg">mail</span>
                        </a>
                    </div>
                </div>
                <div>
                    <h4 class="font-bold text-navy mb-6 font-display text-lg">Quick Links</h4>
                    <ul class="space-y-3 text-sm text-slate-600">
                        <li><a class="hover:text-primary transition-colors" href="about.html">About Dr. Uhunoma</a></li>
                        <li><a class="hover:text-primary transition-colors" href="research.html">Research &amp;
                                Publications</a></li>
                        <li><a class="hover:text-primary transition-colors" href="consulting.html">Consulting
                                Services</a></li>
                        <li><a class="hover:text-primary transition-colors" href="events.html">Speaking Engagements</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-navy mb-6 font-display text-lg">Resources</h4>
                    <ul class="space-y-3 text-sm text-slate-600">
                        <li><a class="hover:text-primary transition-colors" href="research.html">Blog &amp; Insights</a>
                        </li>
                        <li><a class="hover:text-primary transition-colors" href="#">Podcast Episodes</a></li>
                        <li><a class="hover:text-primary transition-colors" href="research.html">Download Briefs</a>
                        </li>
                        <li><a class="hover:text-primary transition-colors" href="#">Press Kit</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-navy mb-6 font-display text-lg">Contact</h4>
                    <ul class="space-y-4 text-sm text-slate-600">
                        <li class="flex items-center justify-center md:justify-start gap-3">
                            <span class="material-symbols-outlined text-primary">mail</span>
                            hello@druhunoma.com
                        </li>
                        <li class="flex items-center justify-center md:justify-start gap-3">
                            <span class="material-symbols-outlined text-primary">location_on</span>
                            Atlanta, GA
                        </li>
                    </ul>
                </div>
            </div>hite/60">© 202
            <div class="bg-navy py-6 text-center">
                <p class="text-xs text-w5 Dr. Uhunoma M. Isibor. All rights reserved.</p>
            </div>
        </footer>
    </main>

</body>

</html>