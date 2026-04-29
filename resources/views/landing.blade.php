@extends('layouts.guest')

@section('title', 'GuardYou — Elite Personal Protection')
@section('meta_description', 'Platform jasa bodyguard profesional terverifikasi. Pesan perlindungan personal kapan saja.')

@push('styles')
<style>
    /* ── HERO FULLSCREEN ── */
    .hero {
        position: relative;
        height: calc(100vh - 72px);
        min-height: 600px;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        overflow: hidden;
    }
    .hero-bg {
        position: absolute; inset: 0;
        background: url('{{ asset('image/hero.jpeg') }}') center center / cover no-repeat;
        transition: transform 0.3s ease-out; /* For parallax */
        transform: scale(1.1);
    }
    .hero-overlay {
        position: absolute; inset: 0;
        background:
            linear-gradient(to top, rgba(8,12,20,1) 0%, rgba(8,12,20,0.75) 40%, rgba(8,12,20,0.2) 100%),
            linear-gradient(to right, rgba(8,12,20,0.7) 0%, rgba(8,12,20,0.1) 60%, transparent 100%),
            linear-gradient(135deg, rgba(176,16,48,0.25) 0%, transparent 55%);
    }
    .hero-content {
        position: relative; z-index: 2;
        padding: 0 4rem 5rem;
        max-width: 800px;
    }
    .hero-title {
        font-size: clamp(3rem, 6vw, 5.5rem);
        font-weight: 900;
        line-height: 1.0;
        margin-bottom: 1.5rem;
        color: #fff;
        letter-spacing: -0.03em;
    }
    .hero-title .highlight { 
        color: var(--color-gold); 
        position: relative;
    }
    /* Typing Cursor */
    .typing-cursor {
        display: inline-block;
        width: 4px;
        height: 0.9em;
        background-color: var(--color-gold);
        margin-left: 4px;
        vertical-align: text-bottom;
        animation: blink 1s step-end infinite;
    }
    @keyframes blink {
        50% { opacity: 0; }
    }

    .hero-subtitle {
        font-size: 1.05rem;
        color: rgba(255,255,255,0.65);
        max-width: 480px;
        line-height: 1.75;
        margin-bottom: 2.5rem;
    }
    .hero-btns { display: flex; gap: 1rem; flex-wrap: wrap; }

    /* Badge floating di kanan bawah */
    .hero-badge {
        position: absolute; z-index: 2;
        bottom: 5rem; right: 4rem;
        background: rgba(8,12,20,0.8);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(220,20,60,0.2);
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        display: flex; align-items: center; gap: 1rem;
        max-width: 280px;
        transition: transform 0.3s ease-out; /* For parallax */
        box-shadow: 0 0 20px rgba(220,20,60,0.1);
        animation: pulse-glow 3s infinite;
    }
    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 0 20px rgba(220,20,60,0.1); }
        50% { box-shadow: 0 0 30px rgba(220,20,60,0.3); }
    }
    .hero-badge-icon {
        width: 44px; height: 44px; border-radius: 50%;
        background: var(--color-gold-bg);
        border: 1px solid rgba(220,20,60,0.3);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; flex-shrink: 0;
        color: var(--color-gold);
        font-weight: 900;
    }
    .hero-badge-text { font-size: 0.82rem; font-weight: 700; color: #fff; }
    .hero-badge-sub  { font-size: 0.7rem; color: var(--color-on-surface-muted); margin-top: 0.2rem; line-height: 1.4; }

    /* Scroll indicator */
    .hero-scroll {
        position: absolute; bottom: 2rem; left: 50%;
        transform: translateX(-50%);
        z-index: 2;
        display: flex; flex-direction: column; align-items: center; gap: 0.4rem;
        color: rgba(255,255,255,0.3);
        font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.2em;
        cursor: pointer;
        animation: fadeUpDown 2s ease-in-out infinite;
    }
    .hero-scroll-line {
        width: 1px; height: 48px;
        background: linear-gradient(to bottom, rgba(220,20,60,0.5), transparent);
    }
    @keyframes fadeUpDown {
        0%, 100% { opacity: 0.4; transform: translateX(-50%) translateY(0); }
        50%       { opacity: 1;   transform: translateX(-50%) translateY(6px); }
    }

    /* ── SCROLL REVEAL ANIMATION ── */
    .reveal {
        opacity: 0;
        transform: translateY(40px);
        transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }
    .reveal-delay-1 { transition-delay: 0.1s; }
    .reveal-delay-2 { transition-delay: 0.2s; }
    .reveal-delay-3 { transition-delay: 0.3s; }

    /* ── FEATURES — full section ── */
    .features-section {
        min-height: 100vh;
        display: flex; flex-direction: column; justify-content: center;
        padding: 6rem 4rem;
        position: relative;
        border-top: 1px solid var(--color-border);
    }
    .features-section::before {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(ellipse 70% 60% at 50% 50%, rgba(220,20,60,0.025) 0%, transparent 70%);
        pointer-events: none;
    }
    .features-inner { max-width: 1200px; margin: 0 auto; width: 100%; }
    .features-header { text-align: center; margin-bottom: 4rem; }
    .features-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
    }
    .feature-card {
        background: var(--color-surface-container);
        border: 1px solid var(--color-border);
        border-radius: 16px;
        padding: 2.5rem 2rem;
        transition: border-color 0.25s ease, transform 0.25s ease, box-shadow 0.25s ease;
    }
    .feature-card:hover {
        border-color: rgba(220,20,60,0.3);
        transform: translateY(-6px);
        box-shadow: 0 20px 50px rgba(0,0,0,0.4);
    }
    .feature-icon-wrap {
        width: 56px; height: 56px;
        background: var(--color-gold-bg);
        border: 1px solid rgba(220,20,60,0.2);
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        transition: transform 0.3s ease;
    }
    .feature-card:hover .feature-icon-wrap {
        transform: scale(1.1) rotate(5deg);
    }
    .feature-title {
        font-size: 1.1rem; font-weight: 800;
        color: #fff; margin-bottom: 0.75rem;
    }
    .feature-desc {
        font-size: 0.875rem;
        color: var(--color-on-surface-muted);
        line-height: 1.7;
    }

    /* ── AGENTS — full section ── */
    .agents-section {
        min-height: 100vh;
        display: flex; flex-direction: column; justify-content: center;
        padding: 6rem 4rem;
        border-top: 1px solid var(--color-border);
        background: var(--color-surface-container);
    }
    .agents-inner { max-width: 1200px; margin: 0 auto; width: 100%; }
    .agents-header {
        display: flex; justify-content: space-between; align-items: flex-end;
        margin-bottom: 3rem;
    }
    .agents-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    .agent-card {
        position: relative; 
        height: 440px;
        border-radius: 14px; overflow: hidden;
        background: var(--color-surface-high);
        border: 1px solid var(--color-border);
        text-decoration: none; color: inherit;
        display: block;
        transition: border-color 0.25s ease, transform 0.3s ease, box-shadow 0.3s ease;
    }
    .agent-card:hover {
        border-color: rgba(220,20,60,0.35);
        transform: translateY(-6px);
        box-shadow: 0 24px 60px rgba(0,0,0,0.5);
    }
    .agent-img-wrap {
        position: absolute; inset: 0;
        overflow: hidden;
    }
    .agent-img-wrap img {
        width: 100%; height: 100%; object-fit: cover;
        filter: grayscale(100%);
        transition: filter 0.4s ease, transform 0.5s ease;
    }
    .agent-card:hover .agent-img-wrap img {
        filter: grayscale(0%);
        transform: scale(1.05);
    }
    .agent-initial-bg {
        width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center;
        font-family: var(--font-display);
        font-size: 7rem; font-weight: 900;
        color: rgba(220,20,60,0.2);
        background: linear-gradient(135deg, #111827, #0d1117);
    }
    .agent-gradient {
        position: absolute; inset: 0;
        background: linear-gradient(0deg, rgba(8,12,20,0.95) 0%, rgba(8,12,20,0.3) 55%, transparent 100%);
    }
    .agent-rate {
        position: absolute; top: 1.25rem; right: 1.25rem;
        background: var(--color-gold);
        color: #ffffff;
        padding: 0.4rem 0.85rem;
        border-radius: 6px;
        font-family: var(--font-display);
        font-size: 0.73rem; font-weight: 900;
        letter-spacing: 0.02em;
        opacity: 0; transform: translateY(-10px);
        transition: all 0.3s ease;
    }
    .agent-card:hover .agent-rate {
        opacity: 1; transform: translateY(0);
    }
    .agent-info {
        position: absolute; bottom: 0; left: 0; right: 0;
        padding: 1.75rem;
        transform: translateY(10px);
        transition: transform 0.3s ease;
    }
    .agent-card:hover .agent-info {
        transform: translateY(0);
    }
    .agent-name {
        font-family: var(--font-display);
        font-size: 1.25rem; font-weight: 900;
        text-transform: uppercase; letter-spacing: -0.01em;
        margin-bottom: 0.3rem;
    }
    .agent-meta {
        font-size: 0.7rem; color: var(--color-gold);
        font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em;
    }

    /* ── CTA — full section ── */
    .cta-section {
        position: relative;
        min-height: 100vh;
        display: flex; align-items: center; justify-content: center;
        text-align: center;
        overflow: hidden;
    }
    .cta-bg {
        position: absolute; inset: 0;
        background: url('{{ asset('image/backg.jpeg') }}') center center / cover;
    }
    .cta-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(135deg, rgba(8,12,20,0.94) 0%, rgba(8,12,20,0.82) 100%);
    }
    .cta-content { position: relative; z-index: 2; max-width: 680px; margin: 0 auto; padding: 4rem 2rem; }
    .cta-title {
        font-size: clamp(2.2rem, 4.5vw, 3.5rem);
        font-weight: 900; color: #fff;
        margin-bottom: 1.25rem; line-height: 1.1;
        letter-spacing: -0.02em;
    }
    .cta-title span { color: var(--color-gold); }
    .cta-subtitle {
        color: var(--color-on-surface-muted);
        font-size: 1.05rem; line-height: 1.75;
        margin-bottom: 3rem;
    }
    .cta-btns { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }

    /* ── RESPONSIVE ── */
    @media (max-width: 1024px) {
        .hero-content { padding: 0 2.5rem 4rem; }
        .hero-badge { bottom: 3.5rem; right: 2.5rem; }
        .features-section, .agents-section { padding: 5rem 2.5rem; }
        .features-grid { grid-template-columns: repeat(2, 1fr); } /* 2 columns on tablet */
    }
    @media (max-width: 768px) {
        .hero-badge { display: none; }
        .hero-content { padding: 0 1.5rem 3.5rem; }
        .features-grid { grid-template-columns: 1fr; }
        .agents-grid { grid-template-columns: 1fr; }
        .agents-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
        .features-section, .agents-section { padding: 4rem 1.5rem; min-height: auto; }
        .cta-section { min-height: auto; padding: 6rem 0; }
        .agent-card { height: 380px; }
    }
    @media (max-width: 600px) {
        .hero-title { font-size: 2.5rem; }
        .navbar { padding: 1rem 1.25rem; }
        .navbar-links { display: none; }
    }
</style>
@endpush

@section('content')

<!-- ── HERO FULLSCREEN ── -->
<section class="hero" id="hero">
    <div class="hero-bg" id="hero-bg"></div>
    <div class="hero-overlay"></div>

    <div class="hero-content">
        <div class="eyebrow" style="margin-bottom:1.5rem;">Elite Security Personnel</div>
        <h1 class="hero-title">
            Perlindungan<br>
            <span class="highlight" id="typed-text"></span><span class="typing-cursor">&nbsp;</span><br>
            Kapan Saja.
        </h1>
        <p class="hero-subtitle">
            Bodyguard profesional terverifikasi siap melindungi Anda dan aset berharga Anda. Diskrit, sigap, dan terpercaya.
        </p>
        <div class="hero-btns">
            <a href="{{ route('bodyguards.index') }}" class="btn-primary btn-lg">Cari Bodyguard</a>
            <a href="{{ route('bodyguard.landing') }}" class="btn-outline btn-lg">Daftar Sekarang</a>
        </div>
    </div>

    <!-- Floating badge -->
    <div class="hero-badge" id="hero-badge">
        <div class="hero-badge-icon">✓</div>
        <div>
            <div class="hero-badge-text">Semua Bodyguard Terverifikasi</div>
            <div class="hero-badge-sub">Latar belakang & lisensi dicek secara menyeluruh</div>
        </div>
    </div>

    <!-- Scroll indicator -->
    <div class="hero-scroll" id="scroll-down">
        <div class="hero-scroll-line"></div>
        <span>scroll</span>
    </div>
</section>

<!-- ── FEATURES ── -->
<section class="features-section" id="features">
    <div class="features-inner">
        <div class="features-header reveal">
            <div class="eyebrow" style="justify-content:center; margin-bottom:0.75rem;">Keunggulan Kami</div>
            <h2 class="section-title" style="text-align:center; margin-bottom:1rem;">Superior Security <span>Service</span></h2>
            <p style="text-align:center; color:var(--color-on-surface-muted); max-width:480px; margin:0 auto; font-size:0.95rem; line-height:1.7;">
                Layanan keamanan personal standar internasional yang telah dipercaya oleh ribuan klien.
            </p>
        </div>

        <div class="features-grid">
            <div class="feature-card reveal reveal-delay-1">
                <div class="feature-icon-wrap">🛡️</div>
                <div class="feature-title">Profesional Terverifikasi</div>
                <p class="feature-desc">Setiap bodyguard menjalani pemeriksaan latar belakang ketat dan protokol pelatihan tingkat tinggi sebelum bergabung.</p>
            </div>
            <div class="feature-card reveal reveal-delay-2">
                <div class="feature-icon-wrap">⚡</div>
                <div class="feature-title">Pemesanan Real-Time</div>
                <p class="feature-desc">Booking langsung melalui platform kami. Perlindungan premium tersedia hanya dengan beberapa klik.</p>
            </div>
            <div class="feature-card reveal reveal-delay-3">
                <div class="feature-icon-wrap">⏱️</div>
                <div class="feature-title">Perlindungan 24/7</div>
                <p class="feature-desc">Pengawasan fisik dan keamanan personal sepanjang waktu. Keselamatan Anda tidak pernah berhenti.</p>
            </div>
        </div>
    </div>
</section>

<!-- ── FEATURED AGENTS ── -->
@if($featuredBodyguards->isNotEmpty())
<section class="agents-section">
    <div class="agents-inner">
        <div class="agents-header reveal">
            <div>
                <div class="eyebrow" style="margin-bottom:0.75rem;">Operatif Lapangan Aktif</div>
                <h2 class="section-title">Agen <span>Unggulan Kami</span></h2>
            </div>
            <a href="{{ route('bodyguards.index') }}" class="btn-outline" style="font-size:0.72rem;">Lihat Semua →</a>
        </div>

        <div class="agents-grid">
            @foreach($featuredBodyguards as $bodyguard)
            @php
                $gradients = ['135deg,#111827,#0d1117','135deg,#12111f,#0a0d17','135deg,#111f18,#0d1712','135deg,#1a1511,#17110d'];
                $g = $gradients[$loop->index % count($gradients)];
            @endphp
            <a href="{{ route('bodyguards.show', $bodyguard) }}" class="agent-card reveal reveal-delay-{{ $loop->index + 1 }}">
                <div class="agent-img-wrap">
                    @if($bodyguard->user->avatar)
                        <img src="{{ asset('uploads/' . $bodyguard->user->avatar) }}" alt="{{ $bodyguard->user->name }}">
                    @else
                        <div class="agent-initial-bg" style="background:linear-gradient({{ $g }});">
                            {{ strtoupper(substr($bodyguard->user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="agent-gradient"></div>
                <div class="agent-rate">Rp {{ number_format($bodyguard->daily_rate, 0, ',', '.') }}/Hari</div>
                <div class="agent-info">
                    <div class="agent-name">{{ $bodyguard->user->name }}</div>
                    <div class="agent-meta">Bodyguard Profesional &middot; {{ $bodyguard->experience_years }} Tahun</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ── CTA ── -->
<section class="cta-section">
    <div class="cta-bg"></div>
    <div class="cta-overlay"></div>
    <div class="cta-content">
        <div class="eyebrow reveal" style="justify-content:center; margin-bottom:1.25rem;">Bergabung Sekarang</div>
        <h2 class="cta-title reveal reveal-delay-1">Standar <span>Emas</span> Keamanan Personal.</h2>
        <p class="cta-subtitle reveal reveal-delay-2">Percayakan keamanan Anda dan orang-orang terkasih kepada GuardYou. Pesan bodyguard profesional terverifikasi dalam hitungan menit.</p>
        <div class="cta-btns reveal reveal-delay-3">
            <a href="{{ route('bodyguard.landing') }}" class="btn-primary btn-lg">Daftar Gratis</a>
            <a href="{{ route('bodyguards.index') }}" class="btn-outline btn-lg">Lihat Bodyguard</a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    // ── 1. TYPING EFFECT ──
    const typedTextElement = document.getElementById('typed-text');
    const textToType = "Kelas Elite,";
    let typeIndex = 0;
    
    function typeWriter() {
        if (typeIndex < textToType.length) {
            typedTextElement.innerHTML += textToType.charAt(typeIndex);
            typeIndex++;
            setTimeout(typeWriter, 120); // Typing speed
        }
    }
    setTimeout(typeWriter, 800); // Start after 0.8s

    // ── 2. SCROLL REVEAL ANIMATION ──
    const reveals = document.querySelectorAll('.reveal');
    
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

    reveals.forEach(el => revealObserver.observe(el));

    // ── 3. SMOOTH SCROLL ──
    const scrollDown = document.getElementById('scroll-down');
    const featuresSection = document.getElementById('features');
    
    if(scrollDown && featuresSection) {
        scrollDown.addEventListener('click', () => {
            featuresSection.scrollIntoView({ behavior: 'smooth' });
        });
    }

    // ── 4. HERO PARALLAX ON MOUSE MOVE ──
    const hero = document.getElementById('hero');
    const heroBg = document.getElementById('hero-bg');
    const heroBadge = document.getElementById('hero-badge');

    if(hero && heroBg && heroBadge) {
        hero.addEventListener('mousemove', (e) => {
            // Only run on wider screens
            if(window.innerWidth > 768) {
                const xAxis = (window.innerWidth / 2 - e.pageX) / 60;
                const yAxis = (window.innerHeight / 2 - e.pageY) / 60;
                
                // Move background slower for depth
                heroBg.style.transform = `scale(1.1) translate(${xAxis * -1}px, ${yAxis * -1}px)`;
                
                // Move badge faster
                heroBadge.style.transform = `translate(${xAxis * 2}px, ${yAxis * 2}px)`;
            }
        });

        hero.addEventListener('mouseleave', () => {
            heroBg.style.transform = 'scale(1.1) translate(0px, 0px)';
            heroBadge.style.transform = 'translate(0px, 0px)';
        });
    }

});
</script>
@endpush