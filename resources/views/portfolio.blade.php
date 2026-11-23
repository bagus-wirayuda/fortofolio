@extends('layouts.app')
@section('title', $profile->name.' — Teknologi, Pendidikan & Kepemimpinan')
@section('description', $profile->intro)
@section('content')
<header class="site-header">
    <a class="brand" href="{{ route('home') }}" aria-label="Beranda Kalfin"><span class="brand-mark">k<span>↗</span></span><span>kalfin<span class="brand-dot">.</span></span></a>
    <nav class="desktop-nav" aria-label="Navigasi utama">
        <a href="#tentang">Tentang</a><a href="#portofolio">Portofolio</a><a href="#perjalanan">Perjalanan</a>
    </nav>
    <a class="nav-contact" href="#kontak">Mari terhubung <x-icon /></a>
    <button class="mobile-menu-toggle icon-button" aria-label="Buka menu" aria-expanded="false" aria-controls="mobile-nav"><x-icon name="menu" /></button>
    <nav id="mobile-nav" class="mobile-nav" aria-label="Navigasi seluler" hidden><a href="#tentang">Tentang</a><a href="#portofolio">Portofolio</a><a href="#perjalanan">Perjalanan</a><a href="#kontak">Kontak</a></nav>
</header>

<main id="main">
    <section class="hero section-wrap" id="beranda">
        <div class="hero-copy">
            <div class="eyebrow"><span class="tiny-line"></span> PERSONAL PORTFOLIO <span class="eyebrow-year">/ {{ date('Y') }}</span></div>
            <div class="hero-intro">Halo, saya</div>
            <h1>{{ $profile->name }}<span class="hero-name-dot">.</span></h1>
            <div class="credentials"><span class="credential-line"></span>{{ $profile->credentials }}</div>
            <p class="hero-description">{{ $profile->intro }}</p>
            <div class="hero-actions"><a class="button button-dark" href="#portofolio">Jelajahi karya saya <x-icon /></a><a class="text-button" href="{{ route('cv.download') }}">Unduh CV <x-icon name="download" /></a></div>
            <div class="hero-location"><x-icon name="pin" /><span>{{ $profile->location }}</span><span class="location-rule"></span><span>Berakar lokal. Berpikir luas.</span></div>
        </div>
        <div class="hero-visual">
            <div class="portrait-ring" aria-hidden="true"></div>
            <span class="portrait-spark" aria-hidden="true">✳</span>
            <div class="portrait-frame"><div class="portrait-frame-label"><span class="status-dot"></span> THE PERSON BEHIND THE WORK</div><x-portrait :profile="$profile" /><div class="portrait-caption"><span>Teknologi.<br>Pendidikan. Dampak.</span><span class="portrait-signature">Kalfin.</span></div></div>
            <div class="floating-note"><span class="note-icon"><x-icon name="code" /></span><div>Ide menjadi aksi.<small>Pengetahuan menjadi kontribusi.</small></div><x-icon /></div>
            <div class="availability"><span class="status-dot"></span>{{ $profile->available ? 'Terbuka untuk kolaborasi' : 'Terus berkarya dan berbagi' }}</div>
        </div>
        <a class="scroll-cue" href="#tentang"><span>SCROLL TO EXPLORE</span><span>↓</span></a>
    </section>

    <div class="expertise-strip" aria-label="Bidang kontribusi"><div><span>TEKNOLOGI INFORMASI</span><span class="strip-star">✳</span><span>PENDIDIKAN DIGITAL</span><span class="strip-star">✳</span><span>KEPEMIMPINAN</span><span class="strip-star">✳</span><span>PEMBERDAYAAN KOMUNITAS</span><span class="strip-star">✳</span></div></div>

    <section class="about-section section-wrap section-pad" id="tentang">
        <div class="section-kicker reveal"><span>01 / TENTANG SAYA</span><span>SEBUAH PERKENALAN</span></div>
        <div class="about-grid">
            <div class="reveal"><h2>{{ $profile->headline }}</h2><div class="about-motif" aria-hidden="true"><span></span><span></span><span></span><b>✳</b></div></div>
            <div class="about-copy reveal"><div class="body-text">{!! nl2br(e($profile->bio)) !!}</div><div class="focus-tags"><span><x-icon name="code" /> Teknologi</span><span><x-icon name="book" /> Pendidikan</span><span><x-icon name="users" /> Kepemimpinan</span></div><a class="underlined-link" href="#perjalanan">Kenali perjalanan saya <x-icon name="arrow-right" /></a></div>
        </div>
        <div class="about-facts reveal">
            @php($education = $entries->get('education', collect()))
            @foreach($education->take(2) as $degree)
                <div><span class="fact-label">{{ $degree->category }} / PENDIDIKAN</span><h3>{{ $degree->title }}</h3><p>{{ $degree->subtitle }}</p></div>
            @endforeach
            <div><span class="fact-label">BERBASIS DI</span><h3>{{ $profile->location }}</h3><p>Terhubung dengan berbagai kemungkinan.</p></div>
        </div>
    </section>

    <section class="work-section section-pad" id="portofolio">
        <div class="section-wrap">
            <div class="section-kicker reveal"><span>02 / PORTOFOLIO</span><span>IDE YANG DIWUJUDKAN</span></div>
            <div class="section-heading reveal"><h2>Inisiatif &<br><em>ruang kontribusi.</em></h2><p>Beberapa ruang tempat saya berkarya,<br>belajar, dan bertumbuh bersama.</p></div>
            @php($projects = $entries->get('project', collect()))
            @if($projects->isNotEmpty())
            <div class="filter-bar reveal" role="group" aria-label="Filter kategori portofolio"><button class="filter-button active" data-filter="all" aria-pressed="true">Semua inisiatif <span>{{ $projects->count() }}</span></button>@foreach($projects->pluck('category')->filter()->unique() as $category)<button class="filter-button" data-filter="{{ $category }}" aria-pressed="false">{{ $category }}</button>@endforeach</div>
            <div class="project-grid">
                @foreach($projects as $project)
                <article class="project-card reveal" data-category="{{ $project->category }}">
                    <button class="project-open" data-project-open="project-{{ $project->id }}" aria-label="Lihat detail {{ $project->title }}">
                        <div class="project-art art-{{ $loop->index % 3 }}">
                            @if($project->image_path)<img src="{{ Storage::disk('public')->url($project->image_path) }}" alt="{{ $project->title }}" loading="lazy" width="700" height="500">
                            @else
                                <span class="art-topline">{{ $project->category ?? 'INISIATIF' }} <span>↗</span></span>
                                <div class="art-emblem" aria-hidden="true">@if($loop->index % 3 === 0)<span class="pixel-symbol"><i></i><i></i><i></i><i></i></span>@elseif($loop->index % 3 === 1)<span class="book-symbol"><i></i><i></i></span>@else<span class="sun-symbol">✳</span>@endif</div>
                                <div class="art-name">{{ $project->title }}</div><span class="art-bottomline">{{ $project->subtitle }} <span>{{ $project->period }}</span></span>
                            @endif
                            <span class="project-arrow"><x-icon /></span>
                        </div>
                        <div class="project-meta"><span>{{ $project->category }}</span><span>{{ $project->period }}</span></div>
                        <h3>{{ $project->title }}</h3><p>{{ Str::limit($project->description, 120) }}</p>
                    </button>
                </article>
                <dialog id="project-{{ $project->id }}" class="project-dialog"><button class="dialog-close icon-button" data-dialog-close aria-label="Tutup detail"><x-icon name="close" /></button><span class="eyebrow">{{ $project->category }} / {{ $project->period }}</span><h2>{{ $project->title }}</h2><span class="project-role">{{ $project->subtitle }}</span><div class="body-text">{!! nl2br(e($project->description)) !!}</div>@if($project->url)<a class="button button-dark" href="{{ $project->url }}" target="_blank" rel="noopener noreferrer">Kunjungi website <x-icon /></a>@else<a class="button button-dark" href="#kontak" data-dialog-close>Diskusikan kolaborasi <x-icon /></a>@endif</dialog>
                @endforeach
            </div>
            @else<div class="empty-state"><x-icon name="grid" /><p>Inisiatif baru akan hadir di sini.</p></div>@endif
            <div class="work-footnote"><span>Setiap langkah punya cerita. Setiap ide punya potensi.</span><a href="#kontak">Punya ide untuk dibicarakan? <x-icon /></a></div>
        </div>
    </section>

    <section class="journey-section section-wrap section-pad" id="perjalanan">
        <div class="section-kicker reveal"><span>03 / PERJALANAN</span><span>PROSES YANG MEMBENTUK SAYA</span></div>
        <div class="journey-layout">
            <div class="journey-intro reveal"><h2>Terus belajar.<br>Terus <em>bergerak.</em></h2><p>Lintasan pengalaman yang mempertemukan ilmu, kepemimpinan, dan kehidupan bermasyarakat.</p><a class="text-button" href="{{ route('cv.download') }}">Lihat CV lengkap <x-icon name="download" /></a><div class="journey-graphic" aria-hidden="true"><span>↗</span><div>GROWTH IS<br>A JOURNEY.</div></div></div>
            <div class="journey-content reveal">
                <div class="journey-tabs" role="tablist" aria-label="Riwayat profesional">
                    @foreach(['experience' => 'Pengalaman', 'education' => 'Pendidikan', 'organization' => 'Organisasi'] as $kind => $label)<button id="tab-{{ $kind }}" role="tab" aria-selected="{{ $loop->first ? 'true' : 'false' }}" aria-controls="panel-{{ $kind }}" tabindex="{{ $loop->first ? '0' : '-1' }}" data-tab="{{ $kind }}" class="{{ $loop->first ? 'active' : '' }}">{{ $label }}<span>{{ $entries->get($kind, collect())->count() }}</span></button>@endforeach
                </div>
                @foreach(['experience', 'education', 'organization'] as $kind)
                <div id="panel-{{ $kind }}" role="tabpanel" aria-labelledby="tab-{{ $kind }}" data-tab-panel="{{ $kind }}" @if(!$loop->first) hidden @endif tabindex="0" class="timeline">
                    @forelse($entries->get($kind, collect()) as $item)
                    <article class="timeline-item"><span class="timeline-dot"></span><div class="timeline-date">{{ $item->period ?? 'Pendidikan dasar' }} @if(Str::contains($item->period, 'Sekarang'))<span class="current-tag">Aktif</span>@endif</div><h3>{{ $item->title }}</h3><p>{{ $item->subtitle }}</p>@if($item->description)<div class="timeline-description">{{ $item->description }}</div>@endif</article>
                    @empty<p class="empty-state">Riwayat akan ditambahkan di sini.</p>@endforelse
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="principles-section">
        <div class="section-wrap principles-inner reveal"><span class="giant-asterisk" aria-hidden="true">✳</span><div><span class="eyebrow">SEMANGAT DALAM SETIAP LANGKAH</span><p>“Pengetahuan untuk dibagikan.<br>Teknologi untuk <em>memberi arti.</em>”</p><span class="principles-byline">SEBUAH SEMANGAT UNTUK TERUS BERKONTRIBUSI</span></div></div>
    </section>

    <section class="contact-section section-wrap section-pad" id="kontak">
        <div class="section-kicker reveal"><span>04 / MARI TERHUBUNG</span><span>PERCAKAPAN ADALAH AWAL</span></div>
        <div class="contact-grid">
            <div class="reveal"><h2>Hal baik dimulai<br>dari sebuah <em>halo.</em><span class="hello-dot">●</span></h2><p>Kolaborasi teknologi, pendidikan, atau sekadar bertukar ide.<br>Saya senang mendengar cerita Anda.</p><a href="mailto:{{ $profile->email }}" class="contact-email">{{ $profile->email }} <x-icon /></a></div>
            <div class="contact-options reveal"><a href="{{ $profile->whatsappUrl() }}" target="_blank" rel="noopener noreferrer"><span class="contact-option-icon"><x-icon name="phone" /></span><div><small>WHATSAPP</small><span>{{ $profile->phone }}</span></div><x-icon /></a><div class="contact-location"><span class="contact-option-icon"><x-icon name="pin" /></span><div><small>LOKASI</small><span>{{ $profile->location }}</span></div></div>@if($profile->website)<a href="{{ $profile->website }}" target="_blank" rel="noopener noreferrer"><span class="contact-option-icon"><x-icon name="globe" /></span><div><small>WEBSITE</small><span>{{ parse_url($profile->website, PHP_URL_HOST) }}</span></div><x-icon /></a>@endif</div>
        </div>
    </section>
</main>
<footer class="site-footer section-wrap"><a class="brand" href="#beranda"><span class="brand-mark">k<span>↗</span></span><span>kalfin<span class="brand-dot">.</span></span></a><p>© {{ date('Y') }} {{ $profile->name }}. Dibuat dengan tujuan.</p><div><a href="{{ route('admin.index') }}">Admin <x-icon name="lock" /></a><a class="back-top" href="#beranda" aria-label="Kembali ke atas">↑</a></div></footer>
@endsection
