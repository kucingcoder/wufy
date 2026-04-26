<script>
    import { onMount } from 'svelte';
    import { fade, fly, slide } from 'svelte/transition';

    // Props from Inertia
    let { 
        profile, 
        education = [], 
        experiences = [], 
        projects = [], 
        skills = [], 
        services = [], 
        visionMission,
        certificates = []
    } = $props();

    let mounted = $state(false);
    let isLoading = $state(true);
    let activeSection = $state('home');
    let searchQuery = $state('');
    let currentPage = $state(1);
    let isMenuOpen = $state(false);
    const itemsPerPage = 6;

    // Derived states for projects
    let filteredProjects = $derived(
        projects.filter(p => 
            p.title.toLowerCase().includes(searchQuery.toLowerCase()) || 
            p.short_description.toLowerCase().includes(searchQuery.toLowerCase())
        )
    );

    let paginatedProjects = $derived(
        filteredProjects.slice((currentPage - 1) * itemsPerPage, currentPage * itemsPerPage)
    );

    let totalPages = $derived(Math.ceil(filteredProjects.length / itemsPerPage));

    // WhatsApp Number Normalization
    let formattedPhone = $derived(
        (profile?.phone || '').replace(/[^0-9]/g, '').replace(/^0/, '62')
    );


    onMount(() => {
        mounted = true;
        
        // Simulate initial loading sequence
        setTimeout(() => {
            isLoading = false;
        }, 1500);
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    activeSection = entry.target.id;
                }
            });
        }, { threshold: 0.3 });

        document.querySelectorAll('section[id], footer[id]').forEach((el) => {
            observer.observe(el);
        });
    });

    const scrollTo = (id) => {
        const el = document.getElementById(id);
        if (el) {
            el.scrollIntoView({ behavior: 'smooth' });
        }
    };

    // Helper to group skills by category
    const categorizedSkills = $derived(
        skills.reduce((acc, skill) => {
            const cat = skill.category || 'teknologi';
            if (!acc[cat]) acc[cat] = [];
            acc[cat].push(skill);
            return acc;
        }, {})
    );

    const getCategoryLabel = (cat) => {
        const labels = {
            'bahasa': 'Bahasa',
            'teknologi': 'Teknologi & Alat',
            'minat': 'Minat & Hobi'
        };
        return labels[cat] || cat;
    };

    // Reset pagination when searching
    $effect(() => {
        if (searchQuery) currentPage = 1;
    });

    // Icons Mapping (Simple SVG icons)
    const icons = {
        linkedin: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>',
        github: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>',
        instagram: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>',
        default: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>'
    };

    const getIcon = (title) => {
        const lower = title.toLowerCase();
        if (lower.includes('linkedin')) return icons.linkedin;
        if (lower.includes('github')) return icons.github;
        if (lower.includes('instagram')) return icons.instagram;
        return icons.default;
    };

    const getServiceIcon = (iconName) => {
        const name = iconName?.toLowerCase() || '';
        if (name.includes('desktop') || name.includes('computer')) return '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>';
        if (name.includes('mobile') || name.includes('phone')) return '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>';
        if (name.includes('paint') || name.includes('design')) return '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>';
        if (name.includes('code') || name.includes('dev')) return '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>';
        if (name.includes('server')) return '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>';
        return '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>';
    };

    // Scroll Reveal Action
    const scrollReveal = (node, options = {}) => {
        const { threshold = 0.1, rootMargin = '0px 0px -50px 0px' } = options;
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    node.classList.add('revealed');
                    observer.unobserve(node);
                }
            });
        }, { threshold, rootMargin });

        observer.observe(node);

        return {
            destroy() {
                observer.unobserve(node);
            }
        };
    };
</script>

<svelte:head>
    <title>{profile?.full_name || 'Portfolio'} - {profile?.job_title || 'Expert'}</title>
    <meta name="description" content={profile?.description || 'Portofolio profesional yang menampilkan proyek inovatif dan solusi digital kreatif.'}>
    <meta name="keywords" content="{skills?.map(s => s.name).join(', ')}, {services?.map(s => s.title).join(', ')}, portfolio, digital solutions, {profile?.full_name}">
    <meta name="author" content={profile?.full_name}>
    <link rel="icon" type="image/webp" href="/icon.webp">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://wufy.test/">
    <meta property="og:title" content="{profile?.full_name} - {profile?.job_title}">
    <meta property="og:description" content={profile?.description}>
    <meta property="og:image" content={profile?.avatar ? `/storage/${profile.avatar}` : '/icon.webp'}>

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://wufy.test/">
    <meta property="twitter:title" content="{profile?.full_name} - {profile?.job_title}">
    <meta property="twitter:description" content={profile?.description}>
    <meta property="twitter:image" content={profile?.avatar ? `/storage/${profile.avatar}` : '/icon.webp'}>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Structured Data (JSON-LD) -->
    <script type="application/ld+json">
        {JSON.stringify({
            "@context": "https://schema.org",
            "@type": "Person",
            "name": profile?.full_name,
            "jobTitle": profile?.job_title,
            "url": "https://wufy.test/",
            "image": profile?.avatar ? `https://wufy.test/storage/${profile.avatar}` : "https://wufy.test/icon.webp",
            "sameAs": profile?.links?.map(l => l.link) || [],
            "description": profile?.description,
            "address": {
                "@type": "PostalAddress",
                "addressLocality": profile?.city,
                "addressRegion": profile?.province,
                "addressCountry": "ID"
            }
        })}
    </script>
</svelte:head>

<!-- Futuristic Preloader -->
{#if isLoading}
    <div 
        transition:fade={{ duration: 800 }}
        class="fixed inset-0 z-[100] bg-[#020617] flex flex-col items-center justify-center overflow-hidden"
    >
        <!-- Background HUD Elements -->
        <div class="absolute inset-0 opacity-20 pointer-events-none">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-sky-500/10 rounded-full blur-[120px] animate-pulse"></div>
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgdmlld0JveD0iMCAwIDQwIDQwIj48ZyBmaWxsPSJub25lIiBzdHJva2U9InJnYmEoMTQ4LCAxNjMsIDE4NCwgMC4wNSkiIHN0cm9rZS13aWR0aD0iMC41Ij48cGF0aCBkPSJNMCA0MGg0MFYwSDB6Ii8+PC9nPjwvc3ZnPg==')] bg-repeat opacity-20"></div>
        </div>

        <!-- Central Animation -->
        <div class="relative flex flex-col items-center">
            <!-- Rotating Rings -->
            <div class="relative w-32 h-32 mb-12">
                <div class="absolute inset-0 border-2 border-sky-500/10 rounded-full"></div>
                <div class="absolute inset-0 border-t-2 border-sky-500 rounded-full animate-spin"></div>
                <div class="absolute inset-4 border-b-2 border-indigo-500 rounded-full animate-spin-reverse opacity-50"></div>
                
                <!-- Inner Core -->
                <div class="absolute inset-10 bg-gradient-to-br from-sky-400 to-indigo-600 rounded-full shadow-[0_0_30px_rgba(14,165,233,0.5)] animate-pulse flex items-center justify-center">
                    <div class="w-2 h-2 bg-white rounded-full"></div>
                </div>
            </div>

            <!-- Loading Text -->
            <div class="text-center relative">
                <div class="text-sky-500 font-mono text-xs tracking-[0.5em] mb-3 uppercase animate-pulse">
                    System Initializing
                </div>
                <div class="h-1 w-48 bg-slate-800 rounded-full overflow-hidden relative">
                    <div class="absolute inset-y-0 left-0 bg-sky-500 rounded-full animate-progress-loading"></div>
                </div>
                
                <!-- Scanning Bar -->
                <div class="absolute -inset-x-20 -inset-y-10 border border-sky-500/5 rounded-3xl pointer-events-none">
                    <div class="absolute inset-x-0 h-[1px] bg-sky-500/20 top-0 animate-scan"></div>
                </div>
            </div>
            
            <div class="mt-8 flex gap-3">
                {#each Array(3) as _, i}
                    <div class="w-1.5 h-1.5 bg-sky-500/30 rounded-full animate-bounce" style="animation-delay: {i * 0.2}s"></div>
                {/each}
            </div>
        </div>
        
        <!-- Bottom Stats -->
        <div class="absolute bottom-12 left-1/2 -translate-x-1/2 flex items-center gap-8 text-[10px] font-mono text-slate-600 tracking-widest uppercase">
            <span>Core: Active</span>
            <span class="w-1 h-1 bg-slate-800 rounded-full"></span>
            <span>Uplink: Secure</span>
            <span class="w-1 h-1 bg-slate-800 rounded-full"></span>
            <span>Protocols: Ready</span>
        </div>
    </div>
{/if}

<div class="bg-[#020617] text-slate-100 font-['Plus_Jakarta_Sans'] selection:bg-sky-500/30 overflow-x-hidden min-h-screen">
    
    <!-- Background System -->
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
        <!-- Base Gradient -->
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,#0f172a_0%,#020617_100%)]"></div>
        
        <!-- Animated Blobs -->
        <div class="absolute top-[-10%] left-[-10%] w-[600px] h-[600px] bg-sky-600/15 blur-[120px] rounded-full animate-blob"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-indigo-600/10 blur-[120px] rounded-full animate-blob animation-delay-2000"></div>
        <div class="absolute top-[20%] right-[10%] w-[400px] h-[400px] bg-blue-500/10 blur-[100px] rounded-full animate-blob animation-delay-4000"></div>
        
        <!-- Grid Pattern -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgdmlld0JveD0iMCAwIDQwIDQwIj48ZyBmaWxsPSJub25lIiBzdHJva2U9InJnYmEoMTQ4LCAxNjMsIDE4NCwgMC4wNSkiIHN0cm9rZS13aWR0aD0iMC41Ij48cGF0aCBkPSJNMCA0MGg0MFYwSDB6Ii8+PC9nPjwvc3ZnPg==')] bg-repeat opacity-40"></div>
        

        
        <!-- Radial Mask -->
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,transparent_0%,#020617_80%)] opacity-50"></div>
    </div>

    <nav class="fixed top-6 left-1/2 -translate-x-1/2 z-50 hidden md:flex justify-center pointer-events-none">
        <div class="bg-slate-900/40 backdrop-blur-md border border-slate-800/50 px-4 py-2 rounded-full flex items-center gap-1 shadow-2xl pointer-events-auto">
            <button onclick={() => scrollTo('home')} class="px-4 py-2 text-xs font-bold tracking-widest transition-all rounded-full {activeSection === 'home' ? 'bg-sky-500 text-white shadow-lg shadow-sky-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5'}">beranda</button>
            <button onclick={() => scrollTo('vision')} class="px-4 py-2 text-xs font-bold tracking-widest transition-all rounded-full {activeSection === 'vision' ? 'bg-sky-500 text-white shadow-lg shadow-sky-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5'}">Visi/Misi</button>
            <button onclick={() => scrollTo('services')} class="px-4 py-2 text-xs font-bold tracking-widest transition-all rounded-full {activeSection === 'services' ? 'bg-sky-500 text-white shadow-lg shadow-sky-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5'}">Jasa</button>
            <button onclick={() => scrollTo('projects')} class="px-4 py-2 text-xs font-bold tracking-widest transition-all rounded-full {activeSection === 'projects' ? 'bg-sky-500 text-white shadow-lg shadow-sky-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5'}">Projek</button>
            <button onclick={() => scrollTo('skills')} class="px-4 py-2 text-xs font-bold tracking-widest transition-all rounded-full {activeSection === 'skills' ? 'bg-sky-500 text-white shadow-lg shadow-sky-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5'}">Keahlian</button>
            <button onclick={() => scrollTo('experience')} class="px-4 py-2 text-xs font-bold tracking-widest transition-all rounded-full {activeSection === 'experience' ? 'bg-sky-500 text-white shadow-lg shadow-sky-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5'}">Pengalaman</button>
            <button onclick={() => scrollTo('education')} class="px-4 py-2 text-xs font-bold tracking-widest transition-all rounded-full {activeSection === 'education' ? 'bg-sky-500 text-white shadow-lg shadow-sky-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5'}">Edukasi</button>
            <button onclick={() => scrollTo('certificates')} class="px-4 py-2 text-xs font-bold tracking-widest transition-all rounded-full {activeSection === 'certificates' ? 'bg-sky-500 text-white shadow-lg shadow-sky-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5'}">Sertifikat</button>
            <button onclick={() => scrollTo('contact')} class="px-4 py-2 text-xs font-bold tracking-widest transition-all rounded-full {activeSection === 'contact' ? 'bg-sky-500 text-white shadow-lg shadow-sky-500/20' : 'text-slate-400 hover:text-white hover:bg-white/5'}">Kontak</button>
        </div>
    </nav>

    <!-- Mobile Menu Toggle (Top Right) -->
    <div class="fixed top-6 right-6 z-[60] md:hidden">
        <button 
            onclick={() => isMenuOpen = !isMenuOpen}
            class="w-9 h-9 flex flex-col items-center justify-center gap-1 bg-slate-900/90 backdrop-blur-md border border-slate-800 rounded-lg shadow-2xl transition-all active:scale-95"
            aria-label={isMenuOpen ? "Tutup Menu" : "Buka Menu"}
        >
            <span class="w-4 h-0.5 bg-white transition-all duration-300 {isMenuOpen ? 'rotate-45 translate-y-1.5' : ''}"></span>
            <span class="w-4 h-0.5 bg-white transition-all duration-300 {isMenuOpen ? 'opacity-0' : ''}"></span>
            <span class="w-4 h-0.5 bg-white transition-all duration-300 {isMenuOpen ? '-rotate-45 -translate-y-1.5' : ''}"></span>
        </button>
    </div>

    <!-- Mobile Menu Overlay -->
    {#if isMenuOpen}
        <div 
            transition:fade={{ duration: 300 }}
            class="fixed inset-0 z-[55] bg-slate-950/95 backdrop-blur-2xl md:hidden flex flex-col"
        >
            <!-- Close Button Header -->
            <div class="flex justify-end p-8">
                <button 
                    onclick={() => isMenuOpen = false}
                    class="text-slate-400 hover:text-white transition-colors group"
                    aria-label="Tutup Menu"
                >
                    <div class="w-9 h-9 rounded-lg border border-slate-800 flex items-center justify-center group-hover:border-slate-600 bg-slate-900/50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                </button>
            </div>

            <div class="flex flex-col items-center justify-center flex-1 gap-10 pb-20">
                {#each [
                    { id: 'home', label: 'beranda' },
                    { id: 'vision', label: 'Visi/Misi' },
                    { id: 'services', label: 'Jasa' },
                    { id: 'projects', label: 'Projek' },
                    { id: 'skills', label: 'Keahlian' },
                    { id: 'experience', label: 'Pengalaman' },
                    { id: 'education', label: 'edukasi' },
                    { id: 'certificates', label: 'sertifikat' },
                    { id: 'contact', label: 'Kontak' }
                ] as item, i}
                    <button 
                        transition:fly={{ y: 30, delay: i * 50, duration: 600 }}
                        onclick={() => {
                            scrollTo(item.id);
                            isMenuOpen = false;
                        }}
                        class="text-4xl lg:text-5xl font-black tracking-tighter transition-all {activeSection === item.id ? 'text-sky-500' : 'text-slate-500 hover:text-white'}"
                    >
                        {item.label}
                    </button>
                {/each}
            </div>
        </div>
    {/if}

    <!-- Hero Section -->
    <section id="home" class="relative min-h-screen flex items-center justify-center pt-20 px-6 lg:px-24">
        <div class="container mx-auto grid lg:grid-cols-2 gap-16 items-center">
            
            <!-- Mobile: TOP / Desktop: RIGHT (Profile & HUD) -->
            <div class="order-1 lg:order-2 flex flex-col items-center lg:items-end gap-8">
                <div use:scrollReveal={{ threshold: 0.1 }} class="relative group reveal">
                    <!-- Outer Glow & HUD Markers -->
                    <div class="absolute -inset-8 bg-sky-500/10 blur-[100px] rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                    
                    <!-- Main Frame -->
                    <div class="relative w-64 h-64 lg:w-80 lg:h-80 bg-slate-900 border border-slate-800 p-3 rounded-3xl group-hover:border-sky-500/30 transition-all duration-500 shadow-2xl">
                        <!-- Inner Glow -->
                        <div class="absolute inset-0 bg-sky-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        
                        <!-- Image Container -->
                        <div class="relative w-full h-full overflow-hidden rounded-2xl bg-slate-950">
                            {#if profile?.avatar}
                                <img src="/storage/{profile.avatar}" alt={profile.full_name} class="w-full h-full object-cover group-hover:scale-105 transition-all duration-700" />
                            {:else}
                                <div class="w-full h-full flex items-center justify-center text-6xl opacity-20">👤</div>
                            {/if}
                        </div>
                    </div>
                    
                    <!-- Decorative Dots -->
                    <div class="absolute -bottom-8 left-1/2 -translate-x-1/2 flex gap-1.5">
                        <div class="w-1.5 h-1.5 bg-sky-500 rounded-full animate-pulse"></div>
                        <div class="w-1.5 h-1.5 bg-sky-500/50 rounded-full animate-pulse delay-75"></div>
                        <div class="w-1.5 h-1.5 bg-sky-500/30 rounded-full animate-pulse delay-150"></div>
                    </div>
                </div>
                
                <div use:scrollReveal={{ threshold: 0.1, delay: 200 }} class="flex gap-4 reveal">
                    {#if profile?.links}
                        {#each profile.links as link}
                            <a 
                                href={link.link} 
                                target="_blank" 
                                rel="noopener noreferrer" 
                                aria-label="Kunjungi {link.title} {profile.full_name}"
                                class="relative w-14 h-14 bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-400 hover:text-sky-400 hover:border-sky-500/50 transition-all clip-hexagon group/social overflow-hidden shadow-2xl"
                            >
                                <div class="absolute inset-0 bg-sky-500/5 opacity-0 group-hover/social:opacity-100 transition-opacity"></div>
                                {@html getIcon(link.title)}
                            </a>
                        {/each}
                    {/if}
                </div>
            </div>

            <!-- Mobile: BOTTOM / Desktop: LEFT (Info) -->
            <div class="order-2 lg:order-1 text-center lg:text-left">
                
                <h1 use:scrollReveal class="text-4xl lg:text-6xl font-black tracking-tighter leading-[0.9] mb-6 text-white reveal">
                    {profile?.full_name || 'Your Name'}
                </h1>
                
                <h2 use:scrollReveal={{ delay: 100 }} class="text-2xl lg:text-3xl font-medium text-slate-400 mb-8 font-['Space_Grotesk'] reveal">
                    {profile?.job_title || 'Gelar Profesional'}
                </h2>
                
                <p use:scrollReveal={{ delay: 200 }} class="text-lg lg:text-xl text-slate-400 max-w-xl mb-2 leading-relaxed font-light reveal">
                    {profile?.description || 'Membangun solusi digital inovatif dengan fokus pada teknologi modern dan pengalaman pengguna yang luar biasa.'}
                </p>

                <div use:scrollReveal={{ delay: 300 }} class="flex flex-col gap-6 items-center lg:items-start reveal">
                    <div class="flex flex-wrap items-center gap-x-8 gap-y-4 justify-center lg:justify-start">
                        <div class="flex items-center gap-2 text-slate-200">
                            <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="font-medium">{profile?.city}, {profile?.province}</span>
                        </div>
                        
                        <div class="h-8 w-[1px] bg-slate-800 hidden sm:block"></div>

                        <div class="flex items-center gap-2 text-sky-400 text-xs font-bold uppercase tracking-widest">
                            <span class="w-2 h-2 rounded-full bg-sky-500 animate-ping"></span>
                            Terbuka untuk pengadaan
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-4 justify-center lg:justify-start">
                        {#if profile?.cv_path}
                            <a href="/storage/{profile.cv_path}" target="_blank" class="flex items-center gap-3 bg-slate-100 text-slate-900 px-8 py-4 rounded-2xl font-bold hover:bg-white hover:scale-105 active:scale-95 transition-all shadow-xl group">
                                <span>Unduh CV</span>
                                <svg class="w-5 h-5 group-hover:translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            </a>
                        {/if}

                        {#if profile?.phone}
                            <a 
                                href="https://wa.me/{formattedPhone}" 
                                target="_blank" 
                                rel="noopener noreferrer"
                                class="flex items-center gap-3 bg-green-600 text-white px-8 py-4 rounded-2xl font-bold hover:bg-green-500 hover:scale-105 active:scale-95 transition-all shadow-xl shadow-green-600/20 group"
                            >
                                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.588-5.946 0-6.556 5.332-11.888 11.888-11.888 3.176 0 6.161 1.237 8.404 3.48s3.481 5.229 3.481 8.406c0 6.555-5.332 11.887-11.887 11.887-2.01 0-3.987-.512-5.744-1.488l-6.141 1.712zm6.352-3.804c1.644.975 3.266 1.489 4.981 1.489 5.399 0 9.794-4.396 9.794-9.795 0-5.398-4.396-9.793-9.794-9.793-2.615 0-5.074 1.018-6.921 2.865s-2.864 4.305-2.864 6.92c0 1.761.533 3.436 1.543 4.887l-1.011 3.693 3.791-1.055c1.479.805 3.13 1.258 4.793 1.258zm11.314-7.462c-.302-.151-1.782-.879-2.059-.979-.277-.101-.48-.151-.68.151-.2.302-.779.979-.955 1.181-.177.201-.353.226-.654.076-.301-.151-1.272-.469-2.421-1.494-.894-.797-1.497-1.782-1.672-2.083-.177-.302-.019-.465.132-.615.136-.134.302-.352.453-.529.151-.176.201-.302.302-.503.101-.201.05-.378-.026-.529-.076-.151-.68-1.637-.931-2.242-.244-.589-.493-.509-.68-.518-.176-.008-.378-.01-.58-.01-.201 0-.529.076-.805.378-.277.301-1.056 1.031-1.056 2.515 0 1.484 1.08 2.919 1.231 3.12.151.201 2.126 3.246 5.148 4.549.719.31 1.28.496 1.718.636.721.23 1.378.197 1.896.12.577-.085 1.782-.729 2.034-1.433.251-.704.251-1.307.176-1.433-.076-.126-.277-.202-.579-.353z"/>
                                </svg>
                                <span>Hubungi</span>
                            </a>
                        {/if}
                    </div>
                </div>
            </div>

        </div>

        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 animate-bounce opacity-20 hidden lg:block">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
        </div>
    </section>

    <!-- Vision Mission Section -->
    <section id="vision" class="py-32 bg-slate-900/30">
        <div class="container mx-auto px-6">
            <!-- Section Title -->
            <div use:scrollReveal class="text-center mb-20 reveal">
                <h2 class="text-4xl lg:text-6xl font-black tracking-tight mb-4">Visi & <span class="text-sky-500">Misi</span></h2>
                <div class="w-24 h-1.5 bg-sky-500 mx-auto rounded-full shadow-[0_0_20px_rgba(14,165,233,0.5)]"></div>
            </div>

            <!-- Vision Card -->
            <div use:scrollReveal class="max-w-5xl mx-auto mb-24 relative group reveal">
                <div class="relative p-10 lg:p-20 bg-slate-900 border border-slate-800 rounded-[3rem] overflow-hidden shadow-2xl transition-all duration-500 hover:border-sky-500/30">
                    <!-- Subtle Background Glow -->
                    <div class="absolute -top-24 -left-24 w-64 h-64 bg-sky-500/5 blur-[80px] rounded-full group-hover:bg-sky-500/10 transition-all duration-700"></div>

                    <div class="relative z-10">
                        <div class="flex items-center gap-4 mb-12">
                            <div class="w-12 h-12 bg-sky-500/10 rounded-xl flex items-center justify-center text-sky-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </div>
                            <span class="text-sky-500 font-bold uppercase tracking-[0.2em] text-xs">Visi</span>
                        </div>

                        <p class="text-3xl lg:text-5xl font-black leading-tight text-white tracking-tight group-hover:text-sky-50 transition-colors duration-500">
                            {visionMission?.vision || 'Menyediakan layanan berkualitas tinggi dengan solusi inovatif.'}
                        </p>
                    </div>
                </div>
            </div>

            {#if visionMission?.missions && Array.isArray(visionMission.missions)}
                <!-- Missions Title -->
                <div use:scrollReveal class="mb-12 mt-20 relative z-10 reveal">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-sky-500/10 rounded-2xl flex items-center justify-center text-sky-400 border border-sky-500/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h3 class="text-3xl font-black text-white tracking-tight uppercase">Misi</h3>
                    </div>
                </div>

                <!-- Missions Grid -->
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    {#each visionMission.missions as mission, i}
                        <div use:scrollReveal={{ delay: i * 100 }} class="p-8 rounded-3xl bg-slate-900/80 border border-slate-800 hover:border-sky-500/50 transition-all group hover:-translate-y-2 duration-500 shadow-2xl relative overflow-hidden reveal">
                            <!-- Background Accent -->
                            <div class="absolute -right-4 -top-4 w-16 h-16 bg-sky-500/5 rounded-full blur-xl group-hover:bg-sky-500/10 transition-all"></div>
                            
                            <div class="flex items-center gap-4 mb-6">
                                <span class="text-sky-500 font-black text-3xl opacity-40 group-hover:opacity-100 transition-opacity">0{i+1}</span>
                                <div class="h-[1px] flex-1 bg-slate-800"></div>
                            </div>
                            
                            <p class="text-lg text-white font-medium leading-relaxed relative z-10">
                                {mission}
                            </p>
                        </div>
                    {/each}
                </div>
            {/if}
        </div>
    </section>

    <!-- Jasa Section -->
    <section id="services" class="py-32 relative">
        <div class="container mx-auto px-6">
            <!-- Section Title -->
            <div use:scrollReveal class="text-center mb-20 reveal">
                <h2 class="text-4xl lg:text-6xl font-black tracking-tight mb-4">Melayani <span class="text-sky-500">Kebutuhan Digital</span> Anda</h2>
                <div class="w-24 h-1.5 bg-sky-500 mx-auto rounded-full shadow-[0_0_20px_rgba(14,165,233,0.5)]"></div>
                <p class="mt-8 text-slate-400 max-w-2xl mx-auto">Transformasi kebutuhan digital menjadi solusi yang efisien, mudah digunakan, dan handal</p>
            </div>

            <div class="max-w-6xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                    {#each services as service, i}
                        <div use:scrollReveal={{ delay: i * 100 }} class="group relative bg-slate-900 border border-slate-800 p-8 lg:p-10 rounded-[2.5rem] transition-all duration-500 hover:border-sky-500/30 shadow-2xl overflow-hidden flex flex-col reveal">
                            <!-- Header Area -->
                            <div class="mb-8">
                                <div class="w-14 h-14 bg-sky-500/10 rounded-2xl flex items-center justify-center text-sky-400 mb-6 group-hover:scale-110 transition-all duration-500 border border-sky-500/10 group-hover:border-sky-500/40 shadow-inner">
                                    {@html getServiceIcon(service.icon)}
                                </div>
                                <h3 class="text-2xl lg:text-3xl font-black text-white group-hover:text-sky-400 transition-colors tracking-tighter mb-3">
                                    {service.title}
                                </h3>
                                <div class="h-1 w-10 bg-sky-500/20 rounded-full group-hover:w-20 group-hover:bg-sky-500 transition-all duration-500"></div>
                            </div>

                            <!-- Investment Info -->
                            <div class="mb-8">
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-[0.2em] mb-1">Investment Range</p>
                                <p class="text-xl font-black text-white tracking-tight">
                                    {service.price_range}
                                </p>
                            </div>

                            <!-- Description Area (Handles paragraphs & points) -->
                            <div class="flex-1">
                                <div class="text-base text-slate-400 leading-relaxed font-light group-hover:text-slate-300 transition-colors whitespace-pre-line">
                                    {service.description}
                                </div>
                            </div>

                            <!-- Decorative Elements -->
                            <div class="absolute -right-20 -top-20 w-64 h-64 bg-sky-500/5 blur-[100px] rounded-full pointer-events-none group-hover:bg-sky-500/10 transition-all duration-1000"></div>
                            <div class="absolute inset-0 bg-gradient-to-br from-transparent via-transparent to-sky-500/[0.02] opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                        </div>
                    {/each}
                </div>
            </div>
        </div>
    </section>

    <!-- Projek Section -->
    <section id="projects" class="py-32 bg-slate-950/50 relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-sky-600/5 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="container mx-auto px-6">
            <!-- Section Header -->
            <div use:scrollReveal class="text-center mb-24 relative z-10 reveal">
                <div class="flex items-center justify-center gap-3 mb-6">
                    <div class="w-12 h-[1px] bg-sky-500/50"></div>
                    <span class="text-sky-500 text-xs font-black uppercase tracking-[0.3em]">Portofolio Expo</span>                    <div class="w-12 h-[1px] bg-sky-500/50"></div>
                </div>
                <h2 class="text-5xl lg:text-7xl font-black tracking-tighter mb-6 text-white">{projects.length} Solusi <span class="text-sky-500">Telah Dibuat</span></h2>
                <div class="w-32 h-1.5 bg-sky-500 mx-auto rounded-full shadow-[0_0_25px_rgba(14,165,233,0.6)]"></div>
                
                <!-- Search Box (Futuristic) -->
                <div class="mt-12 relative max-w-xl mx-auto group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-sky-500/20 to-indigo-500/20 rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-1000"></div>
                    <div class="relative flex items-center bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
                        <div class="pl-6 text-slate-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input 
                            type="text" 
                            bind:value={searchQuery}
                            placeholder="Cari solusi atau proyek..." 
                            class="w-full bg-transparent border-none px-6 py-5 focus:ring-0 outline-none text-slate-100 font-medium placeholder:text-slate-600"
                        />
                    </div>
                </div>
            </div>

            <!-- Projects Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-20 relative z-10">
                {#each paginatedProjects as project, i}
                    <a use:scrollReveal={{ delay: (i % 3) * 100 }} href="/project/{project.slug}" class="group relative flex flex-col bg-slate-900 border border-slate-800 rounded-3xl hover:border-sky-500/50 transition-all duration-500 shadow-xl overflow-hidden reveal">
                        <!-- Thumbnail Container -->
                        <div class="relative aspect-[4/3] overflow-hidden">
                            {#if project.thumbnail}
                                <img src="/storage/{project.thumbnail}" alt={project.title} class="w-full h-full object-cover group-hover:scale-110 transition-all duration-700" />
                            {:else}
                                <div class="w-full h-full bg-slate-800 flex items-center justify-center">
                                    <span class="text-4xl opacity-10">📦</span>
                                </div>
                            {/if}
                            
                            <!-- Date Badge -->
                            <div class="absolute top-4 left-4 z-10">
                                <div class="bg-slate-950/80 backdrop-blur-md border border-white/10 px-3 py-1.5 rounded-xl flex flex-col items-center">
                                    <span class="text-[10px] text-sky-400 font-bold uppercase tracking-widest">{project.month || 'Jan'}</span>
                                    <span class="text-sm text-white font-bold">{project.year || '2024'}</span>
                                </div>
                            </div>

                            <!-- Open Source Badge -->
                            {#if project.is_opensource}
                                <div class="absolute top-4 right-4 z-10">
                                    <div class="bg-sky-500 text-white px-3 py-1.5 rounded-xl flex items-center gap-2 shadow-lg shadow-sky-500/20">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                                        <span class="text-[10px] font-black uppercase tracking-wider">Open Source</span>
                                    </div>
                                </div>
                            {/if}

                            <!-- Overlay Fade -->
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        </div>

                        <!-- Card Content -->
                        <div class="p-8 flex-1 flex flex-col">
                            <h3 class="text-xl font-bold mb-4 text-white group-hover:text-sky-400 transition-colors tracking-tight line-clamp-1">
                                {project.title}
                            </h3>
                            <p class="text-slate-400 text-sm font-light leading-relaxed mb-8 line-clamp-2 flex-1 group-hover:text-slate-300 transition-colors">
                                {project.short_description}
                            </p>

                            <!-- Action Link (Text Only) -->
                            <div class="flex items-center gap-2 text-sky-500 font-bold group-hover:gap-4 transition-all mt-auto group-hover:text-sky-400">
                                <span>baca lebih lanjut</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </div>
                        </div>
                    </a>
                {:else}
                    <div class="col-span-full text-center py-24 bg-slate-900/30 border border-slate-800 border-dashed rounded-[3rem]">
                        <div class="text-6xl mb-6 opacity-20">🔍</div>
                        <p class="text-slate-500 text-xl font-light">Tidak ada solusi ditemukan untuk <span class="text-sky-500 font-medium">"{searchQuery}"</span></p>
                    </div>
                {/each}
            </div>

            <!-- Pagination -->
            {#if totalPages > 1}
                <div class="flex justify-center items-center gap-6 relative z-10">
                    <button 
                        disabled={currentPage === 1}
                        onclick={() => {
                            currentPage--;
                            scrollTo('projects');
                        }}
                        class="w-14 h-14 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-400 hover:text-sky-400 hover:border-sky-500/50 disabled:opacity-20 disabled:pointer-events-none transition-all shadow-xl"
                        aria-label="Halaman sebelumnya"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>

                    <div class="flex items-center gap-3">
                        {#each Array(totalPages) as _, i}
                            <button 
                                onclick={() => {
                                    currentPage = i + 1;
                                    scrollTo('projects');
                                }}
                                class="w-12 h-12 rounded-xl font-black text-xs transition-all {currentPage === i + 1 ? 'bg-sky-500 text-white shadow-lg shadow-sky-500/20 scale-110' : 'bg-slate-900 border border-slate-800 text-slate-500 hover:text-white hover:border-slate-600'}"
                                aria-label="Halaman {i + 1}"
                            >
                                {i + 1}
                            </button>
                        {/each}
                    </div>

                    <button 
                        disabled={currentPage === totalPages}
                        onclick={() => {
                            currentPage++;
                            scrollTo('projects');
                        }}
                        class="w-14 h-14 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-400 hover:text-sky-400 hover:border-sky-500/50 disabled:opacity-20 disabled:pointer-events-none transition-all shadow-xl"
                        aria-label="Halaman berikutnya"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            {/if}
        </div>
    </section>

    <!-- Kemampuan Section -->
    <section id="skills" class="py-32 relative">
        <div class="container mx-auto px-6">
            <div use:scrollReveal class="text-center mb-24 reveal">
                <h2 class="text-5xl lg:text-7xl font-black tracking-tighter mb-4">Bidang <span class="text-sky-500">Keahlian</span></h2>
                <div class="w-24 h-1.5 bg-sky-500 mx-auto rounded-full shadow-[0_0_20px_rgba(14,165,233,0.5)]"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 lg:gap-16">
                {#each ['teknologi', 'minat', 'bahasa'] as cat, catIndex}
                    <div use:scrollReveal={{ delay: catIndex * 150 }} class="flex flex-col reveal">
                        <!-- Category Header -->
                        <div class="flex items-center gap-4 mb-10 pb-6 border-b border-slate-800/50">
                            <div class="w-14 h-14 rounded-2xl bg-sky-500/10 flex items-center justify-center text-3xl shadow-inner border border-sky-500/10">
                                {cat === 'bahasa' ? '🌐' : cat === 'teknologi' ? '⚡' : '✨'}
                            </div>
                            <div>
                                <h3 class="text-2xl font-black text-white tracking-tight">{getCategoryLabel(cat)}</h3>
                            </div>
                        </div>

                        <!-- Skills List (Cards) -->
                        <div class="space-y-6">
                            {#if categorizedSkills[cat]}
                                {#each categorizedSkills[cat] as skill, i}
                                    <div use:scrollReveal={{ delay: (catIndex * 150) + (i * 100) }} class="group relative p-6 rounded-[2rem] bg-slate-900/50 border border-slate-800 hover:border-sky-500/30 transition-all duration-500 shadow-xl overflow-hidden hover:-translate-y-1 reveal">
                                        <!-- Animated Background Accent -->
                                        <div class="absolute -right-8 -bottom-8 w-24 h-24 bg-sky-500/5 rounded-full blur-2xl group-hover:bg-sky-500/10 transition-all duration-700"></div>
                                        
                                        <div class="relative z-10">
                                            <div class="flex items-center gap-5 mb-4">
                                                <div class="w-12 h-12 rounded-xl bg-slate-950 flex items-center justify-center p-2.5 border border-slate-800 group-hover:border-sky-500/50 transition-colors duration-500">
                                                    {#if skill.logo_path}
                                                        <img src="/storage/{skill.logo_path}" alt={skill.title} class="max-w-full max-h-full object-contain" />
                                                    {:else}
                                                        <span class="text-xl">✨</span>
                                                    {/if}
                                                </div>
                                                <h4 class="text-lg font-black text-white group-hover:text-sky-400 transition-colors duration-300 tracking-tight">
                                                    {skill.title}
                                                </h4>
                                            </div>
                                            <p class="text-sm text-slate-400 leading-relaxed font-light line-clamp-3 group-hover:text-slate-300 transition-colors duration-500">
                                                {skill.description || 'Keahlian profesional dalam bidang ini untuk mendukung solusi digital.'}
                                            </p>
                                        </div>
                                    </div>
                                {/each}
                            {:else}
                                <div class="p-8 rounded-[2rem] border border-slate-800 border-dashed text-center">
                                    <p class="text-slate-600 text-sm italic">Belum ada data</p>
                                </div>
                            {/if}
                        </div>
                    </div>
                {/each}
            </div>
        </div>
    </section>

    <!-- Experience Section -->
    <section id="experience" class="py-32 bg-slate-950/50 relative overflow-hidden">
        <!-- Decorative Background Glow -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-sky-500/5 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="container mx-auto px-6 relative z-10">
            <h2 use:scrollReveal class="text-5xl lg:text-7xl font-black mb-32 tracking-tighter flex flex-col items-center justify-center gap-2 text-center reveal">
                <span class="text-sky-500 text-2xl font-mono mb-4 tracking-[0.3em] uppercase opacity-50">Jenjang Karir</span>
                Pengalaman <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-indigo-500">Profesional</span>
            </h2>
            
            <div class="relative max-w-6xl mx-auto">
                <div class="absolute left-8 lg:left-1/2 top-0 bottom-0 w-px bg-gradient-to-b from-transparent via-slate-800 to-transparent lg:-translate-x-1/2">
                    <!-- Glowing Indicator (Animated) -->
                    <div class="sticky top-1/2 w-1 h-20 bg-gradient-to-b from-sky-500 to-indigo-500 -ml-[1.5px] blur-[2px] opacity-50"></div>
                </div>

                <div class="space-y-24">
                    {#each experiences as exp, i}
                        <div class="relative flex flex-col lg:flex-row items-center {i % 2 === 0 ? 'lg:flex-row-reverse' : ''}">
                            <!-- Timeline Dot -->
                            <div class="absolute left-8 lg:left-1/2 top-0 w-6 h-6 -translate-x-1/2 z-20 flex items-center justify-center">
                                <div class="w-full h-full rounded-full bg-slate-950 border border-slate-800 group-hover:border-sky-500 transition-colors relative">
                                    <div class="absolute inset-1 rounded-full bg-sky-500 shadow-[0_0_15px_rgba(14,165,233,0.5)]"></div>
                                </div>
                            </div>

                            <!-- Content Card Area -->
                            <div class="w-full lg:w-[45%] pl-20 lg:pl-0">
                                <div use:scrollReveal={{ threshold: 0.2 }} class="group relative p-8 rounded-[2.5rem] bg-slate-900/50 backdrop-blur-sm border border-slate-800 hover:border-sky-500/30 transition-all duration-500 hover:-translate-y-2 reveal">
                                    <!-- Date Badge -->
                                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-sky-500/10 border border-sky-500/20 text-sky-400 text-xs font-black uppercase tracking-widest mb-6">
                                        <span class="relative flex h-2 w-2">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-sky-500"></span>
                                        </span>
                                        {exp.start_date} — {exp.end_date || 'Sekarang'}
                                    </div>

                                    <h3 class="text-3xl font-black mb-3 text-white group-hover:text-sky-400 transition-colors leading-tight">{exp.position}</h3>
                                    
                                    <div class="flex flex-wrap items-center gap-3 text-slate-400 mb-6">
                                        <div class="flex items-center gap-2 px-3 py-1 rounded-lg bg-slate-950 border border-slate-800">
                                            <span class="text-white font-bold">{exp.company}</span>
                                        </div>
                                        <span class="w-1 h-1 rounded-full bg-slate-700"></span>
                                        <span class="text-sm font-medium text-slate-500 italic">{exp.location_text}</span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-black bg-slate-800 text-slate-400 uppercase tracking-tighter border border-slate-700">{exp.status}</span>
                                    </div>

                                    <p class="text-slate-400 font-light leading-relaxed whitespace-pre-line text-sm lg:text-base">
                                        {exp.description}
                                    </p>

                                    <!-- Bottom Gradient Accent -->
                                    <div class="absolute bottom-0 left-10 right-10 h-px bg-gradient-to-r from-transparent via-sky-500/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                                </div>
                            </div>

                            <!-- Empty space for the other side (Desktop) -->
                            <div class="hidden lg:block lg:w-[45%]"></div>
                        </div>
                    {/each}
                </div>
            </div>
        </div>
    </section>

    <!-- Education Section -->
    <section id="education" class="py-32 relative overflow-hidden">
        <!-- Background Decorative Element -->
        <div class="absolute bottom-0 left-0 w-full h-1/2 bg-gradient-to-t from-indigo-500/5 to-transparent pointer-events-none"></div>

        <div class="container mx-auto px-6 relative z-10">
            <h2 use:scrollReveal class="text-5xl lg:text-7xl font-black mb-32 tracking-tighter flex flex-col items-center justify-center gap-2 text-center reveal">
                Perjalanan <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-500">Pendidikan</span>
            </h2>
            
            <div class="relative max-w-6xl mx-auto">
                <div class="absolute left-8 lg:left-1/2 top-0 bottom-0 w-px bg-gradient-to-b from-transparent via-slate-800 to-transparent lg:-translate-x-1/2">
                    <div class="absolute inset-0 bg-gradient-to-b from-indigo-500/20 via-transparent to-purple-500/20"></div>
                </div>

                <div class="space-y-24">
                    {#each education as edu, i}
                        <div class="relative flex flex-col lg:flex-row items-center {i % 2 !== 0 ? 'lg:flex-row-reverse' : ''}">
                            <!-- Timeline Dot -->
                            <div class="absolute left-8 lg:left-1/2 top-0 w-6 h-6 -translate-x-1/2 z-20 flex items-center justify-center">
                                <div class="w-full h-full rounded-full bg-slate-950 border border-slate-800 group-hover:border-indigo-500 transition-colors relative">
                                    <div class="absolute inset-1 rounded-full bg-indigo-500 shadow-[0_0_15px_rgba(99,102,241,0.5)]"></div>
                                </div>
                            </div>

                            <!-- Content Card -->
                            <div class="w-full lg:w-[45%] pl-20 lg:pl-0">
                                <div use:scrollReveal={{ threshold: 0.2 }} class="group relative p-8 rounded-[2.5rem] bg-slate-900/30 backdrop-blur-sm border border-slate-800/50 hover:border-indigo-500/30 transition-all duration-500 reveal">
                                    <!-- Graduation Year -->
                                    <div class="inline-block px-4 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-xs font-black mb-6">
                                        🎓 Lulus: {edu.graduation_date}
                                    </div>

                                    <h3 class="text-3xl font-black mb-3 text-white group-hover:text-indigo-400 transition-colors tracking-tight">{edu.major}</h3>
                                    
                                    <div class="flex items-center gap-3 text-slate-400 mb-6 font-medium">
                                        <span class="text-slate-200">{edu.institution}</span>
                                        {#if edu.degree}
                                            <span class="w-1 h-1 rounded-full bg-slate-700"></span>
                                            <span class="text-indigo-300/70 text-sm tracking-wide">{edu.degree}</span>
                                        {/if}
                                    </div>

                                    <div class="flex items-center justify-between pt-6 border-t border-slate-800/50">
                                        <div class="flex items-center gap-2 text-slate-500 text-sm">
                                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                            {edu.location_text}
                                        </div>
                                        
                                        {#if edu.gpa}
                                            <div class="flex flex-col items-end">
                                                <span class="text-[10px] text-slate-500 uppercase font-black tracking-tighter">Grade Point</span>
                                                <span class="text-xl font-black text-indigo-400">{edu.gpa}</span>
                                            </div>
                                        {/if}
                                    </div>

                                    <!-- Corner Glow -->
                                    <div class="absolute -bottom-2 -right-2 w-20 h-20 bg-indigo-500/10 blur-2xl rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                </div>
                            </div>

                            <!-- Spacer -->
                            <div class="hidden lg:block lg:w-[45%]"></div>
                        </div>
                    {/each}
                </div>
            </div>
        </div>
    </section>

    <!-- Sertifikat Section -->
    <section id="certificates" class="py-32 relative overflow-hidden">
        <!-- Background Decoration -->
        <div class="absolute top-1/2 right-0 w-[500px] h-[500px] bg-sky-500/5 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="container mx-auto px-6 relative z-10">
            <div use:scrollReveal class="text-center mb-24 reveal">
                <h2 class="text-5xl lg:text-7xl font-black tracking-tighter mb-4">
                    Pelatihan & <span class="text-sky-500">Sertifikasi</span>
                </h2>
                <div class="w-24 h-1.5 bg-sky-500 mx-auto rounded-full shadow-[0_0_20px_rgba(14,165,233,0.5)]"></div>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
                {#each certificates as cert, i}
                    <div use:scrollReveal={{ delay: (i % 3) * 100 }} class="group relative p-10 rounded-[2rem] bg-slate-900/40 backdrop-blur-md border border-slate-800/50 hover:border-sky-500/30 transition-all duration-700 flex flex-col h-full overflow-hidden reveal">
                        <!-- Left Accent Line (Professional Look) -->
                        <div class="absolute left-0 top-10 bottom-10 w-[2px] bg-sky-500/30 group-hover:bg-sky-500 transition-colors duration-700"></div>

                        <!-- Content -->
                        <div class="flex-1 relative z-10 pl-4">
                            <h3 class="text-2xl font-black mb-2 text-white group-hover:text-sky-400 transition-colors leading-tight tracking-tight mt-2">{cert.title}</h3>
                            <p class="text-slate-400 font-bold mb-8 text-sm">{cert.issuer}</p>
                            
                            <div class="flex flex-wrap gap-2">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-sky-500/5 border border-sky-500/10 text-[10px] font-black text-sky-500/80 uppercase tracking-widest">
                                    <span class="w-1.5 h-1.5 rounded-full bg-sky-500 animate-pulse"></span>
                                    {cert.level}
                                </div>
                                <div class="inline-flex items-center px-3 py-1 rounded-lg bg-slate-950 border border-slate-800 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                                    {cert.category}
                                </div>
                            </div>
                        </div>

                        <!-- Footer Info (Simplified Range) -->
                        <div class="mt-10 pt-6 border-t border-slate-800/50 pl-4 relative z-10 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-sky-500/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <p class="text-xs text-slate-400 font-medium tracking-tight">
                                    {cert.start_date} — {cert.end_date || 'Seumur Hidup'}
                                </p>
                            </div>
                        </div>

                        <!-- Professional Hover Background -->
                        <div class="absolute inset-0 bg-gradient-to-br from-sky-500/[0.03] to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-1000"></div>
                    </div>
                {:else}
                    <div class="col-span-full py-20 text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-900 border border-slate-800 mb-6">
                            <span class="text-3xl opacity-20">📭</span>
                        </div>
                        <p class="text-slate-500 font-medium italic">Belum ada data sertifikat yang tersedia.</p>
                    </div>
                {/each}
            </div>
        </div>
    </section>
    <!-- Footer Section -->
    <footer id="contact" class="pt-32 pb-16 bg-slate-950/80 border-t border-slate-900/50 relative overflow-hidden">
        <!-- Background Glows -->
        <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-full h-px bg-gradient-to-r from-transparent via-sky-500/20 to-transparent"></div>
        <div class="absolute bottom-0 left-1/4 w-[400px] h-[400px] bg-sky-600/5 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="flex flex-col lg:flex-row justify-between gap-16 lg:gap-8 pt-20 border-t border-slate-900">
                <!-- Brand Info -->
                <div use:scrollReveal class="max-w-sm space-y-8 reveal">
                    <div class="flex items-center gap-3">
                        <img src="/icon.webp" alt="Logo" class="w-12 h-12 rounded-xl shadow-lg shadow-sky-500/10" />
                        <span class="text-2xl font-black tracking-tighter text-white uppercase">{profile?.full_name || 'Wufy Portfolio'}</span>
                    </div>
                    <p class="text-slate-400 font-light leading-relaxed text-sm">
                        Berfokus pada pengembangan solusi digital yang inovatif, efisien, dan berorientasi pada hasil untuk membantu bisnis Anda tumbuh lebih cepat.
                    </p>
                    <!-- Social Links -->
                    <div class="flex items-center gap-3">
                        {#if profile?.links}
                            {#each profile.links as link}
                                <a href={link.link} target="_blank" rel="noopener noreferrer" class="w-11 h-11 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-400 hover:text-sky-400 hover:border-sky-500/50 hover:bg-sky-500/5 transition-all group shadow-xl">
                                    {@html getIcon(link.title)}
                                </a>
                            {/each}
                        {/if}
                    </div>
                </div>

                <!-- Navigation Links -->
                <div use:scrollReveal={{ delay: 100 }} class="min-w-[280px] reveal">
                    <h4 class="text-white font-black uppercase tracking-widest text-sm mb-8">Navigasi</h4>
                    <ul class="grid grid-cols-2 gap-y-4 gap-x-12">
                        {#each [
                            { id: 'home', label: 'Beranda' },
                            { id: 'vision', label: 'Visi/Misi' },
                            { id: 'services', label: 'Jasa' },
                            { id: 'projects', label: 'Projek' },
                            { id: 'skills', label: 'Keahlian' },
                            { id: 'experience', label: 'Pengalaman' },
                            { id: 'education', label: 'Edukasi' },
                            { id: 'certificates', label: 'Sertifikat' },
                            { id: 'contact', label: 'Kontak' }
                        ] as item}
                            <li>
                                <button 
                                    onclick={() => scrollTo(item.id)}
                                    class="text-slate-400 hover:text-sky-400 transition-colors text-sm font-medium whitespace-nowrap text-left"
                                >
                                    {item.label}
                                </button>
                            </li>
                        {/each}
                    </ul>
                </div>

                <!-- Contact Info -->
                <div use:scrollReveal={{ delay: 200 }} class="min-w-[240px] reveal">
                    <h4 class="text-white font-black uppercase tracking-widest text-sm mb-8">Kontak</h4>
                    <div class="space-y-6">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-sky-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <div>
                                <p class="text-slate-200 text-sm font-bold tracking-tight">{profile?.city}, {profile?.province}</p>
                                <p class="text-slate-500 text-xs mt-1 uppercase font-black tracking-widest">Lokasi</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-sky-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <div>
                                <p class="text-slate-200 text-sm font-bold tracking-tight">{profile?.phone}</p>
                                <p class="text-slate-500 text-xs mt-1 uppercase font-black tracking-widest">Telepon</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-sky-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <div>
                                <p class="text-slate-200 text-sm font-bold tracking-tight">{profile?.email}</p>
                                <p class="text-slate-500 text-xs mt-1 uppercase font-black tracking-widest">Email</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Copyright Bar -->
            <div use:scrollReveal class="mt-32 pt-8 border-t border-slate-900 flex flex-col md:flex-row justify-between items-center gap-6 reveal">
                <p class="text-slate-500 text-xs font-medium tracking-wide">
                    &copy; {new Date().getFullYear()} <span class="text-slate-300 font-bold">{profile?.full_name || 'Wufy Portfolio'}</span>. All rights reserved.
                </p>
            </div>
        </div>
    </footer>


</div>

<style>
    :global(html) {
        scroll-behavior: smooth;
        background: #020617;
    }

    :global(body) {
        margin: 0;
        padding: 0;
    }
    
    /* Custom Scrollbar */
    :global(::-webkit-scrollbar) {
        width: 10px;
    }
    :global(::-webkit-scrollbar-track) {
        background: #020617;
    }
    :global(::-webkit-scrollbar-thumb) {
        background: #1e293b;
        border-radius: 10px;
        border: 3px solid #020617;
    }
    :global(::-webkit-scrollbar-thumb:hover) {
        background: #334155;
    }

    @keyframes progress {
        from { width: 0; }
        to { width: 85%; }
    }

    @keyframes scan {
        0% { transform: translateY(-100%); }
        100% { transform: translateY(400%); }
    }

    @keyframes scan-slow {
        0% { transform: translateY(-150%); }
        100% { transform: translateY(300%); }
    }

    .animate-scan {
        animation: scan 4s linear infinite;
    }

    .animate-scan-slow {
        animation: scan-slow 8s linear infinite;
    }

    @keyframes spin-reverse {
        from { transform: rotate(0deg); }
        to { transform: rotate(-360deg); }
    }
    
    .animate-spin-reverse {
        animation: spin-reverse 2s linear infinite;
    }

    @keyframes progress-loading {
        0% { width: 0; left: 0; }
        50% { width: 100%; left: 0; }
        100% { width: 0; left: 100%; }
    }

    .animate-progress-loading {
        animation: progress-loading 2s ease-in-out infinite;
    }

    .clip-hexagon {
        clip-path: polygon(25% 0%, 75% 0%, 100% 50%, 75% 100%, 25% 100%, 0% 50%);
    }

    @keyframes pulse {
        0%, 100% { opacity: 0.1; transform: scale(1); }
        50% { opacity: 0.2; transform: scale(1.1); }
    }

    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }

    .animate-blob {
        animation: blob 20s infinite ease-in-out;
    }

    .animation-delay-2000 {
        animation-delay: 2s;
    }

    .animation-delay-4000 {
        animation-delay: 4s;
    }
    
    .animate-pulse {
        animation: pulse 15s infinite ease-in-out;
    }

    /* Scroll Reveal Styles */
    :global(.reveal) {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.8s cubic-bezier(0.2, 0.8, 0.2, 1), transform 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
        will-change: opacity, transform;
    }

    :global(.revealed) {
        opacity: 1 !important;
        transform: translateY(0) !important;
    }

    /* Staggered delays using CSS variables if needed, 
       but we handle them via JS options in the action for simplicity. 
       If we want to pass delay to the action, we can set it as an inline style. */
</style>
