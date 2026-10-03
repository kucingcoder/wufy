@extends('layouts.main')

@php
    $baseUrl = url('/');
    $formattedPhone = $profile && $profile->phone ? preg_replace('/[^0-9]/', '', $profile->phone) : '';
    if (str_starts_with($formattedPhone, '0')) {
        $formattedPhone = '62' . substr($formattedPhone, 1);
    }
    
    $skillsByCategory = [];
    foreach($skills as $skill) {
        $cat = $skill->category ?: 'teknologi';
        $skillsByCategory[$cat][] = $skill;
    }

    $metaDescription = strip_tags($profile->description ?? 'Portofolio profesional dan jasa pembuatan website/aplikasi.');
    if (strlen($metaDescription) > 155) {
        $metaDescription = substr($metaDescription, 0, 152) . '...';
    }
    
    $keywordsArray = ['Portfolio', 'Jasa Web', 'Software Developer', 'Programmer', 'Aplikasi', 'Website'];
    if ($profile) {
        $keywordsArray[] = $profile->full_name;
        $keywordsArray[] = $profile->job_title;
        if ($profile->city) $keywordsArray[] = $profile->city;
        if ($profile->seo_keywords) {
            $customKeywords = array_map('trim', explode(',', $profile->seo_keywords));
            $keywordsArray = array_merge($keywordsArray, $customKeywords);
        }
    }
    foreach($skills as $skill) {
        $keywordsArray[] = $skill->title;
    }
    $metaKeywords = implode(', ', array_filter(array_unique($keywordsArray)));

    $jsonLd = [
        "@context" => "https://schema.org",
        "@graph" => [
            [
                "@type" => "Person",
                "name" => $profile->full_name ?? '',
                "jobTitle" => $profile->job_title ?? '',
                "url" => url('/'),
                "image" => $profile->avatar ? url('storage/'.$profile->avatar) : url('/icon.webp'),
                "sameAs" => collect($profile->links ?? [])->pluck('link')->toArray(),
                "description" => $metaDescription,
                "address" => [
                    "@type" => "PostalAddress",
                    "addressLocality" => $profile->city ?? '',
                    "addressRegion" => $profile->province ?? '',
                    "addressCountry" => "ID"
                ]
            ],
            [
                "@type" => "WebSite",
                "url" => url('/'),
                "name" => ($profile->full_name ?? 'Portfolio') . ' - ' . ($profile->job_title ?? 'Expert'),
                "description" => $metaDescription,
                "publisher" => [
                    "@type" => "Person",
                    "name" => $profile->full_name ?? ''
                ]
            ]
        ]
    ];

    $icons = [
        'linkedin' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>',
        'github' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>',
        'instagram' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>',
        'default' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>'
    ];
    $getIcon = function($title, $icons) {
        $lower = strtolower($title);
        if (str_contains($lower, 'linkedin')) return $icons['linkedin'];
        if (str_contains($lower, 'github')) return $icons['github'];
        if (str_contains($lower, 'instagram')) return $icons['instagram'];
        return $icons['default'];
    };
@endphp

@section('title', ($profile->full_name ?? 'Portfolio') . ' - ' . ($profile->job_title ?? 'Expert'))
@section('meta_description', $metaDescription)
@section('meta_keywords', $metaKeywords)
@section('meta_author', $profile->full_name ?? '')
@section('og_title', ($profile->full_name ?? 'Portfolio') . ' - ' . ($profile->job_title ?? 'Expert'))
@section('og_description', $metaDescription)
@section('twitter_title', ($profile->full_name ?? 'Portfolio') . ' - ' . ($profile->job_title ?? 'Expert'))
@section('twitter_description', $metaDescription)
@if($profile && $profile->avatar)
    @section('og_image', url('storage/'.$profile->avatar))
    @section('twitter_image', url('storage/'.$profile->avatar))
@endif

@section('head')
    <script type="application/ld+json">
        {!! json_encode($jsonLd) !!}
    </script>
    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }
        ::-webkit-scrollbar-track {
            background: #020617;
        }
        ::-webkit-scrollbar-thumb {
            background: #1e293b;
            border-radius: 10px;
            border: 3px solid #020617;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #334155;
        }
    </style>
@endsection

@section('content')
<div x-data="{ 
    isMenuOpen: false,
    activeSection: 'home',
    updateActiveSection() {
        const sections = ['home', 'services', 'projects', 'skills', 'experience', 'education', 'certificates', 'contact'];
        let current = 'home';
        for (let section of sections) {
            const el = document.getElementById(section);
            if (el && window.scrollY >= (el.offsetTop - window.innerHeight / 3)) {
                current = section;
            }
        }
        this.activeSection = current;
    }
}" @scroll.window.throttle.50ms="updateActiveSection()" x-init="updateActiveSection()">

    <!-- Desktop Navigation -->
    <nav class="fixed top-6 left-1/2 -translate-x-1/2 z-50 hidden md:flex justify-center pointer-events-none">
        <div class="bg-slate-900/40 backdrop-blur-md border border-slate-800/50 px-4 py-2 rounded-full flex items-center gap-1 shadow-2xl pointer-events-auto">
            <a href="#home" @click="activeSection = 'home'" :class="activeSection === 'home' ? 'bg-sky-500 text-white' : 'text-slate-300 hover:text-white hover:bg-white/5'" class="px-4 py-2 text-xs font-bold tracking-widest transition-all rounded-full">Beranda</a>
            @if(!$profile || !$profile->hide_services)
            <a href="#services" @click="activeSection = 'services'" :class="activeSection === 'services' ? 'bg-sky-500 text-white' : 'text-slate-300 hover:text-white hover:bg-white/5'" class="px-4 py-2 text-xs font-bold tracking-widest transition-all rounded-full">Jasa</a>
            @endif
            <a href="#projects" @click="activeSection = 'projects'" :class="activeSection === 'projects' ? 'bg-sky-500 text-white' : 'text-slate-300 hover:text-white hover:bg-white/5'" class="px-4 py-2 text-xs font-bold tracking-widest transition-all rounded-full">Proyek</a>
            <a href="#skills" @click="activeSection = 'skills'" :class="activeSection === 'skills' ? 'bg-sky-500 text-white' : 'text-slate-300 hover:text-white hover:bg-white/5'" class="px-4 py-2 text-xs font-bold tracking-widest transition-all rounded-full">Keahlian</a>
            <a href="#experience" @click="activeSection = 'experience'" :class="activeSection === 'experience' ? 'bg-sky-500 text-white' : 'text-slate-300 hover:text-white hover:bg-white/5'" class="px-4 py-2 text-xs font-bold tracking-widest transition-all rounded-full">Pengalaman</a>
            <a href="#education" @click="activeSection = 'education'" :class="activeSection === 'education' ? 'bg-sky-500 text-white' : 'text-slate-300 hover:text-white hover:bg-white/5'" class="px-4 py-2 text-xs font-bold tracking-widest transition-all rounded-full">Edukasi</a>
            <a href="#certificates" @click="activeSection = 'certificates'" :class="activeSection === 'certificates' ? 'bg-sky-500 text-white' : 'text-slate-300 hover:text-white hover:bg-white/5'" class="px-4 py-2 text-xs font-bold tracking-widest transition-all rounded-full">Sertifikat</a>
            <a href="#contact" @click="activeSection = 'contact'" :class="activeSection === 'contact' ? 'bg-sky-500 text-white' : 'text-slate-300 hover:text-white hover:bg-white/5'" class="px-4 py-2 text-xs font-bold tracking-widest transition-all rounded-full">Kontak</a>
        </div>
    </nav>

    @auth
    <div class="fixed top-6 right-6 z-50 hidden md:flex">
        <a href="{{ url('/admin') }}" class="bg-sky-500/10 backdrop-blur-md border border-sky-500/50 px-6 py-2.5 rounded-full text-xs font-bold tracking-widest text-sky-400 hover:text-white hover:bg-sky-500 hover:border-sky-400 transition-all shadow-[0_0_20px_rgba(14,165,233,0.3)] hover:shadow-[0_0_30px_rgba(14,165,233,0.6)] pointer-events-auto">
            Admin
        </a>
    </div>
    @endauth

    <!-- Mobile Menu Toggle -->
    <div class="fixed top-6 right-6 z-[60] md:hidden">
        <button @click="isMenuOpen = !isMenuOpen" aria-label="Toggle Menu" class="w-9 h-9 flex flex-col items-center justify-center gap-1 bg-slate-900/90 backdrop-blur-md border border-slate-800 rounded-lg shadow-2xl transition-all active:scale-95">
            <span class="w-4 h-0.5 bg-white transition-all duration-300" :class="isMenuOpen ? 'rotate-45 translate-y-1.5' : ''"></span>
            <span class="w-4 h-0.5 bg-white transition-all duration-300" :class="isMenuOpen ? 'opacity-0' : ''"></span>
            <span class="w-4 h-0.5 bg-white transition-all duration-300" :class="isMenuOpen ? '-rotate-45 -translate-y-1.5' : ''"></span>
        </button>
    </div>

    <!-- Mobile Menu Overlay -->
    <div x-show="isMenuOpen" x-transition.opacity.duration.300ms class="fixed inset-0 z-[55] bg-slate-950/95 backdrop-blur-2xl md:hidden flex flex-col overflow-y-auto" style="display: none;">
        <div class="flex flex-col items-center justify-start flex-1 gap-6 pb-20 pt-24">
            <a @click="isMenuOpen = false; activeSection = 'home'" href="#home" :class="activeSection === 'home' ? 'text-sky-400' : 'text-slate-300 hover:text-white'" class="text-2xl font-black tracking-tighter transition-all">Beranda</a>
            @if(!$profile || !$profile->hide_services)
            <a @click="isMenuOpen = false; activeSection = 'services'" href="#services" :class="activeSection === 'services' ? 'text-sky-400' : 'text-slate-300 hover:text-white'" class="text-2xl font-black tracking-tighter transition-all">Jasa</a>
            @endif
            <a @click="isMenuOpen = false; activeSection = 'projects'" href="#projects" :class="activeSection === 'projects' ? 'text-sky-400' : 'text-slate-300 hover:text-white'" class="text-2xl font-black tracking-tighter transition-all">Proyek</a>
            <a @click="isMenuOpen = false; activeSection = 'skills'" href="#skills" :class="activeSection === 'skills' ? 'text-sky-400' : 'text-slate-300 hover:text-white'" class="text-2xl font-black tracking-tighter transition-all">Keahlian</a>
            <a @click="isMenuOpen = false; activeSection = 'experience'" href="#experience" :class="activeSection === 'experience' ? 'text-sky-400' : 'text-slate-300 hover:text-white'" class="text-2xl font-black tracking-tighter transition-all">Pengalaman</a>
            <a @click="isMenuOpen = false; activeSection = 'education'" href="#education" :class="activeSection === 'education' ? 'text-sky-400' : 'text-slate-300 hover:text-white'" class="text-2xl font-black tracking-tighter transition-all">Edukasi</a>
            <a @click="isMenuOpen = false; activeSection = 'certificates'" href="#certificates" :class="activeSection === 'certificates' ? 'text-sky-400' : 'text-slate-300 hover:text-white'" class="text-2xl font-black tracking-tighter transition-all">Sertifikat</a>
            <a @click="isMenuOpen = false; activeSection = 'contact'" href="#contact" :class="activeSection === 'contact' ? 'text-sky-400' : 'text-slate-300 hover:text-white'" class="text-2xl font-black tracking-tighter transition-all">Kontak</a>
            @auth
            <div class="w-12 h-px bg-slate-800 mt-4"></div>
            <a href="{{ url('/admin') }}" class="text-2xl font-black tracking-tighter text-sky-500 hover:text-white transition-all mt-4">Admin</a>
            @endauth
        </div>
    </div>

    <!-- Hero Section -->
    <section id="home" class="relative min-h-screen flex items-center justify-center pt-32 pb-12 px-6 lg:px-24">
        <div class="container mx-auto flex flex-col lg:grid lg:grid-cols-[1.8fr_1fr] gap-12 lg:gap-16 items-center">
            
            <!-- Info (Text) - Priority on Mobile -->
            <div class="order-1 lg:order-1 text-center lg:text-left space-y-6 lg:space-y-8">
                <div>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold mb-4 text-white reveal">
                        {{ $profile->full_name ?? 'Your Name' }}
                    </h1>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-sky-500 reveal" data-delay="100">
                        {{ $profile->job_title ?? 'Gelar Profesional' }}
                    </h2>
                </div>

                <!-- Profile Photo - Integrated in Mobile Flow -->
                <div class="lg:hidden flex justify-center py-4">
                    <div class="relative group reveal" data-delay="100">
                        <!-- Premium Outer Glow -->
                        <div class="absolute -inset-4 bg-gradient-to-tr from-sky-500/20 to-indigo-500/20 blur-2xl rounded-full opacity-60"></div>
                        
                        <!-- Floating Decorative Badges -->
                        <div class="absolute -top-2 -right-2 z-10 w-10 h-10 bg-slate-900 border border-slate-700 rounded-xl flex items-center justify-center shadow-xl animate-bounce" style="animation-duration: 3s;">
                            <span class="text-lg">🚀</span>
                        </div>
                        
                        <!-- Frame with Gradient Border -->
                        <div class="relative w-56 h-56 bg-slate-900 p-1.5 rounded-[2.5rem] shadow-2xl overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-tr from-sky-500 via-indigo-500/20 to-sky-500 opacity-40 group-hover:opacity-100 transition-opacity duration-700"></div>
                            
                            <div class="relative w-full h-full overflow-hidden rounded-[2.2rem] bg-slate-950">
                                @if($profile && $profile->avatar)
                                    <img src="/storage/{{ $profile->avatar }}" alt="Foto Profil {{ $profile->full_name ?? 'Portfolio' }} - {{ $profile->job_title ?? 'Expert' }}" class="w-full h-full object-cover group-hover:scale-110 transition-all duration-1000" fetchpriority="high" loading="eager" />
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-6xl opacity-20">👤</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-6">
                    <p class="text-base sm:text-lg lg:text-xl text-slate-200 leading-[1.8] reveal max-w-2xl mx-auto lg:mx-0 text-left" data-delay="200">
                        {{ $profile->description ?? 'Membangun solusi digital inovatif dengan fokus pada teknologi modern dan pengalaman pengguna yang luar biasa.' }}
                    </p>

                    <div class="flex flex-wrap items-center gap-x-6 gap-y-4 justify-center lg:justify-start reveal" data-delay="250">
                        <div class="flex items-center gap-2 text-slate-200">
                            <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="font-bold text-sm">{{ $profile->city ?? '' }}, {{ $profile->province ?? '' }}</span>
                        </div>
                        
                        <div class="flex items-center gap-2 text-sky-500 text-xs font-bold uppercase tracking-widest bg-sky-500/10 px-4 py-2 rounded-full border border-sky-500/20">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-500 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-sky-500"></span>
                            </span>
                            Terbuka untuk pekerjaan
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-8 items-center lg:items-start reveal" data-delay="300">
                    <!-- Social Links (Mobile) -->
                    <div class="flex gap-4 lg:hidden">
                        @if($profile && $profile->links)
                            @foreach($profile->links as $link)
                                <a href="{{ $link['link'] }}" target="_blank" rel="noopener noreferrer" class="relative w-12 h-12 rounded-full bg-slate-900/50 backdrop-blur-md border border-slate-800 flex items-center justify-center text-slate-200 hover:text-sky-400 hover:border-sky-500/50 transition-all group/social shadow-xl">
                                    <div class="absolute inset-0 bg-sky-500/10 rounded-full opacity-0 group-hover/social:opacity-100 blur-md transition-opacity"></div>
                                    <div class="relative z-10 group-hover/social:scale-110 transition-transform">
                                        {!! $getIcon($link['title'], $icons) !!}
                                    </div>
                                </a>
                            @endforeach
                        @endif
                    </div>

                    <div class="flex flex-wrap items-center gap-4 justify-center lg:justify-start">
                        @if($cv_exists)
                            <a href="/storage/{{ $profile->cv_path }}" target="_blank" class="flex items-center gap-3 bg-white text-slate-950 px-8 py-4 rounded-2xl font-black hover:bg-sky-500 hover:text-white hover:scale-105 active:scale-95 transition-all shadow-xl group">
                                <span>Unduh CV</span>
                                <svg class="w-5 h-5 group-hover:translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            </a>
                        @endif

                        @if($profile && $profile->phone)
                            <a href="https://wa.me/{{ $formattedPhone }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 bg-slate-900 border border-slate-800 text-white px-8 py-4 rounded-2xl font-black hover:border-[#25D366] hover:bg-[#25D366] hover:text-white hover:shadow-[0_0_20px_rgba(37,211,102,0.6)] hover:scale-105 active:scale-95 transition-all duration-300 shadow-xl group">
                                <svg class="w-6 h-6 fill-[#25D366] group-hover:fill-white transition-colors duration-300" viewBox="0 0 24 24">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.588-5.946 0-6.556 5.332-11.888 11.888-11.888 3.176 0 6.161 1.237 8.404 3.48s3.481 5.229 3.481 8.406c0 6.555-5.332 11.887-11.887 11.887-2.01 0-3.987-.512-5.744-1.488l-6.141 1.712zm6.352-3.804c1.644.975 3.266 1.489 4.981 1.489 5.399 0 9.794-4.396 9.794-9.795 0-5.398-4.396-9.793-9.794-9.793-2.615 0-5.074 1.018-6.921 2.865s-2.864 4.305-2.864 6.92c0 1.761.533 3.436 1.543 4.887l-1.011 3.693 3.791-1.055c1.479.805 3.13 1.258 4.793 1.258zm11.314-7.462c-.302-.151-1.782-.879-2.059-.979-.277-.101-.48-.151-.68.151-.2.302-.779.979-.955 1.181-.177.201-.353.226-.654.076-.301-.151-1.272-.469-2.421-1.494-.894-.797-1.497-1.782-1.672-2.083-.177-.302-.019-.465.132-.615.136-.134.302-.352.453-.529.151-.176.201-.302.302-.503.101-.201.05-.378-.026-.529-.076-.151-.68-1.637-.931-2.242-.244-.589-.493-.509-.68-.518-.176-.008-.378-.01-.58-.01-.201 0-.529.076-.805.378-.277.301-1.056 1.031-1.056 2.515 0 1.484 1.08 2.919 1.231 3.12.151.201 2.126 3.246 5.148 4.549.719.31 1.28.496 1.718.636.721.23 1.378.197 1.896.12.577-.085 1.782-.729 2.034-1.433.251-.704.251-1.307.176-1.433-.076-.126-.277-.202-.579-.353z"/>
                                </svg>
                                <span>Hubungi</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Desktop Profile & HUD -->
            <div class="hidden lg:order-2 lg:flex flex-col items-center lg:items-end gap-14">
                <div class="relative group reveal" data-delay="100">
                    <div class="absolute -inset-16 bg-sky-500/5 blur-[100px] rounded-full animate-pulse"></div>
                    
                    <div class="absolute -top-6 -left-6 w-16 h-16 border-t-2 border-l-2 border-sky-500/40 rounded-tl-2xl group-hover:scale-105 transition-transform duration-700"></div>
                    <div class="absolute -bottom-6 -right-6 w-16 h-16 border-b-2 border-r-2 border-sky-500/40 rounded-br-2xl group-hover:scale-105 transition-transform duration-700"></div>
                    
                    <div class="absolute -top-6 -right-2 w-1 h-1 bg-sky-500/50 rounded-full"></div>
                    <div class="absolute -bottom-2 -left-6 w-1 h-1 bg-sky-500/50 rounded-full"></div>
                    
                    <div class="relative w-80 h-80 bg-slate-900 border border-slate-800 p-4 rounded-[3.5rem] group-hover:border-sky-500/50 transition-all duration-700 shadow-2xl">
                        <div class="absolute inset-0 bg-gradient-to-tr from-sky-500/10 via-transparent to-indigo-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                        
                        <div class="relative w-full h-full overflow-hidden rounded-[3rem] bg-slate-950 shadow-inner">
                            @if($profile && $profile->avatar)
                                <img src="/storage/{{ $profile->avatar }}" alt="Foto Profil {{ $profile->full_name ?? 'Portfolio' }} - {{ $profile->job_title ?? 'Expert' }}" class="w-full h-full object-cover group-hover:scale-110 transition-all duration-1000" fetchpriority="high" loading="eager" />
                            @else
                                <div class="w-full h-full flex items-center justify-center text-8xl opacity-20">👤</div>
                            @endif
                            
                            
                        </div>
                    </div>
                    

                </div>
                
                <!-- Desktop Social Links -->
                <div class="flex gap-6 reveal" data-delay="200">
                    @if($profile && $profile->links)
                        @foreach($profile->links as $link)
                            <a href="{{ $link['link'] }}" target="_blank" rel="noopener noreferrer" class="relative w-14 h-14 rounded-full bg-slate-900/40 backdrop-blur-xl border border-slate-800 flex items-center justify-center text-slate-200 hover:text-sky-400 hover:border-sky-500/50 transition-all group/social shadow-2xl overflow-hidden">
                                <div class="absolute inset-0 bg-sky-500/10 opacity-0 group-hover/social:opacity-100 transition-opacity"></div>
                                <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-8 h-1 bg-sky-500 rounded-full blur-sm opacity-0 group-hover/social:opacity-100 transition-all duration-500"></div>
                                <div class="relative z-10 group-hover/social:-translate-y-1 transition-transform duration-300">
                                    {!! $getIcon($link['title'], $icons) !!}
                                </div>
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>

        </div>
        
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 animate-bounce opacity-20 hidden lg:block">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
        </div>
    </section>

    @if(!$profile || !$profile->hide_services)
    <!-- Jasa Section -->
    <section id="services" class="py-32 relative">
        <div class="container mx-auto px-6">
            <div class="text-center mb-20 reveal">
                <h2 class="text-4xl lg:text-6xl font-bold tracking-tight mb-4">Melayani <span class="text-sky-500">Kebutuhan Digital</span></h2>
                <div class="w-24 h-1.5 bg-sky-500 mx-auto rounded-full shadow-[0_0_20px_rgba(14,165,233,0.5)]"></div>
                <p class="mt-8 text-slate-200 max-w-2xl mx-auto">Transformasi kebutuhan digital menjadi solusi yang efisien, mudah digunakan, dan handal</p>
            </div>

            <div class="max-w-6xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                    @foreach($services as $i => $service)
                        <div class="group relative bg-slate-900 border border-slate-800 p-6 lg:p-8 rounded-[2rem] transition-all duration-500 hover:border-sky-500/30 shadow-xl overflow-hidden flex flex-col reveal" data-delay="{{ $i * 100 }}">
                            <div>
                                <h3 class="text-xl lg:text-2xl font-bold text-white group-hover:text-sky-400 transition-colors tracking-tighter">
                                    {{ $service->title }}
                                </h3>
                                <div class="h-1 w-10 bg-sky-500/20 rounded-full group-hover:w-20 group-hover:bg-sky-500 transition-all duration-500"></div>
                            </div>

                            <div>
                                <p class="text-lg lg:text-xl font-black text-sky-400 tracking-tight">
                                    {{ $service->price_range }}
                                </p>
                            </div>

                            <div class="flex-1">
                                <p class="text-sm text-slate-200 leading-relaxed font-light group-hover:text-slate-300 transition-colors whitespace-pre-line text-justify">
                                    {{ $service->description }}
                                </p>
                            </div>

                            <div class="mt-auto relative z-20">
                                <a href="https://wa.me/{{ $formattedPhone }}?text={{ urlencode('Halo, saya mau konsultasi ' . $service->title) }}" target="_blank" rel="noopener noreferrer" class="w-full flex items-center justify-center gap-2 bg-slate-900 border border-[#25D366]/30 text-[#25D366] px-5 py-3 rounded-xl font-bold transition-all duration-300 group/btn hover:bg-[#25D366] hover:text-white hover:border-[#25D366] hover:shadow-[0_0_20px_rgba(37,211,102,0.6)] active:scale-95">
                                    <svg class="w-5 h-5 fill-current group-hover/btn:scale-110 transition-transform" viewBox="0 0 24 24">
                                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.588-5.946 0-6.556 5.332-11.888 11.888-11.888 3.176 0 6.161 1.237 8.404 3.48s3.481 5.229 3.481 8.406c0 6.555-5.332 11.887-11.887 11.887-2.01 0-3.987-.512-5.744-1.488l-6.141 1.712zm6.352-3.804c1.644.975 3.266 1.489 4.981 1.489 5.399 0 9.794-4.396 9.794-9.795 0-5.398-4.396-9.793-9.794-9.793-2.615 0-5.074 1.018-6.921 2.865s-2.864 4.305-2.864 6.92c0 1.761.533 3.436 1.543 4.887l-1.011 3.693 3.791-1.055c1.479.805 3.13 1.258 4.793 1.258zm11.314-7.462c-.302-.151-1.782-.879-2.059-.979-.277-.101-.48-.151-.68.151-.2.302-.779.979-.955 1.181-.177.201-.353.226-.654.076-.301-.151-1.272-.469-2.421-1.494-.894-.797-1.497-1.782-1.672-2.083-.177-.302-.019-.465.132-.615.136-.134.302-.352.453-.529.151-.176.201-.302.302-.503.101-.201.05-.378-.026-.529-.076-.151-.68-1.637-.931-2.242-.244-.589-.493-.509-.68-.518-.176-.008-.378-.01-.58-.01-.201 0-.529.076-.805.378-.277.301-1.056 1.031-1.056 2.515 0 1.484 1.08 2.919 1.231 3.12.151.201 2.126 3.246 5.148 4.549.719.31 1.28.496 1.718.636.721.23 1.378.197 1.896.12.577-.085 1.782-.729 2.034-1.433.251-.704.251-1.307.176-1.433-.076-.126-.277-.202-.579-.353z"/>
                                    </svg>
                                    Konsultasi Gratis
                                </a>
                            </div>

                            <div class="absolute -right-20 -top-20 w-64 h-64 bg-sky-500/5 blur-[100px] rounded-full pointer-events-none group-hover:bg-sky-500/10 transition-all duration-1000"></div>
                            <div class="absolute inset-0 bg-gradient-to-br from-transparent via-transparent to-sky-500/[0.02] opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Projek Section -->
    <section id="projects" class="py-32 bg-slate-950/50 relative overflow-hidden">
        <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-sky-600/5 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="container mx-auto px-6">
            <div class="text-center mb-24 relative z-10 reveal">
                <div class="flex items-center justify-center gap-3 mb-6">
                    <div class="w-12 h-[1px] bg-sky-500/50"></div>
                    <span class="text-sky-500 text-xs font-black uppercase tracking-[0.3em]">Portofolio Expo</span>
                    <div class="w-12 h-[1px] bg-sky-500/50"></div>
                </div>
                <h2 class="text-5xl lg:text-7xl font-bold tracking-tighter mb-6 text-white">{{ count($projects) }} Solusi <span class="text-sky-500">Telah Dibuat</span></h2>
                <div class="w-32 h-1.5 bg-sky-500 mx-auto rounded-full shadow-[0_0_25px_rgba(14,165,233,0.6)]"></div>
                
                <!-- AlpineJS Search and Pagination -->
                <script>
                    document.addEventListener('alpine:init', () => {
                        Alpine.data('projectsData', () => ({
                            searchQuery: '',
                            currentPage: 1,
                            perPage: 6,
                            projects: {{ Js::from($projects) }},
                            get filteredProjects() {
                                let q = this.searchQuery.toLowerCase();
                                return this.projects.filter(p => 
                                    (p.title && p.title.toLowerCase().includes(q))
                                );
                            },
                            get paginatedProjects() {
                                let start = (this.currentPage - 1) * this.perPage;
                                return this.filteredProjects.slice(start, start + this.perPage);
                            },
                            get totalPages() {
                                return Math.ceil(this.filteredProjects.length / this.perPage);
                            },
                            get pages() {
                                let pages = [];
                                for (let i = 1; i <= this.totalPages; i++) {
                                    pages.push(i);
                                }
                                return pages;
                            },
                            goToPage(page) {
                                if (page >= 1 && page <= this.totalPages) {
                                    this.currentPage = page;
                                    document.getElementById('projects-section').scrollIntoView({ behavior: 'smooth' });
                                }
                            }
                        }));
                    });
                </script>
                <div id="projects-section" class="w-full" x-data="projectsData()" x-init="$watch('searchQuery', () => currentPage = 1)">
                
                    <!-- Search Bar -->
                    <div class="flex justify-center mt-12 mb-16">
                        <div class="relative w-full max-w-2xl reveal">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-6 pointer-events-none">
                                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" x-model="searchQuery" class="w-full bg-[#0f172a] border border-slate-700 text-slate-200 rounded-2xl py-4 pl-16 pr-6 focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/50 transition-all font-mono shadow-xl text-base" placeholder="Cari solusi atau proyek..." />
                        </div>
                    </div>

                    <!-- Projects Grid -->
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16 relative z-10 max-w-5xl mx-auto text-left">
                        <template x-for="(project, index) in paginatedProjects" :key="project.id">
                            <a :href="'/project/' + project.slug" class="group relative flex flex-col bg-slate-900 border border-slate-800 rounded-3xl hover:border-sky-500/50 transition-all duration-500 shadow-xl overflow-hidden">
                                <div class="relative aspect-video overflow-hidden">
                                    <template x-if="project.thumbnail">
                                        <img :src="'/storage/' + project.thumbnail" :alt="project.title" class="w-full h-full object-cover group-hover:scale-110 transition-all duration-700" loading="lazy" />
                                    </template>
                                    <template x-if="!project.thumbnail">
                                        <div class="w-full h-full bg-slate-800 flex items-center justify-center">
                                            <span class="text-4xl opacity-10">📦</span>
                                        </div>
                                    </template>
                                    
                                    <div class="absolute top-4 left-4 z-10">
                                        <div class="bg-slate-950/80 backdrop-blur-md border border-white/10 px-3 py-1.5 rounded-xl flex flex-col items-center">
                                            <span class="text-[10px] text-sky-400 font-bold uppercase tracking-widest" x-text="project.month || 'Jan'"></span>
                                            <span class="text-sm text-white font-bold" x-text="project.year || '2024'"></span>
                                        </div>
                                    </div>

                                    <template x-if="project.is_opensource">
                                        <div class="absolute top-4 right-4 z-10">
                                            <div class="bg-sky-500 text-white px-3 py-1.5 rounded-xl flex items-center gap-2 shadow-lg shadow-sky-500/20">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                                                <span class="text-[10px] font-black uppercase tracking-wider">Open Source</span>
                                            </div>
                                        </div>
                                    </template>

                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                </div>

                                <div class="p-6 lg:p-8 flex-1 flex flex-col">
                                    <h3 class="text-xl lg:text-2xl font-bold mb-3 text-white group-hover:text-sky-400 transition-colors tracking-tight" x-text="project.title"></h3>
                                    
                                    <div class="flex items-center gap-2 text-sky-500 font-bold text-sm uppercase tracking-wider group-hover:gap-4 transition-all group-hover:text-sky-400 mt-auto">
                                        <span>Lihat Detail</span>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                    </div>
                                </div>
                            </a>
                        </template>
                        
                        <!-- Empty State -->
                        <template x-if="filteredProjects.length === 0">
                            <div class="col-span-full text-center py-24 bg-slate-900/30 border border-slate-800 border-dashed rounded-[3rem]">
                                <div class="text-6xl mb-6 opacity-20">🔍</div>
                                <p class="text-slate-300 text-xl font-light">Tidak ada solusi ditemukan</p>
                            </div>
                        </template>
                    </div>

                    <!-- Pagination controls -->
                    <div x-show="totalPages > 1" style="display: none;" class="flex justify-center items-center gap-2 mb-10 relative z-10">
                        <button @click="goToPage(currentPage - 1)" :disabled="currentPage === 1" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-900 border border-slate-700 text-slate-300 hover:text-white hover:border-sky-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </button>
                        
                        <template x-for="page in pages" :key="page">
                            <button @click="goToPage(page)" 
                                class="w-10 h-10 flex items-center justify-center rounded-xl font-bold transition-all"
                                :class="currentPage === page ? 'bg-sky-500 text-white shadow-[0_0_15px_rgba(14,165,233,0.4)]' : 'bg-slate-900 border border-slate-700 text-slate-300 hover:text-white hover:border-sky-500'" 
                                x-text="page">
                            </button>
                        </template>

                        <button @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-900 border border-slate-700 text-slate-300 hover:text-white hover:border-sky-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>
                </div>
        </div>
    </section>

    <!-- Skills Section -->
    <section id="skills" class="py-32 relative">
        <div class="container mx-auto px-6">
            <div class="text-center mb-24 reveal">
                <h2 class="text-5xl lg:text-7xl font-bold tracking-tighter mb-4 text-white">Bidang <span class="text-sky-500">Keahlian</span></h2>
                <div class="w-24 h-1.5 bg-sky-500 mx-auto rounded-full shadow-[0_0_20px_rgba(14,165,233,0.5)]"></div>
            </div>
            
            <div class="flex flex-col gap-16 lg:gap-24 max-w-4xl mx-auto">
                @php
                    $allowedCats = ['teknologi', 'minat', 'bahasa'];
                    if ($profile && $profile->hide_hobbies) unset($allowedCats[array_search('minat', $allowedCats)]);
                    if ($profile && $profile->hide_languages) unset($allowedCats[array_search('bahasa', $allowedCats)]);
                @endphp
                
                @foreach($allowedCats as $catIndex => $cat)
                    <div class="flex flex-col reveal" data-delay="{{ $catIndex * 50 }}">
                        <div class="flex items-center gap-4 mb-10 pb-6">
                            <div class="w-14 h-14 rounded-2xl bg-sky-500/10 flex items-center justify-center text-3xl shadow-inner border border-sky-500/10">
                                {{ $cat === 'bahasa' ? '🌐' : ($cat === 'teknologi' ? '⚡' : '✨') }}
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-white tracking-tight">{{ $cat === 'teknologi' ? 'Teknologi & Alat' : ($cat === 'bahasa' ? 'Bahasa' : 'Minat & Hobi') }}</h3>
                            </div>
                        </div>
                        
                        @if(isset($skillsByCategory[$cat]))
                            <div class="flex flex-wrap gap-8 lg:gap-12 justify-center">
                                @foreach($skillsByCategory[$cat] as $i => $skill)
                                    <div class="reveal" data-delay="{{ ($catIndex * 50) + ($i * 30) }}">
                                        <div class="group flex flex-col items-center gap-3 animate-float cursor-default" style="animation-delay: {{ $i * 0.2 }}s">
                                            <div class="w-14 h-14 flex items-center justify-center">
                                                @if($skill->logo_path)
                                                    <img src="/storage/{{ $skill->logo_path }}" alt="{{ $skill->title }}" class="max-w-full max-h-full object-contain group-hover:scale-110 group-hover:drop-shadow-[0_0_15px_rgba(14,165,233,0.5)] transition-all duration-500" loading="lazy" />
                                                @else
                                                    <span class="text-4xl group-hover:scale-110 transition-transform duration-500 group-hover:drop-shadow-[0_0_15px_rgba(14,165,233,0.5)]">✨</span>
                                                @endif
                                            </div>
                                            <h4 class="text-sm font-medium text-slate-200 group-hover:text-sky-400 transition-colors duration-300 tracking-tight text-center">
                                                {{ $skill->title }}
                                            </h4>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-8 rounded-[2rem] border border-slate-800 border-dashed text-center">
                                <p class="text-slate-600 text-sm italic">Belum ada data</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Experience Section -->
    <section id="experience" class="py-32 bg-slate-950/50 relative overflow-hidden">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-sky-500/5 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="container mx-auto px-6 relative z-10">
            <h2 class="text-5xl lg:text-7xl font-bold mb-32 tracking-tighter flex flex-col items-center justify-center gap-2 text-center reveal">
                <span class="text-sky-500 text-2xl font-mono mb-4 tracking-[0.3em] uppercase opacity-50">Jenjang Karir</span>
                Pengalaman <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-indigo-500">Profesional</span>
            </h2>
            
            <div class="relative max-w-6xl mx-auto">
                <!-- Vertical Timeline Line -->
                <div class="absolute left-8 lg:left-1/2 top-0 bottom-0 w-0.5 bg-gradient-to-b from-transparent via-sky-500/50 to-transparent lg:-translate-x-1/2">
                    <div class="sticky top-1/2 w-1 h-20 bg-gradient-to-b from-sky-500 to-indigo-500 -ml-[1px] blur-[2px] opacity-70"></div>
                </div>

                <div class="space-y-24">
                    @foreach($experiences as $i => $exp)
                        <div class="relative flex flex-col lg:flex-row items-center {{ $i % 2 === 0 ? 'lg:flex-row-reverse' : '' }}">
                            <!-- Timeline Dot -->
                            <div class="absolute left-8 lg:left-1/2 top-0 w-6 h-6 -translate-x-1/2 z-20 flex items-center justify-center">
                                <div class="w-full h-full rounded-full bg-slate-950 border border-slate-800 group-hover:border-sky-500 transition-colors relative">
                                    <div class="absolute inset-1 rounded-full bg-sky-500 shadow-[0_0_15px_rgba(14,165,233,0.5)]"></div>
                                </div>
                            </div>

                            <div class="w-full lg:w-[45%] pl-20 lg:pl-0">
                                <div class="group relative p-8 rounded-[2.5rem] bg-slate-900/50 backdrop-blur-sm border border-white/10 hover:border-sky-400 transition-all duration-500 hover:-translate-y-2 hover:shadow-[0_10px_40px_-15px_rgba(56,189,248,0.4)] reveal">
                                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-sky-500/10 border border-sky-500/20 text-sky-400 text-xs font-black uppercase tracking-widest mb-6">
                                        <span class="relative flex h-2 w-2">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-sky-500"></span>
                                        </span>
                                        {{ $exp->start_date }} — {{ $exp->end_date ?: 'Sekarang' }}
                                    </div>

                                    <h3 class="text-3xl font-bold mb-3 text-white group-hover:text-sky-400 transition-colors leading-tight">{{ $exp->position }}</h3>
                                    
                                    <div class="flex flex-wrap items-center gap-3 text-slate-200">
                                        <div class="flex items-center gap-2 px-3 py-1 rounded-lg bg-slate-950 border border-slate-800">
                                            <span class="text-white font-bold">{{ $exp->company }}</span>
                                        </div>
                                        <span class="w-1 h-1 rounded-full bg-slate-700"></span>
                                        <span class="text-sm font-medium text-slate-300 italic">{{ $exp->location_text }}</span>
                                        @if($exp->status)
                                            <span class="px-2 py-0.5 rounded text-[10px] font-black bg-slate-800 text-slate-200 uppercase tracking-tighter border border-slate-700">{{ $exp->status }}</span>
                                        @endif
                                    </div>


                                    <div class="absolute bottom-0 left-10 right-10 h-px bg-gradient-to-r from-transparent via-sky-500/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                                </div>
                            </div>

                            <!-- Spacer -->
                            <div class="hidden lg:block lg:w-[45%]"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Education Section -->
    <section id="education" class="py-32 relative overflow-hidden">
        <div class="absolute bottom-0 left-0 w-full h-1/2 bg-gradient-to-t from-indigo-500/5 to-transparent pointer-events-none"></div>

        <div class="container mx-auto px-6 relative z-10">
            <h2 class="text-5xl lg:text-7xl font-bold mb-32 tracking-tighter flex flex-col items-center justify-center gap-2 text-center reveal">
                Perjalanan <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-500">Pendidikan</span>
            </h2>
            
            <div class="relative max-w-6xl mx-auto">
                <div class="absolute left-8 lg:left-1/2 top-0 bottom-0 w-0.5 bg-gradient-to-b from-transparent via-indigo-500/50 to-transparent lg:-translate-x-1/2">
                    <div class="absolute inset-0 bg-gradient-to-b from-indigo-500/40 via-transparent to-purple-500/40"></div>
                </div>

                <div class="space-y-24">
                    @foreach($education as $i => $edu)
                        <div class="relative flex flex-col lg:flex-row items-center {{ $i % 2 !== 0 ? 'lg:flex-row-reverse' : '' }}">
                            <!-- Timeline Dot -->
                            <div class="absolute left-8 lg:left-1/2 top-0 w-6 h-6 -translate-x-1/2 z-20 flex items-center justify-center">
                                <div class="w-full h-full rounded-full bg-slate-950 border border-slate-800 group-hover:border-indigo-500 transition-colors relative">
                                    <div class="absolute inset-1 rounded-full bg-indigo-500 shadow-[0_0_15px_rgba(99,102,241,0.5)]"></div>
                                </div>
                            </div>

                            <div class="w-full lg:w-[45%] pl-20 lg:pl-0">
                                <div class="group relative p-8 rounded-[2.5rem] bg-slate-900/30 backdrop-blur-sm border border-white/10 hover:border-indigo-400 transition-all duration-500 hover:-translate-y-2 hover:shadow-[0_10px_40px_-15px_rgba(129,140,248,0.4)] reveal">
                                    <div class="inline-block px-4 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-xs font-black mb-6">
                                        @if($edu->is_current)
                                            📖 Dalam masa studi
                                        @else
                                            🎓 Lulus: {{ $edu->graduation_date }}
                                        @endif
                                    </div>

                                    <h3 class="text-3xl font-black mb-3 text-white group-hover:text-indigo-400 transition-colors tracking-tight">{{ $edu->major }}</h3>
                                    
                                    <div class="flex items-center gap-3 text-slate-200 font-bold">
                                        <span class="text-slate-200">{{ $edu->institution }}</span>
                                        @if($edu->degree)
                                            <span class="w-1 h-1 rounded-full bg-slate-700"></span>
                                            <span class="text-indigo-400 font-bold text-sm tracking-wide">{{ $edu->degree }}</span>
                                        @endif
                                    </div>

                                    <div class="flex items-center justify-between mt-4">
                                        <div class="flex items-center gap-2 text-slate-300 text-sm">
                                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                            {{ $edu->location_text }}
                                        </div>
                                        
                                        @if($edu->gpa)
                                            <div class="flex items-center gap-2">
                                                <span class="text-lg lg:text-xl text-slate-300 font-bold tracking-tight">IPK</span>
                                                <span class="text-lg lg:text-xl font-bold text-indigo-400">{{ $edu->gpa }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="absolute -bottom-2 -right-2 w-20 h-20 bg-indigo-500/10 blur-2xl rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                </div>
                            </div>
                            
                            <!-- Spacer -->
                            <div class="hidden lg:block lg:w-[45%]"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Sertifikat Section -->
    <section id="certificates" class="py-32 relative overflow-hidden">
        <div class="absolute top-1/2 right-0 w-[500px] h-[500px] bg-sky-500/5 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="text-center mb-24 reveal">
                <h2 class="text-5xl lg:text-7xl font-black tracking-tighter mb-4 text-white">
                    Sertifikasi <span class="text-sky-500">Keahlian</span>
                </h2>
                <div class="w-24 h-1.5 bg-sky-500 mx-auto rounded-full shadow-[0_0_20px_rgba(14,165,233,0.5)]"></div>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-5xl mx-auto">
                @forelse($certificates as $i => $cert)
                    <div class="group relative p-10 rounded-[2rem] bg-slate-900/40 backdrop-blur-md border border-slate-800/50 hover:border-sky-500/30 transition-all duration-700 flex flex-col h-full overflow-hidden reveal" data-delay="{{ ($i % 3) * 100 }}">
                        <div class="absolute left-0 top-10 bottom-10 w-[2px] bg-sky-500/30 group-hover:bg-sky-500 transition-colors duration-700"></div>

                        <div class="flex-1 relative z-10 pl-4">
                            <h3 class="text-2xl font-black mb-2 text-white group-hover:text-sky-400 transition-colors leading-tight tracking-tight mt-2">{{ $cert->title }}</h3>
                            <p class="text-slate-200 font-bold mb-8 text-sm">{{ $cert->issuer }}</p>
                            
                            <div class="flex flex-wrap gap-2">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-sky-500/5 border border-sky-500/10 text-[10px] font-black text-sky-500/80 uppercase tracking-widest">
                                    <span class="w-1.5 h-1.5 rounded-full bg-sky-500 animate-pulse"></span>
                                    {{ strtolower($cert->level) === 'dasar' ? 'Dasar' : $cert->level }}
                                </div>
                                @if($cert->category)
                                    <div class="inline-flex items-center px-3 py-1 rounded-lg bg-slate-950 border border-slate-800 text-[10px] font-black text-slate-300 uppercase tracking-widest">
                                        {{ strtolower($cert->category) === 'kursus' ? 'Kursus' : (strtolower($cert->category) === 'kompetensi' ? 'Kompetensi' : $cert->category) }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 pl-4 relative z-10 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-sky-500/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <p class="text-xs text-slate-200 font-medium tracking-tight">
                                    {{ $cert->start_date }} — {{ $cert->end_date ?: 'Seumur Hidup' }}
                                </p>
                            </div>

                            @if($cert->verification_url || $cert->credential_url)
                                <a href="{{ $cert->verification_url ?: $cert->credential_url }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-1.5 text-sky-500 hover:text-sky-400 transition-colors group/link">
                                    <span class="text-[10px] font-black uppercase tracking-wider">Verifikasi</span>
                                    <svg class="w-3.5 h-3.5 group-hover/link:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                </a>
                            @endif
                        </div>

                        <div class="absolute inset-0 bg-gradient-to-br from-sky-500/[0.03] to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-1000"></div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-900 border border-slate-800 mb-6">
                            <span class="text-3xl opacity-20">📭</span>
                        </div>
                        <p class="text-slate-300 font-medium italic">Belum ada data sertifikat yang tersedia.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <footer id="contact" class="pt-32 pb-16 bg-slate-950/80 border-t border-slate-900/50 relative overflow-hidden">
        <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-full h-px bg-gradient-to-r from-transparent via-sky-500/20 to-transparent"></div>
        <div class="absolute bottom-0 left-1/4 w-[400px] h-[400px] bg-sky-600/5 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="flex flex-col lg:flex-row justify-between gap-16 lg:gap-8 pt-20 border-t border-slate-900">
                <div class="max-w-sm space-y-8 reveal">
                    <div class="flex items-center gap-3">
                        <img src="/icon.webp" alt="Logo" class="w-12 h-12 rounded-xl shadow-lg shadow-sky-500/10" loading="lazy" />
                        <span class="text-2xl font-black tracking-tighter text-white uppercase">{{ $profile->full_name ?? 'Portfolio' }}</span>
                    </div>
                    <p class="text-slate-200 font-light leading-relaxed text-sm">
                        Berfokus pada pengembangan solusi digital yang inovatif, efisien, dan berorientasi pada hasil untuk membantu bisnis Anda tumbuh lebih cepat.
                    </p>
                    <div class="flex items-center gap-3">
                        @if($profile && $profile->links)
                            @foreach($profile->links as $link)
                                <a href="{{ $link['link'] }}" target="_blank" rel="noopener noreferrer" class="w-11 h-11 rounded-full bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-200 hover:text-sky-400 hover:border-sky-500/50 hover:bg-sky-500/5 transition-all group/social shadow-xl">
                                    <div class="group-hover/social:scale-110 transition-transform">
                                        {!! $getIcon($link['title'], $icons) !!}
                                    </div>
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="min-w-[280px] reveal" data-delay="100">
                    <h4 class="text-white font-black uppercase tracking-widest text-sm mb-8">Navigasi</h4>
                    <ul class="grid grid-cols-2 gap-y-4 gap-x-12">
                        <li><a href="#home" class="text-slate-200 hover:text-sky-400 transition-colors text-sm font-medium whitespace-nowrap text-left">Beranda</a></li>
                        @if(!$profile || !$profile->hide_services)
                        <li><a href="#services" class="text-slate-200 hover:text-sky-400 transition-colors text-sm font-medium whitespace-nowrap text-left">Jasa</a></li>
                        @endif
                        <li><a href="#projects" class="text-slate-200 hover:text-sky-400 transition-colors text-sm font-medium whitespace-nowrap text-left">Proyek</a></li>
                        <li><a href="#skills" class="text-slate-200 hover:text-sky-400 transition-colors text-sm font-medium whitespace-nowrap text-left">Keahlian</a></li>
                        <li><a href="#experience" class="text-slate-200 hover:text-sky-400 transition-colors text-sm font-medium whitespace-nowrap text-left">Pengalaman</a></li>
                        <li><a href="#education" class="text-slate-200 hover:text-sky-400 transition-colors text-sm font-medium whitespace-nowrap text-left">Edukasi</a></li>
                        <li><a href="#certificates" class="text-slate-200 hover:text-sky-400 transition-colors text-sm font-medium whitespace-nowrap text-left">Sertifikat</a></li>
                        <li><a href="#contact" class="text-slate-200 hover:text-sky-400 transition-colors text-sm font-medium whitespace-nowrap text-left">Kontak</a></li>
                    </ul>
                </div>

                <div class="min-w-[200px] reveal" data-delay="150">
                    <h4 class="text-white font-black uppercase tracking-widest text-sm mb-8">Kebijakan</h4>
                    <ul class="space-y-4">
                        <li><a href="{{ url('/terms') }}" class="text-slate-200 hover:text-sky-400 transition-colors text-sm font-medium">Syarat & Ketentuan</a></li>
                        <li><a href="{{ url('/privacy') }}" class="text-slate-200 hover:text-sky-400 transition-colors text-sm font-medium">Kebijakan Privasi</a></li>
                        <li><a href="{{ url('/refund') }}" class="text-slate-200 hover:text-sky-400 transition-colors text-sm font-medium">Kebijakan Pengembalian Dana</a></li>
                    </ul>
                </div>

                <div class="min-w-[240px] reveal" data-delay="200">
                    <h4 class="text-white font-black uppercase tracking-widest text-sm mb-8">Kontak</h4>
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-sky-500/10 flex items-center justify-center text-sky-400 border border-sky-500/20 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sky-500 text-[10px] font-semibold uppercase tracking-[0.2em] mb-1">Lokasi</p>
                                <p class="text-white text-base font-medium tracking-tight">{{ $profile->city ?? '-' }}, {{ $profile->province ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-sky-500/10 flex items-center justify-center text-sky-400 border border-sky-500/20 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sky-500 text-[10px] font-semibold uppercase tracking-[0.2em] mb-1">Telepon</p>
                                <p class="text-white text-base font-medium tracking-tight">{{ $profile->phone ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-sky-500/10 flex items-center justify-center text-sky-400 border border-sky-500/20 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sky-500 text-[10px] font-semibold uppercase tracking-[0.2em] mb-1">Email</p>
                                <p class="text-white text-base font-medium tracking-tight">{{ $profile->email ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-32 pt-8 border-t border-slate-900 flex flex-col md:flex-row justify-between items-center gap-6 reveal">
                <p class="text-slate-300 text-xs font-medium tracking-wide">
                    &copy; {{ date('Y') }} <span class="text-slate-300 font-bold">{{ $profile->full_name ?? 'Portfolio' }}</span>. Hak Cipta Dilindungi.
                </p>
            </div>
        </div>
    </footer>
</div>
@endsection