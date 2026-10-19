<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="@yield('meta_description', '')">
    <meta name="keywords" content="@yield('meta_keywords', '')">
    <meta name="author" content="@yield('meta_author', '')">
    <meta name="theme-color" content="#020617">
    <link rel="icon" type="image/webp" href="/icon.webp">
    <link rel="apple-touch-icon" href="/icon.webp">

    <!-- Open Graph / Facebook -->
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="@yield('og_title', config('app.name'))">
    <meta property="og:description" content="@yield('og_description', '')">
    <meta property="og:image" content="@yield('og_image', url('/icon.webp'))">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url('/') }}">
    <meta property="twitter:title" content="@yield('twitter_title', config('app.name'))">
    <meta property="twitter:description" content="@yield('twitter_description', '')">
    <meta property="twitter:image" content="@yield('twitter_image', url('/icon.webp'))">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    </noscript>

    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="{{ tailwindcss('css/app.css') }}">

    <!-- Alpine.js (CDN) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @yield('head')
</head>
<body class="bg-[#020617] text-slate-100 font-sans antialiased selection:bg-sky-500/30 overflow-x-hidden min-h-screen">
    <!-- Background System -->
    <div class="fixed top-0 left-0 w-full h-screen h-[100dvh] pointer-events-none z-0 bg-[#020617]">
        <div class="absolute inset-0 bg-[url('/images/background.webp')] bg-cover bg-center bg-no-repeat"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-[#020617]/20 to-[#020617]/90"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,transparent_30%,#020617_100%)] opacity-60"></div>
    </div>

    <!-- Cosmic Space Particles & Shooting Stars Canvas (Layer Tengah) -->
    <canvas id="space-particles-canvas" class="fixed inset-0 w-full h-full pointer-events-none z-[1]" aria-hidden="true"></canvas>

    <!-- Page Content (Layer Depan) -->
    <div class="relative z-10">
        @yield('content')
    </div>

    <!-- Cosmic Space Particles & Shooting Stars Script -->
    <script src="/js/space-particles.js" defer></script>

</body>
</html>
