<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ $page['props']['profile']['full_name'] ?? ($page['props']['project']['title'] ?? config('app.name', 'Laravel')) }}</title>
        <meta name="description" content="{{ $page['props']['profile']['description'] ?? ($page['props']['project']['short_description'] ?? '') }}">
        
        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:title" content="{{ $page['props']['profile']['full_name'] ?? ($page['props']['project']['title'] ?? config('app.name', 'Laravel')) }}">
        <meta property="og:description" content="{{ $page['props']['profile']['description'] ?? ($page['props']['project']['short_description'] ?? '') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia

        {{-- Hidden content for SEO/AI/Crawlers --}}
        <div style="display: none;" aria-hidden="true">
            <h1>{{ $page['props']['profile']['full_name'] ?? config('app.name', 'Laravel') }}</h1>
            <h2>{{ $page['props']['profile']['job_title'] ?? '' }}</h2>
            <p>{{ $page['props']['profile']['description'] ?? '' }}</p>
            
            @if(isset($page['props']['visionMission']))
                <section>
                    <h3>Visi</h3>
                    <p>{{ $page['props']['visionMission']['vision'] ?? '' }}</p>
                    <h3>Misi</h3>
                    <ul>
                        @foreach($page['props']['visionMission']['missions'] ?? [] as $mission)
                            <li>{{ $mission }}</li>
                        @endforeach
                    </ul>
                </section>
            @endif

            @if(isset($page['props']['projects']))
                <section>
                    <h3>Proyek</h3>
                    @foreach($page['props']['projects'] as $project)
                        <article>
                            <h4>{{ $project['title'] }}</h4>
                            <p>{{ $project['short_description'] }}</p>
                        </article>
                    @endforeach
                </section>
            @endif
        </div>
    </body>
</html>
