<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dr. Uhunoma M. Isibor' }}</title>
    <meta name="description" content="@yield('description', 'Dr. Uhunoma M. Isibor — Historian, Scholar, Consultant')">

    <!-- Fonts -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block" as="style" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        /* Hide icon text until Material Symbols font is loaded */
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.2s ease;
        }
        html.fonts-ready .material-symbols-outlined {
            visibility: visible;
            opacity: 1;
        }
        /* Skeleton shimmer for lazy content */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
            border-radius: 0.5rem;
        }
        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        [x-cloak] { display: none !important; }
    </style>
    <script>
        /* Mark fonts as ready as soon as Material Symbols has loaded */
        document.fonts.ready.then(function () {
            document.documentElement.classList.add('fonts-ready');
        });
    </script>
</head>
<body class="font-body bg-background-light text-slate-800">

    <x-navigation />

    <main>
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <x-footer />

    @livewireScripts
</body>
</html>
