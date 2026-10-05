<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="@yield('meta_description', '')">
    <meta name="keywords" content="@yield('meta_keywords', '')">
    <meta name="author" content="@yield('meta_author', '')">
    <link rel="icon" type="image/webp" href="/icon.webp">

    <!-- Open Graph / Facebook -->
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:type" content="website">
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
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;700;800&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;700;800&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    </noscript>

    <!-- Tailwind Config -->
    <script>
        window.tailwind = {
            config: {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['"JetBrains Mono"', 'Consolas', 'Courier New', 'monospace'],
                            mono: ['"JetBrains Mono"', 'Consolas', 'Courier New', 'monospace'],
                        },
                        animation: {
                            'float': 'float 3s ease-in-out infinite',
                            'scan': 'scan 2s linear infinite',
                        },
                        keyframes: {
                            float: {
                                '0%, 100%': { transform: 'translateY(0)' },
                                '50%': { transform: 'translateY(-10px)' },
                            },
                            scan: {
                                '0%': { transform: 'translateY(-100%)' },
                                '100%': { transform: 'translateY(100%)' },
                            }
                        }
                    }
                }
            }
        };
    </script>
    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>
    
    <style type="text/tailwindcss">
        @layer utilities {
            .reveal {
                opacity: 0;
                translate: 0 30px;
                transition: all 0.8s ease-out;
            }
            .reveal.active {
                opacity: 1;
                translate: 0 0;
            }
        }
    </style>

    <!-- Alpine.js (CDN) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @yield('head')
</head>
<body class="bg-[#020617] text-slate-100 font-sans selection:bg-sky-500/30 overflow-x-hidden min-h-screen">
    <!-- Background System -->
    <div class="fixed inset-0 pointer-events-none z-0 bg-[#020617]">
        <div class="absolute inset-0 bg-[url('/images/space-bg.webp')] bg-cover bg-center bg-no-repeat"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-[#020617]/20 to-[#020617]/90"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,transparent_30%,#020617_100%)] opacity-60"></div>
    </div>

    @yield('content')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const reveals = document.querySelectorAll('.reveal');
            
            const revealObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        // Apply delay if specified in data-delay
                        const delay = entry.target.dataset.delay || 0;
                        setTimeout(() => {
                            entry.target.classList.add('active');
                        }, delay);
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                rootMargin: '0px 0px -50px 0px',
                threshold: 0.1
            });

            reveals.forEach(reveal => revealObserver.observe(reveal));
        });
    </script>
</body>
</html>
