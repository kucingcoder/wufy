<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>@yield('title') - {{ config('app.name', 'Portfolio') }}</title>
    
    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Consolas', 'Courier New', 'monospace'],
                        mono: ['Consolas', 'Courier New', 'monospace'],
                    },
                }
            }
        }
    </script>
</head>
<body class="bg-[#020617] text-slate-100 font-sans selection:bg-sky-500/30 overflow-hidden min-h-screen flex items-center justify-center">
    <!-- Background System -->
    <div class="fixed top-0 left-0 w-full pointer-events-none z-0 overflow-hidden bg-[#020617]" style="height: 100vh;">
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-[#020617]/20 to-[#020617]/90"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,transparent_30%,#020617_100%)] opacity-60"></div>
    </div>

    <div class="relative z-10 w-full max-w-2xl px-6">
        <div class="group relative p-8 md:p-12 rounded-[2.5rem] bg-slate-900/50 backdrop-blur-md border border-white/10 text-center transition-all duration-500 hover:border-sky-500/30 hover:shadow-[0_10px_40px_-15px_rgba(14,165,233,0.3)]">
            <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-sky-500/50 to-transparent"></div>
            
            <h1 class="text-7xl md:text-9xl font-black text-transparent bg-clip-text bg-gradient-to-br from-sky-400 to-indigo-600 mb-6 tracking-tighter">
                @yield('code')
            </h1>
            
            <h2 class="text-2xl md:text-3xl font-bold text-white mb-4">
                @yield('message')
            </h2>
            
            <p class="text-slate-400 text-sm md:text-base mb-10 max-w-md mx-auto leading-relaxed">
                @yield('description', 'Maaf, terjadi kesalahan atau halaman yang Anda cari tidak dapat ditemukan.')
            </p>
            
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-slate-800 text-white font-bold hover:bg-sky-500 hover:text-white transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-sky-500/25 active:scale-95 group/btn">
                <svg class="w-5 h-5 group-hover/btn:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
