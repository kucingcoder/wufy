<script>
    import { onMount } from 'svelte';
    import { fade } from 'svelte/transition';

    let { title, content, app_url, author_name } = $props();
    const baseUrl = $derived(app_url?.replace(/\/$/, '') || '');
    let mounted = $state(false);

    onMount(() => {
        mounted = true;
        window.scrollTo(0, 0);
    });
</script>

<svelte:head>
    <title>{title} - {author_name || 'Portfolio'}</title>
    <meta name="description" content="{title} page for {author_name}" />
</svelte:head>

<div class="min-h-screen bg-slate-900 text-slate-200 selection:bg-indigo-500/30 font-sans">
    <div class="relative w-full overflow-hidden">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-40 -left-40 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Navigation -->
        <nav class="fixed top-0 inset-x-0 z-50 transition-all duration-300 bg-slate-900/80 backdrop-blur-md border-b border-white/5 py-4">
            <div class="container mx-auto px-6 max-w-6xl flex justify-between items-center">
                <a href="{baseUrl}/" class="text-xl font-bold font-heading bg-gradient-to-r from-white to-slate-400 bg-clip-text text-transparent group flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-400 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </nav>

        {#if mounted}
            <main class="container mx-auto px-6 pt-32 pb-24 max-w-4xl" in:fade={{ duration: 600, delay: 100 }}>
                <!-- Header -->
                <div class="mb-12">
                    <h1 class="text-4xl md:text-5xl font-bold font-heading text-white mb-6 leading-tight">
                        {title}
                    </h1>
                    <div class="w-20 h-1 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full"></div>
                </div>

                <!-- Content -->
                <div class="prose prose-invert prose-indigo max-w-none 
                    prose-headings:font-heading prose-headings:text-white
                    prose-h2:text-3xl prose-h2:mt-12 prose-h2:mb-6 prose-h2:pb-2 prose-h2:border-b prose-h2:border-white/10
                    prose-h3:text-2xl prose-h3:mt-8 prose-h3:mb-4
                    prose-p:text-slate-300 prose-p:leading-relaxed
                    prose-a:text-indigo-400 prose-a:no-underline hover:prose-a:text-indigo-300 hover:prose-a:underline
                    prose-strong:text-white prose-strong:font-semibold
                    prose-ul:text-slate-300 prose-ol:text-slate-300
                    prose-li:marker:text-indigo-500
                    bg-white/5 rounded-2xl p-8 md:p-12 border border-white/10 backdrop-blur-sm">
                    {@html content}
                </div>
            </main>
        {/if}
    </div>
</div>

<style>
    :global(html) {
        scroll-behavior: smooth;
    }
    :global(.font-heading) {
        font-family: 'Outfit', 'Inter', system-ui, sans-serif;
    }
</style>
