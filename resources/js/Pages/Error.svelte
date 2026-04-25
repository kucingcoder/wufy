<script>
    import { fade, fly } from 'svelte/transition';

    let { status } = $props();

    let title = $derived({
        503: '503: Service Unavailable',
        500: '500: Server Error',
        404: '404: Page Not Found',
        403: '403: Forbidden',
    }[status] || 'Error');

    let description = $derived({
        503: 'Sorry, we are doing some maintenance. Please check back soon.',
        500: 'Whoops, something went wrong on our servers.',
        404: 'Sorry, the page you are looking for could not be found.',
        403: 'Sorry, you are forbidden from accessing this page.',
    }[status] || 'An unexpected error has occurred.');
</script>

<svelte:head>
    <title>{title} | Wufy</title>
    <link rel="icon" type="image/webp" href="/icon.webp">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
</svelte:head>

<div class="bg-[#020617] text-slate-100 font-['Plus_Jakarta_Sans'] selection:bg-sky-500/30 overflow-hidden min-h-screen flex items-center justify-center p-6 relative">
    
    <!-- Background System -->
    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,#0f172a_0%,#020617_100%)]"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-sky-600/10 blur-[120px] rounded-full"></div>
    </div>

    <div class="relative z-10 text-center max-w-2xl">
        <div transition:fly={{ y: 20, duration: 800 }}>
            <h1 class="text-8xl lg:text-[12rem] font-black tracking-tighter text-white opacity-10 mb-[-2rem] lg:mb-[-4rem]">
                {status}
            </h1>
            
            <div class="relative inline-block mb-12">
                <div class="absolute -inset-4 bg-sky-500/20 blur-xl rounded-full opacity-50 animate-pulse"></div>
                <div class="relative w-24 h-24 rounded-3xl bg-slate-900 border border-slate-800 flex items-center justify-center shadow-2xl">
                    <svg class="w-12 h-12 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.268 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>

            <h2 class="text-4xl lg:text-6xl font-black text-white mb-6 tracking-tight">
                {title.split(': ')[1]}
            </h2>
            
            <p class="text-slate-400 text-lg lg:text-xl font-light mb-12 leading-relaxed">
                {description}
            </p>

            <a 
                href="/" 
                class="inline-flex items-center gap-3 px-8 py-4 rounded-2xl bg-white text-slate-950 font-bold hover:bg-sky-500 hover:text-white transition-all shadow-xl hover:shadow-sky-500/20 active:scale-95 group"
            >
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<style>
    :global(body) {
        background: #020617;
    }
</style>
