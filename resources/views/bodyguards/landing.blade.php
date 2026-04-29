@extends('layouts.app')

@section('title', 'Pendaftaran Bodyguard')

@section('page_title', 'Pendaftaran Bodyguard')

@push('styles')
<style>
    /* ── Scroll Reveal ── */
    .reveal-up {
        opacity: 0;
        transform: translateY(40px);
        transition: all 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
    }
    .reveal-up.is-visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* ── Floating Background ── */
    .floating-shape {
        position: absolute;
        border-radius: 50%;
        filter: blur(60px);
        opacity: 0.35;
        animation: floatShape 20s infinite alternate;
        z-index: -1;
    }
    @keyframes floatShape {
        0%   { transform: translateY(0) translateX(0) rotate(0deg); }
        100% { transform: translateY(-50px) translateX(30px) rotate(20deg); }
    }

    /* ── Card Hover ── */
    .card-hover {
        transition: all 0.4s cubic-bezier(0.2, 0.8, 0.2, 1);
    }
    .card-hover:hover {
        transform: translateY(-8px) scale(1.01);
        box-shadow: 0 20px 40px rgba(0,0,0,0.4), 0 0 24px rgba(220,20,60,0.1);
    }

    /* ── Checklist Item ── */
    .item-hover .icon-dot {
        transition: all 0.3s ease;
    }
    .item-hover:hover .icon-dot {
        background-color: rgba(220,20,60,0.3);
        transform: scale(1.15);
    }
    .item-hover:hover .item-text {
        color: #fff;
    }

    /* ── Benefit Item ── */
    .benefit-item {
        transition: all 0.3s ease;
    }
    .benefit-item:hover {
        border-color: var(--color-gold) !important;
        background: var(--color-surface) !important;
        transform: translateY(-4px);
    }
    .benefit-item:hover .benefit-icon-box {
        background-color: rgba(220,20,60,0.3);
    }
    .benefit-item:hover .benefit-icon-box svg {
        transform: scale(1.15);
    }
    .benefit-item:hover .benefit-title {
        color: var(--color-gold-light);
    }
    .benefit-icon-box svg {
        transition: transform 0.3s ease;
    }

    /* ── CTA Shimmer ── */
    .btn-shimmer {
        position: relative;
        overflow: hidden;
    }
    .btn-shimmer::after {
        content: '';
        position: absolute;
        top: 0; left: -100%;
        width: 100%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.18), transparent);
        transition: 0.5s;
    }
    .btn-shimmer:hover::after {
        left: 100%;
    }

    /* ── Header Title Hover ── */
    .hero-title {
        transition: color 0.5s ease;
    }
    .hero-title:hover {
        color: var(--color-gold) !important;
    }
</style>
@endpush

@section('content')

    <!-- ═══ Floating Background Shapes ═══ -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="floating-shape" style="width: 24rem; height: 24rem; background: var(--color-gold); top: 2.5rem; left: -5rem; animation-delay: 0s;"></div>
        <div class="floating-shape" style="width: 20rem; height: 20rem; background: #b91c1c; bottom: 2.5rem; right: 2.5rem; animation-delay: 5s;"></div>
        <div class="floating-shape" style="width: 16rem; height: 16rem; background: #52525b; top: 50%; left: 50%; animation-delay: 10s;"></div>
    </div>

    <!-- ═══ Mouse Spotlight ═══ -->
    <div id="cursor-spotlight" class="fixed pointer-events-none opacity-0 transition-opacity duration-300"
         style="width: 24rem; height: 24rem; background: radial-gradient(circle, rgba(220,20,60,0.08) 0%, transparent 70%); z-index: 0;"></div>

    <!-- ═══ Content Wrapper ═══ -->
    <div style="position: relative; z-index: 1;">

        <!-- Header -->
        <div class="text-center mb-16 reveal-up">
            <div class="eyebrow" style="justify-content: center;">Mitra Bodyguard</div>
            <h1 class="hero-title text-4xl sm:text-5xl lg:text-6xl text-white mb-4 cursor-default"
                style="font-family: var(--font-display); font-weight: 800; letter-spacing: -0.02em; line-height: 1.1;">
                BERGABUNG BERSAMA KAMI
            </h1>
            <p class="max-w-2xl mx-auto text-lg" style="color: var(--color-on-surface-muted);">
                Kami mencari individu yang disiplin, tangguh, dan berintegritas untuk bergabung menjadi bagian dari tim keamanan profesional Guard You.
            </p>
        </div>

        <!-- Cards Grid -->
        <div class="grid lg:grid-cols-2 gap-8 mb-14">

            <!-- ── Persyaratan ── -->
            <div class="reveal-up card-hover" style="background: rgba(13,17,23,0.8); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.08); border-radius: 1rem; padding: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.3); transition-delay: 0.1s;">
                <div class="flex items-center gap-3 mb-6">
                    <div class="flex items-center justify-center" style="width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; background: rgba(220,20,60,0.15);">
                        <svg class="w-5 h-5" style="color: var(--color-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <h2 class="text-2xl text-white" style="font-family: var(--font-display); font-weight: 800; letter-spacing: -0.01em;">PERSYARATAN</h2>
                </div>

                <ul class="space-y-4">
                    @foreach([
                        'Pria/Wanita, usia 17 – 45 tahun.',
                        'Tinggi badan min. 170 cm (Pria) / 165 cm (Wanita).',
                        'Sehat jasmani dan rohani.',
                        'Bersedia untuk wawancara dan melengkai berkas lanjutan.',
                        'Bersedia mengikuti pelatihan.'
                    ] as $item)
                    <li class="flex items-start gap-3 item-hover cursor-pointer">
                        <div class="icon-dot flex items-center justify-center shrink-0" style="width: 1.5rem; height: 1.5rem; border-radius: 50%; background: var(--color-bg); margin-top: 2px;">
                            <svg class="w-3.5 h-3.5" style="color: var(--color-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="item-text text-sm" style="color: var(--color-on-surface-muted); transition: color 0.2s;">{{ $item }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- ── Keuntungan ── -->
            <div class="reveal-up card-hover" style="background: rgba(13,17,23,0.8); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.08); border-radius: 1rem; padding: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.3); transition-delay: 0.2s;">
                <div class="flex items-center gap-3 mb-6">
                    <div class="flex items-center justify-center" style="width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; background: rgba(220,20,60,0.15);">
                        <svg class="w-5 h-5" style="color: var(--color-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h2 class="text-2xl text-white" style="font-family: var(--font-display); font-weight: 800; letter-spacing: -0.01em;">KEUNTUNGAN</h2>
                </div>

                <div class="space-y-4">
                    @foreach([
                        ['Penghasilan Kompetitif', 'Fee menarik per proyek.', 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['Waktu Fleksibel', 'Pilih shift sesuai jadwal.', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['Jaminan Asuransi', 'Perlindungan saat bertugas.', 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z']
                    ] as $benefit)
                    <div class="benefit-item flex items-center gap-4 cursor-pointer" style="background: rgba(8,12,20,0.5); border: 1px solid rgba(255,255,255,0.06); border-radius: 0.75rem; padding: 1rem;">
                        <div class="benefit-icon-box flex items-center justify-center shrink-0" style="width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; background: rgba(220,20,60,0.1); transition: background 0.3s;">
                            <svg class="w-5 h-5" style="color: var(--color-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $benefit[2] }}"></path></svg>
                        </div>
                        <div>
                            <h3 class="benefit-title font-semibold" style="color: #fff; transition: color 0.2s;">{{ $benefit[0] }}</h3>
                            <p class="text-xs" style="color: var(--color-on-surface-variant);">{{ $benefit[1] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- CTA -->
        <div class="text-center reveal-up" style="transition-delay: 0.3s;">
            <p class="mb-6" style="color: var(--color-on-surface-variant);">
                Pastikan Anda telah memenuhi persyaratan sebelum mendaftar.
            </p>
           <a href="{{ route('bodyguard.register') }}"
                class="btn-primary btn-shimmer inline-flex items-center gap-2 font-semibold text-white"
                style="padding: 0.8rem 2.0rem; font-size: 0.9rem; border-radius: 0.6rem; box-shadow: 0 4px 15px rgba(220,20,60,0.25); transition: box-shadow 0.3s, transform 0.3s;"
                onmouseover="this.style.boxShadow='0 8px 25px rgba(220,20,60,0.45)'; this.style.transform='scale(1.03)';"
                onmouseout="this.style.boxShadow='0 4px 15px rgba(220,20,60,0.25)'; this.style.transform='scale(1)';">
                    Daftar Sekarang
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        /* ── Scroll Reveal ── */
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) entry.target.classList.add('is-visible');
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.reveal-up').forEach(el => observer.observe(el));

        /* ── Mouse Spotlight (desktop only) ── */
        const spotlight = document.getElementById('cursor-spotlight');
        if (spotlight) {
            window.addEventListener('mousemove', (e) => {
                if (window.innerWidth > 1024) {
                    spotlight.style.opacity = '1';
                    spotlight.style.transform = `translate(${e.clientX - 192}px, ${e.clientY - 192}px)`;
                }
            });
            document.body.addEventListener('mouseleave', () => {
                spotlight.style.opacity = '0';
            });
        }
    });
</script>
@endpush