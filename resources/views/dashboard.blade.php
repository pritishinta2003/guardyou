@extends('layouts.app')

@section('title', 'Security Command Center')

@push('styles')
<style>
    /* Main Container with Red Gradient Atmosphere */
    .dashboard-vault {
        min-height: 100vh;
        padding: 7rem 2rem 4rem;
        max-width: 1400px;
        margin: 0 auto;
        position: relative;
        /* Multiple layers of gradients for depth */
        background: 
            radial-gradient(circle at 10% 10%, rgba(220, 20, 60, 0.15) 0%, transparent 40%),
            radial-gradient(circle at 90% 80%, rgba(220, 20, 60, 0.08) 0%, transparent 40%),
            var(--color-surface);
    }

    .dashboard-header {
        margin-bottom: 4rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .dashboard-title {
        font-size: clamp(2.5rem, 5vw, 3.8rem);
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: -0.04em;
        line-height: 1;
        color: #fff;
    }
    .dashboard-title span { color: var(--color-gold); }

    /* ===== CTA PANEL (HERO) ===== */
    .deployment-cta {
        background: linear-gradient(135deg, rgba(220,20,60,0.12) 0%, rgba(255,255,255,0.02) 100%);
        border: 1px solid rgba(220,20,60,0.2);
        border-radius: 16px;
        padding: 4rem 3rem;
        text-align: center;
        margin-bottom: 4rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.3);
    }
    .deployment-cta::before {
        content: 'SECURE';
        position: absolute; top: -2rem; right: -1rem;
        font-size: 10rem; font-weight: 900; color: rgba(255,255,255,0.015);
        letter-spacing: -0.05em; pointer-events: none;
    }
    .deployment-cta::after {
        content: '';
        position: absolute; bottom: 0; left: 0; right: 0; height: 2px;
        background: linear-gradient(90deg, transparent, var(--color-gold), transparent);
    }

    /* ===== SECTION LABELS ===== */
    .section-label {
        display: inline-block;
        font-size: 0.7rem; font-weight: 800; text-transform: uppercase;
        letter-spacing: 0.2em; color: var(--color-gold);
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid rgba(220,20,60,0.3);
    }

    /* ===== MISSION GRID ===== */
    .mission-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
        margin-bottom: 5rem;
    }

    .mission-card {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.07);
        border-radius: 12px;
        padding: 2rem;
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    
    /* Hover Glow Effect */
    .mission-card:hover {
        border-color: rgba(220,20,60,0.4);
        transform: translateY(-4px);
        box-shadow: 0 15px 40px rgba(220,20,60,0.15);
        background: rgba(255, 255, 255, 0.05);
    }

    /* Card Top Accent Line */
    .mission-card::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; height: 3px;
        background: linear-gradient(90deg, var(--color-gold), transparent);
        opacity: 0;
        transition: opacity 0.3s;
    }
    .mission-card:hover::before { opacity: 1; }

    .card-status {
        position: absolute; top: 1.5rem; right: 1.5rem;
        font-size: 0.6rem; font-weight: 900; text-transform: uppercase;
        letter-spacing: 0.1em;
    }
    .status-active { color: #4ade80; background: rgba(74,222,128,0.1); padding: 0.3rem 0.6rem; border-radius: 4px; }
    .status-pending { color: #fbbf24; background: rgba(255,193,7,0.1); padding: 0.3rem 0.6rem; border-radius: 4px; }

    .card-agent { 
        font-size: 1.4rem; 
        font-weight: 800; 
        text-transform: uppercase; 
        letter-spacing: -0.02em; 
        margin-bottom: 0.25rem; 
        color: #fff;
    }
    .card-meta { 
        font-size: 0.8rem; 
        color: var(--color-on-surface-muted); 
        margin-bottom: 1.5rem; 
        display: block; 
    }

    .data-strip {
        background: rgba(0,0,0,0.2);
        border-radius: 8px;
        padding: 1rem;
        margin-top: auto; /* Push to bottom */
    }
    
    .data-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }
    .data-row:last-child { margin-bottom: 0; }

    .entry-label {
        font-size: 0.6rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.15em; color: var(--color-on-surface-muted);
        display: block; margin-bottom: 0.25rem;
    }
    .entry-val {
        font-size: 0.9rem; font-weight: 700; color: var(--color-on-surface);
    }
    .entry-val.price { color: var(--color-gold-light); }

    .card-actions { 
        display: flex; 
        gap: 1rem; 
        margin-top: 1.5rem; 
        padding-top: 1.5rem; 
        border-top: 1px solid rgba(255,255,255,0.05); 
    }
    .card-link { 
        font-size: 0.75rem; 
        font-weight: 700; 
        text-transform: uppercase; 
        letter-spacing: 0.1em; 
        color: var(--color-on-surface-muted); 
        text-decoration: none; 
        transition: color 0.2s;
    }
    .card-link:hover { color: #fff; }

    /* ===== BANNER CARD (Become Bodyguard) ===== */
    .join-banner {
        background: linear-gradient(135deg, rgba(255,255,255,0.05) 0%, rgba(220,20,60,0.05) 100%);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 12px;
        padding: 2.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 2rem;
        flex-wrap: wrap;
        margin-bottom: 4rem;
        position: relative;
        overflow: hidden;
    }
    .join-banner::after {
        content: '';
        position: absolute; top: 0; right: 0; width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(220,20,60,0.1) 0%, transparent 70%);
        pointer-events: none;
    }

    /* Empty State */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
        background: rgba(255,255,255,0.02);
        border-radius: 12px;
        border: 1px dashed rgba(255,255,255,0.1);
        color: var(--color-on-surface-muted);
    }
    
    @media (max-width: 768px) {
        .deployment-cta { padding: 2.5rem 1.5rem; }
        .dashboard-vault { padding: 6rem 1rem 3rem; }
        .mission-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="dashboard-vault">

    {{-- Session Alerts --}}
    @if(session('success'))
    <div class="alert alert-success" style="margin-bottom: 2rem;">
        {{ session('success') }}
    </div>
    @endif
    @if(session('info'))
    <div class="alert alert-warning" style="margin-bottom: 2rem;">
        {{ session('info') }}
    </div>
    @endif

    {{-- HEADER --}}
    <header class="dashboard-header">
        <div>
            <span class="eyebrow" style="margin-bottom: 1rem;">Halaman Pengguna</span>
            <h1 class="dashboard-title">Guard<span>You</span></h1>
            <p style="color: var(--color-on-surface-muted); margin-top: 1rem; max-width: 500px;">
                Kontrol penuh atas keamanan Anda. Lihat order dan pilih guard anda.
            </p>
        </div>
    </header>

    {{-- HERO CTA --}}
    <section class="deployment-cta">
        <h2 class="dashboard-title" style="font-size:clamp(1.8rem, 3vw, 2.5rem); margin-bottom:1rem;">
            Butuh Proteksi Segera?
        </h2>
        <p style="color:var(--color-on-surface-muted); max-width:550px; margin:0 auto 2.5rem; line-height:1.6;">
            Akses jaringan bodyguard bersertifikat kami. Respons cepat, identitas terverifikasi, dan siaga 24 jam.
        </p>
        <a href="{{ route('bodyguards.index') }}" class="btn-primary btn-lg">
            <span style="margin-right: 8px;"></span> Pesan Sekarang
        </a>
    </section>

    {{-- BECOME A BODYGUARD --}}
    <section class="join-banner">
        <div style="position: relative; z-index: 2;">
            <span class="section-label" style="margin-bottom:0.5rem; border:none;">Opsional</span>
            <h3 style="font-size:1.4rem; font-weight:800; color:#fff; margin-bottom:0.5rem; letter-spacing:-0.02em;">
                Jadilah <span style="color:var(--color-gold);">Bodyguard</span>
            </h3>
            <p style="color:var(--color-on-surface-muted); font-size:0.9rem; max-width:450px; margin:0;">
                Bergabunglah dengan tim kami. Tawarkan keahlian dan dapatkan klien anda.
            </p>
        </div>
        <a href="{{ route('bodyguard.landing') }}" class="btn-outline" style="white-space:nowrap; position:relative; z-index:2;">
            Daftar Sebagai Bodyguard
        </a>
    </section>

    {{-- ACTIVE ASSIGNMENTS --}}
    <section class="mission-section">
        <span class="section-label">Pesanan Aktif</span>
        
        @if($activeBookings->isEmpty())
            <div class="empty-state">
                <p style="font-size: 1rem; margin-bottom: 0.5rem;">Belum ada guard aktif</p>
                <p style="font-size: 0.85rem;">Pesanan Anda yang sedang berlangsung akan muncul di sini.</p>
            </div>
        @else
            <div class="mission-grid">
                @foreach($activeBookings as $booking)
                <div class="mission-card">
                    <span class="card-status status-active">{{ strtoupper($booking->status) }}</span>
                    
                    <div style="margin-bottom: 1.5rem;">
                        <span class="entry-label">Elite Sentinel</span>
                        <span class="card-agent">{{ $booking->bodyguard->user->name }}</span>
                        <span class="card-meta">
                            {{ \Carbon\Carbon::parse($booking->start_date)->format('d M') }} — 
                            {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}
                        </span>
                    </div>
                    
                    <div class="data-strip">
                        <div class="data-row">
                            <span class="entry-label">Tarif Harian</span>
                            <span class="entry-val">IDR {{ number_format($booking->bodyguard->daily_rate, 0, ',', '.') }}</span>
                        </div>
                        <div class="data-row">
                            <span class="entry-label">Status</span>
                            <span class="entry-val" style="color:#4ade80;">Operational</span>
                        </div>
                    </div>

                    <div class="card-actions">
                        <a href="{{ route('bookings.show', $booking) }}" class="card-link" style="color:var(--color-gold);">Lihat Detail</a>
                        <a href="{{ route('chat.show', $booking) }}" class="card-link">🛰 Uplink</a>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </section>

    {{-- PENDING AUTHORIZATIONS --}}
    @if(!$pendingBookings->isEmpty())
    <section class="mission-section">
        <span class="section-label">Menunggu Otorisasi</span>
        <div class="mission-grid">
            @foreach($pendingBookings as $booking)
            <div class="mission-card" style="border-style: dashed; border-color: rgba(220,20,60,0.3);">
                <span class="card-status status-pending">PENDING PAYMENT</span>
                
                <span class="entry-label">Target Sentinel</span>
                <span class="card-agent">{{ $booking->bodyguard->user->name }}</span>
                <span class="card-meta">Mission ID: #GY-{{ $booking->id }}</span>
                
                <div class="data-strip" style="background: rgba(220,20,60,0.05); border: 1px solid rgba(220,20,60,0.1);">
                    <span class="entry-label">Total Biaya Misi</span>
                    <span class="entry-val price" style="font-size: 1.1rem;">IDR {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                </div>

                <div class="card-actions" style="flex-direction: column; gap: 0;">
                    <a href="{{ route('bookings.show', $booking) }}" class="btn-primary" style="width:100%; text-align:center; padding:0.9rem;">
                        authorize & Bayar
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

</div>
@endsection