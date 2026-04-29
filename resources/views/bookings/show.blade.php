@extends('layouts.app')

@section('title', 'Pusat Misi // #' . $booking->id)

@push('styles')
<style>
    .mission-control {
        min-height: 100vh;
        background: var(--color-surface);
        padding: 8rem 3rem 4rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* ===== HEADER MISI ===== */
    .mission-header {
        margin-bottom: 5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        padding-bottom: 3rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }

    .mission-id {
        font-size: 0.7rem; font-weight: 800; text-transform: uppercase;
        letter-spacing: 0.3em; color: var(--color-on-surface-variant);
        margin-bottom: 1rem; display: block;
    }

    .mission-status-title {
        font-size: 4.5rem; font-weight: 950; text-transform: uppercase;
        letter-spacing: -0.05em; line-height: 1;
    }
    .status-text-pending   { color: var(--color-gold); }
    .status-text-paid      { color: #4ade80; }
    .status-text-active    { color: #60a5fa; }
    .status-text-completed { color: #94a3b8; }
    .status-text-cancelled { color: #f87171; }

    /* ===== GRID MISI ===== */
    .mission-grid {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 4rem;
    }

    .briefing-box { margin-bottom: 4rem; }
    .briefing-label {
        font-size: 0.65rem; font-weight: 800; text-transform: uppercase;
        letter-spacing: 0.25em; color: var(--color-gold);
        margin-bottom: 1.5rem; display: block;
    }

    .data-strip {
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem;
        background: rgba(255, 255, 255, 0.02);
        padding: 2rem; border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
    .strip-item { border-left: 1px solid rgba(233, 193, 118, 0.2); padding-left: 1.5rem; }
    .strip-val { font-size: 1.4rem; font-weight: 800; color: var(--color-on-surface); display: block; }
    .strip-lbl { font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--color-on-surface-variant); }

    /* ===== PANEL AKSI ===== */
    .action-panel {
        background: var(--color-surface-low);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 12px; padding: 2.5rem;
    }
    .asset-mini-row { display: flex; align-items: center; gap: 1.5rem; margin-bottom: 2rem; }
    .asset-ava {
        width: 60px; height: 60px; border-radius: 50%;
        background: #1a232c; border: 2px solid var(--color-gold);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; font-weight: 900;
    }
    .asset-name { font-size: 1.2rem; font-weight: 800; text-transform: uppercase; display: block; }
    .asset-vocation { font-size: 0.6rem; color: var(--color-gold); text-transform: uppercase; letter-spacing: 0.1em; }

    .protocol-btn {
        width: 100%; padding: 1rem; margin-bottom: 1rem;
        font-size: 0.8rem; font-weight: 800; text-transform: uppercase;
        letter-spacing: 0.15em; border-radius: 4px; border: none; cursor: pointer;
        transition: all 0.3s; display: flex; align-items: center; justify-content: center; gap: 0.5rem;
    }
    .btn-primary { background: var(--color-gold); color: #000; }
    .btn-secondary { background: rgba(255,255,255,0.05); color: var(--color-on-surface); border: 1px solid rgba(255,255,255,0.1); text-decoration: none; }
    .btn-danger { background: transparent; color: #f87171; border: 1px solid rgba(248,113,113,0.2); }
    .btn-danger:hover { background: rgba(248,113,113,0.1); }

    /* Responsif mobile */
    @media (max-width: 900px) {
        .mission-grid { grid-template-columns: 1fr; }
        .mission-status-title { font-size: 3rem; }
    }
</style>
@endpush

@section('content')
<div class="mission-control">
    
    @php
        $authUser = auth()->user();
        $isBookingOwner = $authUser->id === $booking->user_id;
        $isAssignedBodyguard = $authUser->bodyguard?->id === $booking->bodyguard_id;
    @endphp

    <header class="mission-header">
        <div>
            <span class="mission-id">ID ORDER #GY-{{ $booking->id }}</span>
            <h1 class="mission-status-title status-text-{{ $booking->status }}">
                {{ $booking->status === 'paid' ? 'Disetujui' : $booking->status }}
            </h1>
        </div>
        <div style="text-align:right;">
            <span class="strip-lbl">Harga</span>
            <span class="strip-val" style="color:var(--color-gold); font-size:2rem;">IDR {{ number_format($booking->total_price, 0, ',', '.') }}</span>
        </div>
    </header>

    <div class="mission-grid">
        
        <!-- KIRI: BRIEFING -->
        <div class="mission-briefing">
            <div class="briefing-box">
                <span class="briefing-label">Metadata Penugasan</span>
                <div class="data-strip">
                    <div class="strip-item">
                        <span class="strip-val">{{ \Carbon\Carbon::parse($booking->start_date)->format('M d, Y') }}</span>
                        <span class="strip-lbl">Mulai Tugas</span>
                    </div>
                    <div class="strip-item">
                        <span class="strip-val">{{ \Carbon\Carbon::parse($booking->end_date)->format('M d, Y') }}</span>
                        <span class="strip-lbl">Selesai Tugas</span>
                    </div>
                    <div class="strip-item">
                        <span class="strip-val">{{ \Carbon\Carbon::parse($booking->start_date)->diffInDays($booking->end_date) }} Hari</span>
                        <span class="strip-lbl">Durasi</span>
                    </div>
                </div>
            </div>

            <div class="briefing-box">
                <span class="briefing-label">Log Protokol Keamanan</span>
                <p style="color:var(--color-on-surface-variant); font-size:0.9rem; line-height:1.8; max-width:600px;">
                    <b>{{ $booking->bodyguard->user->name }}</b> ditugaskan untuk memberikan perlindungan prioritas tinggi. 
                        Status saat ini: <b>{{ strtoupper($booking->status) }}</b>. Semua percakapan di bawah ini akan tersimpan untuk keamanan dan pemantauan.
                </p>
                <div style="margin-top:2rem;">
                    <a href="{{ route('chat.show', $booking) }}" class="protocol-btn btn-primary" style="max-width:300px; text-decoration:none;">
                         Jalur Komunikasi (Chat)
                    </a>
                </div>
            </div>
        </div>

        <!-- KANAN: PERSONEL & AKSI -->
        <aside class="mission-actions">
            <div class="action-panel">
                <span class="briefing-label" style="margin-bottom:2rem;">Personel Ditugaskan</span>
                <div class="asset-mini-row">
                    <div class="asset-ava">{{ substr($booking->bodyguard->user->name, 0, 1) }}</div>
                    <div>
                        <span class="asset-name">{{ $booking->bodyguard->user->name }}</span>
                        <span class="asset-vocation">Guard Anda</span>
                    </div>
                </div>

                <div style="margin-top:3rem;">
                    <span class="briefing-label" style="margin-bottom:1.5rem;">Protokol Taktis</span>
                    
                    @if($isBookingOwner)
                        @if($booking->status === 'pending')
                            @if($booking->payment_url)
                                <a href="{{ $booking->payment_url }}" class="protocol-btn btn-primary" target="_blank">Lanjut ke Pembayaran</a>
                            @else
                                <form method="POST" action="{{ route('bookings.pay', $booking) }}">
                                    @csrf
                                    <button type="submit" class="protocol-btn btn-primary">Buat Link Pembayaran</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('bookings.cancel', $booking) }}" onsubmit="return confirm('Batalkan penugasan ini?')">
                                @csrf
                                <button type="submit" class="protocol-btn btn-danger">Batalkan Misi</button>
                            </form>
                        @endif
                    @endif

                    @if($isAssignedBodyguard)
                        @if($booking->status === 'paid')
                            <form method="POST" action="{{ route('bookings.updateStatus', $booking) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="active">
                                <button type="submit" class="protocol-btn btn-primary">Mulai Penugasan</button>
                            </form>
                        @elseif($booking->status === 'active')
                            <form method="POST" action="{{ route('bookings.updateStatus', $booking) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="protocol-btn btn-primary">Selesaikan Penugasan</button>
                            </form>
                        @endif
                    @endif

                    <a href="{{ route('bodyguards.show', $booking->bodyguard) }}" class="protocol-btn btn-secondary">Lihat Profil Personel</a>
                </div>
            </div>

            <div style="margin-top:2rem; padding: 1.5rem; background:rgba(220,20,60,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius:8px;">
                <p style="font-size:0.7rem; color:var(--color-on-surface-variant); line-height:1.5; margin:0;">
                    <b>LINGKUNGAN AMAN:</b> Panel ini diakses melalui koneksi terenkripsi 2048-bit. IP dan lokasi Anda disamarkan melalui jaringan proxy GuardYou.
                </p>
            </div>
        </aside>

    </div>
</div>
@endsection