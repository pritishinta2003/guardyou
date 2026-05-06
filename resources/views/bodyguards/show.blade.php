@extends('layouts.app')

@section('title', ($bodyguard->user->name ?? 'Agen') . ' // Dossier Keamanan')

@push('styles')
<style>
    .dossier-container {
        display: grid;
        grid-template-columns: 1fr 1.2fr;
        min-height: 90vh;
        background: var(--color-surface);
        padding-top: 80px;
    }

    /* ===== LEFT: VISUAL ASSET ===== */
    .dossier-visual {
        position: relative;
        background: #050a0f;
        overflow: hidden;
    }
    .dossier-visual img {
        width: 100%; height: 100%; object-fit: cover;
    }
    .dossier-visual::after {
        content: 'PENGAWAL ELIT';
        position: absolute; bottom: 2rem; left: 2rem;
        font-size: 5rem; font-weight: 950; color: rgba(255,255,255,0.03);
        letter-spacing: -0.05em; line-height: 0.8; pointer-events: none;
    }

    /* ===== RIGHT: TACTICAL DATA ===== */
    .dossier-data {
        padding: 5rem 6rem;
        background: radial-gradient(circle at 0% 0%, rgba(233, 193, 118, 0.05) 0%, transparent 50%);
        overflow-y: auto;
    }

    .dossier-status {
        display: inline-flex; align-items: center; gap: 0.5rem;
        font-size: 0.65rem; font-weight: 800; text-transform: uppercase;
        letter-spacing: 0.2em; color: var(--color-gold);
        margin-bottom: 2rem; padding: 0.4rem 0.8rem;
        background: rgba(233, 193, 118, 0.1); border-radius: 4px;
    }

    .dossier-name {
        font-size: 4.5rem; font-weight: 950; text-transform: uppercase;
        letter-spacing: -0.05em; line-height: 0.9; margin-bottom: 3rem;
    }

    .data-grid {
        display: grid; grid-template-columns: 1fr 1fr; gap: 3rem;
        margin-bottom: 4rem;
    }
    .data-item { border-left: 1px solid rgba(233, 193, 118, 0.2); padding-left: 1.5rem; }
    .data-val { font-size: 1.8rem; font-weight: 900; color: var(--color-on-surface); display: block; }
    .data-lbl { font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.15em; color: var(--color-on-surface-variant); }

    .protocol-list { list-style: none; margin-bottom: 4rem; }
    .protocol-list li {
        display: flex; align-items: center; gap: 1rem;
        padding: 1rem 0; border-bottom: 1px solid rgba(255,255,255,0.05);
        font-size: 0.9rem; color: var(--color-on-surface-variant);
    }
    .protocol-list li b { color: var(--color-on-surface); text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.05em; flex-shrink: 0; width: 140px; }

    /* ===== BOOKING INTERFACE ===== */
    .booking-terminal {
        background: var(--color-surface-low);
        border: 1px solid rgba(233, 193, 118, 0.1);
        padding: 2.5rem; border-radius: 8px; margin-top: 5rem;
    }
    .terminal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
    .terminal-title { font-size: 0.8rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.15em; color: var(--color-gold); }
    .terminal-price { font-size: 1.5rem; font-weight: 900; color: var(--color-on-surface); }

    .back-btn {
        position: absolute; top: 110px; left: 3rem; z-index: 10;
        color: var(--color-on-surface-variant); text-decoration: none;
        font-size: 0.7rem; font-weight: 800; text-transform: uppercase;
        letter-spacing: 0.2em; display: flex; align-items: center; gap: 0.5rem;
    }
    .back-btn:hover { color: var(--color-gold); }

</style>
@endpush

@section('content')
<div class="dossier-container">
    <a href="{{ route('bodyguards.index') }}" class="back-btn">← Kembali ke Katalog</a>

    <!-- LEFT: THE ASSET -->
    <div class="dossier-visual" style="display:flex; align-items:center; justify-content:center;">
        <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center;
                    font-size:12rem; font-weight:900; color:rgba(220,20,60,0.08); letter-spacing:-0.05em; pointer-events:none;">
            {{ strtoupper(substr($bodyguard->user->name, 0, 1)) }}
        </div>
        @if($bodyguard->user->avatar)
            <img src="{{ asset('uploads/' . $bodyguard->user->avatar) }}"
                 alt="{{ $bodyguard->user->name }}"
                 onerror="this.style.display='none'">
        @endif
    </div>

    <!-- RIGHT: THE DATA -->
    <main class="dossier-data">
        <div class="dossier-status">Operasional // Siap Bertugas</div>
        
        <h1 class="dossier-name">{{ $bodyguard->user->name }}</h1>

        <div class="data-grid">
            <div class="data-item">
                <span class="data-val">{{ $bodyguard->experience_years }} Tahun</span>
                <span class="data-lbl">Masa Tugas</span>
            </div>
            <div class="data-item">
                <span class="data-val">{{ $bodyguard->height }} CM</span>
                <span class="data-lbl">Tinggi Badan</span>
            </div>
            <div class="data-item" style="grid-column: span 2;">
                <span class="data-val">Pengamanan dan Pengawalan Pribadi</span>
                <span class="data-lbl">Spaesialis Keamanan</span>
            </div>
        </div>

        <ul class="protocol-list">
            <li><b>Penugasan</b> <span>Detail Perlindungan Utama</span></li>
            <li><b>Verifikasi</b> <span>Lolos Verifikasi Tim GuardYou</span></li>
            <li><b>Lokasi</b> <span>Tersedia hanya untuk Kota Samarinda</span></li>
            <li><b>Kontak</b> <span>Hanya Melalui Chat Terenkripsi</span></li>
        </ul>

        <!-- BOOKING TERMINAL -->
        <section class="booking-terminal">
            <div class="terminal-header">
                <span class="terminal-title">Harga Pemesanan</span>
                <span class="terminal-price">IDR {{ number_format($bodyguard->daily_rate, 0, ',', '.') }}<span style="font-size:0.7rem; font-weight:700; color:var(--color-on-surface-variant);"> / HARI</span></span>
            </div>

            @auth
                @if(auth()->user()->role === 'user')
                    <p style="color:var(--color-on-surface-variant); font-size:0.85rem; margin-bottom:2rem; line-height:1.6;">
                        Pesan bodyguard ini sekarang. Pembayaran diproses melalui gateway aman kami.
                    </p>
                    <a href="{{ route('bookings.create', $bodyguard) }}" 
                    class="btn-primary btn-shimmer inline-flex items-center gap-2 font-semibold text-white"
                    style="padding: 0.8rem 2.0rem; font-size: 0.9rem; border-radius: 0.6rem; box-shadow: 0 4px 15px rgba(220,20,60,0.25); transition: box-shadow 0.3s, transform 0.3s;"
                    onmouseover="this.style.boxShadow='0 8px 25px rgba(220,20,60,0.45)'; this.style.transform='scale(1.03)';"
                    onmouseout="this.style.boxShadow='0 4px 15px rgba(220,20,60,0.25)'; this.style.transform='scale(1)';">
                    Pesan Sekarang
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
                @else
                    <div style="padding:1.5rem; background:rgba(255,255,255,0.03); border-radius:4px; text-align:center; color:var(--color-on-surface-variant); font-size:0.8rem;">
                        Akun bodyguard tidak dapat melakukan pemesanan.
                    </div>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn-gold" style="width:100%; text-align:center;">Masuk untuk Memesan</a>
            @endauth
        </section>
    </main>
</div>
@endsection
