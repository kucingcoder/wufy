<script>
    import { onMount, onDestroy } from 'svelte';
    import { fade } from 'svelte/transition';
    import PhotoSwipeLightbox from 'photoswipe/lightbox';
    import 'photoswipe/style.css';

    let { project, app_url, author_name } = $props();
    const baseUrl = $derived(app_url?.replace(/\/$/, '') || '');
    let mounted = $state(false);
    let lightbox = null;

    onMount(() => {
        mounted = true;
        
        lightbox = new PhotoSwipeLightbox({
            gallery: '#project-gallery',
            children: 'a',
            pswpModule: () => import('photoswipe'),
            // Add some padding for the UI
            padding: { top: 20, bottom: 20, left: 20, right: 20 },
        });

        lightbox.on('contentLoad', (e) => {
            const { content } = e;
            if (content.type === 'image') {
                const img = new Image();
                img.src = content.data.src;
                img.onload = () => {
                    if (content.data.width !== img.width || content.data.height !== img.height) {
                        content.data.width = img.width;
                        content.data.height = img.height;
                        if (lightbox.pswp) {
                            lightbox.pswp.refreshLazy(e.index);
                        }
                    }
                };
            }
        });

        lightbox.init();
    });

    onDestroy(() => {
        if (lightbox) {
            lightbox.destroy();
            lightbox = null;
        }
    });

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
    {@html `<script type="application/ld+json">${jsonLd}<\/script>`}
</svelte:head>

<div class="bg-[#020617] text-slate-100 font-['Plus_Jakarta_Sans'] selection:bg-sky-500/30 min-h-screen overflow-x-hidden">
    
    <!-- Background Gradient -->
    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-sky-600/10 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-indigo-600/5 blur-[120px] rounded-full"></div>
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
                    <div class="flex items-end justify-between mb-16">
                        <div>
                            <h2 class="text-4xl lg:text-6xl font-black tracking-tight mb-4">Project <span class="text-sky-500">Gallery</span></h2>
                            <div class="h-1.5 w-20 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(14,165,233,0.5)]"></div>
                        </div>
                        <p class="text-slate-500 font-bold uppercase tracking-widest text-xs hidden sm:block">Click to expand</p>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="project-gallery">
                        {#each project.galleries as gallery}
                            <a 
                                href="/storage/{gallery.image_path}" 
                                target="_blank"
                                class="group flex flex-col gap-4 text-left outline-none"
                            >
                                <div class="relative overflow-hidden bg-slate-900 border border-slate-800 w-full shadow-xl transition-all duration-500 group-hover:shadow-sky-500/10 group-hover:-translate-y-1">
                                    <img 
                                        src="/storage/{gallery.image_path}" 
                                        alt={gallery.title || project.title} 
                                        class="w-full h-auto transition-transform duration-[1s] ease-out group-hover:scale-110" 
                                        onload={(e) => {
                                            const a = e.currentTarget.closest('a');
                                            if (a) {
                                                a.dataset.pswpWidth = e.currentTarget.naturalWidth;
                                                a.dataset.pswpHeight = e.currentTarget.naturalHeight;
                                            }
                                        }}
                                    />
                                    
                                    <!-- Premium Overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent opacity-0 group-hover:opacity-40 transition-opacity duration-500"></div>
                                    
                                    <!-- Zoom Icon -->
                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-500 scale-90 group-hover:scale-100">
                                        <div class="w-14 h-14 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white flex items-center justify-center shadow-2xl">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                        </div>
                                    </div>
                                </div>
                                {#if gallery.title}
                                    <div class="px-2">
                                        <span class="text-sm font-bold text-slate-400 group-hover:text-sky-400 transition-colors tracking-tight line-clamp-1">{gallery.title}</span>
                                    </div>
                                {/if}
                            </a>
                        {/each}
                    </div>
                </div>
            {/if}

        </article>
    </main>

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

    /* PhotoSwipe Customization */
    :global(.pswp) {
        --pswp-bg: rgba(2, 6, 23, 0.98);
        --pswp-placeholder-bg: #0f172a;
        --pswp-root-z-index: 1000;
    }

    :global(.pswp__img) {
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }

    :global(.pswp__button) {
        background: rgba(255, 255, 255, 0.05) !important;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 12px !important;
        margin: 10px !important;
        transition: all 0.3s !important;
    }

    :global(.pswp__button:hover) {
        background: rgba(255, 255, 255, 0.1) !important;
        border-color: rgba(14, 165, 233, 0.5) !important;
        color: #0ea5e9 !important;
    }
</style>
