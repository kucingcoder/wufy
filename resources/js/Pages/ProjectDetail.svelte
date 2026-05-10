<script>
    import { onMount } from 'svelte';
    import { fade, fly } from 'svelte/transition';

    let { project, app_url, author_name } = $props();
    const baseUrl = $derived(app_url?.replace(/\/$/, '') || '');
    let mounted = $state(false);

    // Image Viewer State
    let selectedImage = $state(null);
    let zoomScale = $state(1);
    let isDragging = $state(false);
    let position = $state({ x: 0, y: 0 });
    let startPos = { x: 0, y: 0 };

    onMount(() => {
        mounted = true;
    });

    const openViewer = (image) => {
        selectedImage = image;
        zoomScale = 1;
        position = { x: 0, y: 0 };
        document.body.style.overflow = 'hidden';
    };

    const closeViewer = () => {
        selectedImage = null;
        document.body.style.overflow = 'auto';
    };

    const handleZoom = (delta) => {
        zoomScale = Math.max(0.5, Math.min(5, zoomScale + delta));
    };

    const handleMouseDown = (e) => {
        if (zoomScale > 1) {
            isDragging = true;
            startPos = { x: e.clientX - position.x, y: e.clientY - position.y };
        }
    };

    const handleMouseMove = (e) => {
        if (isDragging) {
            position = {
                x: e.clientX - startPos.x,
                y: e.clientY - startPos.y
            };
        }
    };

    const handleMouseUp = () => {
        isDragging = false;
    };

    const jsonLd = $derived(JSON.stringify({
        "@context": "https://schema.org",
        "@type": "Article",
        "headline": project.title,
        "description": project.short_description,
        "image": project.thumbnail ? `${baseUrl}/storage/${project.thumbnail}` : `${baseUrl}/icon.webp`,
        "url": `${baseUrl}/project/${project.slug}`,
        "datePublished": project.created_at,
        "author": {
            "@type": "Person",
            "name": author_name
        }
    }));

</script>

<svelte:head>
    <title>{project.title} - Case Study</title>
    <meta name="description" content={project.short_description}>
    <meta name="keywords" content="{project.title.split(' ').join(', ')}, portfolio, case study, digital solutions">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article">
    <meta property="og:url" content={`${baseUrl}/project/${project.slug}`}>
    <meta property="og:title" content="{project.title} - Case Study">
    <meta property="og:description" content={project.short_description}>
    <meta property="og:image" content={project.thumbnail ? `${baseUrl}/storage/${project.thumbnail}` : `${baseUrl}/icon.webp`}>

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content={`${baseUrl}/project/${project.slug}`}>
    <meta property="twitter:title" content="{project.title} - Case Study">
    <meta property="twitter:description" content={project.short_description}>
    <meta property="twitter:image" content={project.thumbnail ? `${baseUrl}/storage/${project.thumbnail}` : `${baseUrl}/icon.webp`}>

    <link rel="icon" type="image/webp" href="/icon.webp">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Structured Data (JSON-LD) -->
    <script type="application/ld+json">
        {@html jsonLd}
    </script>
</svelte:head>

<div class="bg-[#020617] text-slate-100 font-['Plus_Jakarta_Sans'] selection:bg-sky-500/30 min-h-screen overflow-x-hidden">
    
    <!-- Background Gradient -->
    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-sky-600/10 blur-[120px] rounded-full"></div>
    </div>

    <!-- Header / Back Button -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-slate-950/50 backdrop-blur-md border-b border-slate-800/50">
        <div class="container mx-auto px-6 h-20 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2 text-slate-400 hover:text-sky-400 transition-colors group">
                <div class="w-8 h-8 rounded-lg bg-slate-900 border border-slate-800 flex items-center justify-center group-hover:border-sky-500/50 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </div>
                <span class="font-bold uppercase tracking-widest text-xs">Back to Home</span>
            </a>
        </div>
    </header>

    <main class="pt-32 pb-40 px-6 relative z-10">
        <article class="container mx-auto max-w-5xl">
            
            <!-- Hero -->
            <div class="mb-20 text-left">
                <h1 class="text-5xl lg:text-8xl font-black tracking-tighter mb-4 leading-none text-left">{project.title}</h1>
                <div class="flex flex-wrap items-center gap-6">
                    <div class="flex items-center gap-2 text-sky-500 font-black uppercase tracking-[0.3em] text-sm">
                        <span>{project.month || 'Januari'}</span>
                        <span class="w-2 h-2 rounded-full bg-slate-800"></span>
                        <span>{project.year || '2024'}</span>
                    </div>
                </div>
            </div>

            <!-- Main Image -->
            <div class="relative w-full aspect-[21/9] lg:aspect-[3/1] rounded-[2rem] lg:rounded-[3rem] overflow-hidden bg-slate-900 border border-slate-800 mb-16 shadow-2xl shadow-sky-900/10 group ring-1 ring-white/5">
                {#if project.thumbnail}
                    <div class="absolute inset-0 bg-slate-950/10 group-hover:bg-transparent transition-colors duration-700 z-10 pointer-events-none"></div>
                    <img src="/storage/{project.thumbnail}" alt={project.title} class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-[1.5s] ease-out" />
                    <div class="absolute inset-0 bg-gradient-to-t from-[#020617]/80 via-transparent to-transparent z-10 pointer-events-none"></div>
                {:else}
                    <div class="w-full h-full bg-gradient-to-br from-slate-800 to-slate-950 flex items-center justify-center text-8xl opacity-20">🖼️</div>
                {/if}
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center justify-center gap-4 mb-20">
                {#if project.link}
                    <a 
                        href={project.link} 
                        target="_blank" 
                        rel="noopener noreferrer"
                        class="flex items-center gap-2 px-6 py-3 rounded-2xl bg-sky-500 text-white hover:bg-sky-400 transition-all group shadow-xl shadow-sky-500/20"
                    >
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        <span class="text-sm font-bold uppercase tracking-wider">Kunjungi</span>
                    </a>
                {/if}

                {#if project.is_opensource && project.github_link}
                    <a 
                        href={project.github_link} 
                        target="_blank" 
                        rel="noopener noreferrer"
                        class="flex items-center gap-2 px-6 py-3 rounded-2xl bg-slate-900 border border-slate-800 text-slate-300 hover:text-white hover:border-sky-500/50 transition-all group shadow-xl"
                    >
                        <svg class="w-5 h-5 text-sky-500 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                        <span class="text-sm font-bold uppercase tracking-wider">Github</span>
                    </a>
                {/if}
            </div>

            <!-- Blog Content -->
            <div class="max-w-4xl mx-auto">
                <div class="prose prose-invert prose-sky max-w-none">
                    <div class="text-slate-300 leading-relaxed text-lg space-y-8">
                        {#if project.blog_content}
                            {@html project.blog_content}
                        {:else}
                            <p>No detailed description available for this project</p>
                        {/if}
                    </div>
                </div>
            </div>

            <!-- Gallery Section -->
            {#if project.galleries && project.galleries.length > 0}
                <div class="mt-40">
                    <h2 class="text-4xl lg:text-6xl font-black mb-16 tracking-tight">Project <span class="text-sky-500">Gallery</span></h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        {#each project.galleries as gallery}
                            <button 
                                onclick={() => openViewer(`/storage/${gallery.image_path}`)}
                                class="group flex flex-col gap-3 text-left outline-none"
                            >
                                <div class="relative rounded-2xl overflow-hidden bg-slate-900 border border-slate-800 aspect-video w-full">
                                    <img src="/storage/{gallery.image_path}" alt={gallery.title || project.title} class="w-full h-full object-cover hover:scale-105 transition-transform duration-700" />
                                    <!-- Zoom Icon Overlay -->
                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-sky-500/10 pointer-events-none">
                                        <div class="w-12 h-12 rounded-full bg-sky-500 text-white flex items-center justify-center shadow-lg">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                        </div>
                                    </div>
                                </div>
                                {#if gallery.title}
                                    <div class="px-2">
                                        <span class="text-sm font-medium text-slate-300 group-hover:text-white transition-colors tracking-tight">{gallery.title}</span>
                                    </div>
                                {/if}
                            </button>
                        {/each}
                    </div>
                </div>
            {/if}

        </article>
    </main>

    <!-- Image Viewer Modal -->
    {#if selectedImage}
        <div 
            transition:fade={{ duration: 200 }}
            class="fixed inset-0 z-[100] bg-slate-950/95 backdrop-blur-xl flex items-center justify-center overflow-hidden touch-none"
            onmousemove={handleMouseMove}
            onmouseup={handleMouseUp}
            onmouseleave={handleMouseUp}
            role="button"
            tabindex="0"
            aria-label="Close viewer"
            onclick={(e) => { if (e.target === e.currentTarget) closeViewer(); }}
            onkeydown={(e) => { if (e.key === 'Escape') closeViewer(); }}
        >
            <!-- Controls -->
            <div class="absolute top-6 right-6 flex items-center gap-3 z-[110]">
                <div class="flex items-center gap-1 bg-slate-900/80 border border-slate-800 rounded-2xl p-1.5 shadow-2xl">
                    <button 
                        onclick={() => handleZoom(0.2)}
                        class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-slate-800 text-white transition-colors"
                        title="Zoom In"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </button>
                    <button 
                        onclick={() => handleZoom(-0.2)}
                        class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-slate-800 text-white transition-colors"
                        title="Zoom Out"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                    </button>
                    <button 
                        onclick={() => { zoomScale = 1; position = { x: 0, y: 0 }; }}
                        class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-slate-800 text-white transition-colors"
                        title="Reset"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </button>
                </div>

                <button 
                    onclick={closeViewer}
                    class="w-12 h-12 flex items-center justify-center rounded-2xl bg-red-500/10 border border-red-500/20 text-red-500 hover:bg-red-500 hover:text-white transition-all shadow-2xl"
                    title="Close"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Image Container -->
            <div 
                class="relative w-full h-full flex items-center justify-center {zoomScale > 1 ? 'cursor-grab active:cursor-grabbing' : ''}"
                onmousedown={handleMouseDown}
                role="presentation"
            >
                <img 
                    src={selectedImage} 
                    alt="Preview" 
                    class="max-w-[90%] max-h-[90%] object-contain select-none transition-transform duration-200 ease-out"
                    style="transform: translate({position.x}px, {position.y}px) scale({zoomScale})"
                    draggable="false"
                />
            </div>

            <!-- Zoom Indicator -->
            <div class="absolute bottom-10 left-1/2 -translate-x-1/2 px-4 py-2 rounded-full bg-slate-900/50 backdrop-blur-md border border-white/10 text-xs font-black tracking-widest text-white/50 uppercase">
                Zoom: {Math.round(zoomScale * 100)}%
            </div>
        </div>
    {/if}
</div>

<style>
    :global(html) {
        scroll-behavior: smooth;
        background: #020617;
    }
    
    /* Typography improvements for rich content */
    .prose :global(h2) {
        font-weight: 800;
        font-size: 2.5rem;
        letter-spacing: -0.05em;
        margin-top: 4rem;
        margin-bottom: 2rem;
        color: white;
    }
    
    .prose :global(p) {
        margin-bottom: 1.5rem;
    }

    .prose :global(img) {
        border-radius: 2rem;
        border: 1px solid rgba(255,255,255,0.1);
        margin: 3rem 0;
    }
</style>
