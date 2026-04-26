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
            <section>
                <h1>Biodata & Kontak</h1>
                <p>Nama: {{ $page['props']['profile']['full_name'] ?? config('app.name', 'Laravel') }}</p>
                <p>Posisi: {{ $page['props']['profile']['job_title'] ?? '' }}</p>
                <p>Deskripsi: {{ $page['props']['profile']['description'] ?? '' }}</p>
                <p>Lokasi: {{ $page['props']['profile']['city'] ?? '' }}, {{ $page['props']['profile']['province'] ?? '' }}</p>
                <p>Email: {{ $page['props']['profile']['email'] ?? '' }}</p>
                <p>Telepon: {{ $page['props']['profile']['phone'] ?? '' }}</p>
                @if(isset($page['props']['profile']['links']))
                    <ul>
                        @foreach($page['props']['profile']['links'] as $link)
                            <li>{{ $link['title'] }}: {{ $link['link'] }}</li>
                        @endforeach
                    </ul>
                @endif
            </section>

            @if(isset($page['props']['skills']))
                <section>
                    <h2>Kemampuan</h2>
                    <ul>
                        @foreach($page['props']['skills'] as $skill)
                            <li><strong>{{ $skill['title'] }}</strong>: {{ $skill['description'] ?? '' }}</li>
                        @endforeach
                    </ul>
                </section>
            @endif

            @if(isset($page['props']['experiences']))
                <section>
                    <h2>Pengalaman</h2>
                    @foreach($page['props']['experiences'] as $exp)
                        <article>
                            <h3>{{ $exp['position'] }} di {{ $exp['company'] }}</h3>
                            <p>Periode: {{ $exp['start_date'] }} - {{ $exp['end_date'] ?? 'Sekarang' }}</p>
                            <p>Lokasi: {{ $exp['location_text'] ?? '' }}</p>
                            <p>Deskripsi: {{ $exp['description'] }}</p>
                        </article>
                    @endforeach
                </section>
            @endif

            @if(isset($page['props']['education']))
                <section>
                    <h2>Pendidikan</h2>
                    @foreach($page['props']['education'] as $edu)
                        <article>
                            <h3>{{ $edu['major'] }} - {{ $edu['institution'] }}</h3>
                            <p>Lulus: {{ $edu['graduation_date'] }}</p>
                            <p>Gelar: {{ $edu['degree'] ?? '' }} (IPK: {{ $edu['gpa'] ?? '' }})</p>
                        </article>
                    @endforeach
                </section>
            @endif

            @if(isset($page['props']['certificates']))
                <section>
                    <h2>Sertifikat</h2>
                    @foreach($page['props']['certificates'] as $cert)
                        <article>
                            <h3>{{ $cert['title'] }}</h3>
                            <p>Penerbit: {{ $cert['issuer'] }}</p>
                            <p>Level: {{ $cert['level'] ?? '' }}</p>
                            <p>Kategori: {{ $cert['category'] ?? '' }}</p>
                            <p>Masa Berlaku: {{ $cert['start_date'] }} - {{ $cert['end_date'] ?? 'Seumur Hidup' }}</p>
                        </article>
                    @endforeach
                </section>
            @endif
        </div>
    </body>
</html>
