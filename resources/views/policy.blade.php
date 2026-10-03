@extends('layouts.main')

@php
    $profile = \App\Models\Profile::first();
    $metaDescription = strip_tags($content ?? '');
    if (strlen($metaDescription) > 155) {
        $metaDescription = substr($metaDescription, 0, 152) . '...';
    }
    
    $keywordsArray = explode(' ', $title);
    $keywordsArray[] = 'Policy';
    $keywordsArray[] = 'Legal';
    if ($profile) {
        $keywordsArray[] = $profile->full_name;
        $keywordsArray[] = $profile->job_title;
        if ($profile->seo_keywords) {
            $customKeywords = array_map('trim', explode(',', $profile->seo_keywords));
            $keywordsArray = array_merge($keywordsArray, $customKeywords);
        }
    }
    $metaKeywords = implode(', ', array_filter(array_unique($keywordsArray)));
    
    $jsonLd = [
        "@context" => "https://schema.org",
        "@type" => "WebPage",
        "name" => $title . ' - ' . ($author_name ?? config('app.name')),
        "description" => $metaDescription,
        "url" => url()->current(),
        "publisher" => [
            "@type" => "Organization",
            "name" => $author_name ?? config('app.name'),
            "logo" => [
                "@type" => "ImageObject",
                "url" => url('/icon.webp')
            ]
        ]
    ];
@endphp

@section('title', $title . ' - ' . ($author_name ?? config('app.name')))
@section('meta_description', $metaDescription)
@section('meta_keywords', $metaKeywords)
@section('og_type', 'website')
@section('og_title', $title . ' - ' . ($author_name ?? config('app.name')))
@section('og_description', $metaDescription)
@section('og_image', url('/icon.webp'))
@section('twitter_title', $title . ' - ' . ($author_name ?? config('app.name')))
@section('twitter_description', $metaDescription)
@section('twitter_image', url('/icon.webp'))

@section('head')
<script type="application/ld+json">
{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endsection

@section('content')
<div class="min-h-screen text-slate-200 selection:bg-indigo-500/30 font-sans relative z-10">
    <div class="relative w-full overflow-hidden">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-40 -left-40 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Navigation -->
        <nav class="fixed top-0 inset-x-0 z-50 transition-all duration-300 bg-slate-950/50 backdrop-blur-md border-b border-slate-800/50 py-4">
            <div class="container mx-auto px-6 h-12 flex justify-between items-center">
                <a href="{{ url('/') }}" class="text-xl font-bold font-heading text-slate-400 hover:text-sky-400 transition-colors group flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-slate-900 border border-slate-800 flex items-center justify-center group-hover:border-sky-500/50 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </div>
                    <span class="font-bold uppercase tracking-widest text-xs">Kembali</span>
                </a>
            </div>
        </nav>

        <main class="container mx-auto px-6 pt-32 pb-24 max-w-4xl">
            <!-- Header -->
            <div class="mb-12">
                <h1 class="text-4xl md:text-5xl font-black tracking-tighter text-white mb-6 leading-none">
                    {{ $title }}
                </h1>
                <div class="h-1.5 w-20 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(14,165,233,0.5)]"></div>
            </div>

            <!-- Content -->
            <div class="bg-slate-900/95 backdrop-blur-2xl border border-slate-800 p-8 sm:p-12 rounded-[2.5rem] shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-sky-500/5 blur-[100px] rounded-full pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-indigo-500/5 blur-[100px] rounded-full pointer-events-none"></div>
                
                <div class="prose prose-invert prose-sky max-w-none relative z-10 text-justify
                    prose-headings:font-bold prose-headings:text-white
                    prose-h2:text-3xl prose-h2:mt-12 prose-h2:mb-6 prose-h2:pb-2 prose-h2:border-b prose-h2:border-slate-800
                    prose-h3:text-2xl prose-h3:mt-8 prose-h3:mb-4
                    prose-p:text-slate-200 prose-p:leading-relaxed
                    prose-a:text-sky-400 prose-a:no-underline hover:prose-a:text-sky-300 hover:prose-a:underline
                    prose-strong:text-white prose-strong:font-semibold
                    prose-ul:text-slate-200 prose-ol:text-slate-200
                    prose-li:marker:text-sky-500">
                    {!! $content !!}
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
