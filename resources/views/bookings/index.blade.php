@extends('layouts.app')

@section('title', 'Log Deployment Misi')

@push('styles')
<style>
    .mission-logs-page {
        min-height: 100vh;
        background: var(--color-surface);
        padding: 8rem 3rem 4rem;
        max-width: 1100px;
        margin: 0 auto;
    }

    .logs-header {
        margin-bottom: 5rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }

    .logs-title {
        font-size: 3.5rem;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: -0.04em;
        line-height: 1;
    }
    .logs-title span { color: var(--color-gold); }

    .mission-list {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .mission-entry {
        display: grid;
        grid-template-columns: 80px 1fr 200px 150px 40px;
        align-items: center;
        gap: 2rem;
        padding: 2rem;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 8px;
        text-decoration: none;
        color: inherit;
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
    }
    .mission-entry:hover {
        background: rgba(255, 255, 255, 0.04);
        border-color: rgba(233, 193, 118, 0.2);
        transform: translateX(10px);
    }

    .mission-ava {
        width: 60px; height: 60px; border-radius: 50%;
        background: #1a232c; border: 2px solid rgba(233, 193, 118, 0.2);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; font-weight: 900;
    }

    .mission-details { display: flex; flex-direction: column; }
    .entry-label { font-size: 0.6rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.2em; color: var(--color-gold); margin-bottom: 0.2rem; }
    .entry-val { font-size: 1.1rem; font-weight: 800; text-transform: uppercase; letter-spacing: -0.01em; color: var(--color-on-surface); }
    .entry-sub { font-size: 0.75rem; color: var(--color-on-surface-variant); font-weight: 600; }

    .status-pill {
        padding: 0.4rem 0.8rem; border-radius: 4px;
        font-size: 0.65rem; font-weight: 900; text-transform: uppercase;
        letter-spacing: 0.15em; text-align: center;
    }
    .status-pending   { background: rgba(220,20,60,0.1); color: var(--color-gold); border: 1px solid rgba(220,20,60,0.2); }
    .status-paid      { background: rgba(74,222,128,0.1); color: #4ade80; border: 1px solid rgba(74,222,128,0.2); }
    .status-active    { background: rgba(96,165,250,0.1); color: #60a5fa; border: 1px solid rgba(96,165,250,0.2); }
    .status-completed { background: rgba(148,163,184,0.1); color: #94a3b8; border: 1px solid rgba(148,163,184,0.2); }
    .status-cancelled { background: rgba(248,113,113,0.1); color: #f87171; border: 1px solid rgba(248,113,113,0.2); }

    .empty-logs {
        text-align: center; padding: 10rem 0;
        opacity: 0.5;
    }

    @media (max-width: 900px) {
        .mission-entry { grid-template-columns: 60px 1fr 1fr; gap: 1rem; }
        .entry-pricing, .entry-arrow { display: none; }
    }
</style>
@endpush

@section('content')
<div class="mission-logs-page">

    @if(session('success'))
        <div style="background:rgba(74,222,128,0.1); border:1px solid rgba(74,222,128,0.2); color:#4ade80; padding:1.2rem; border-radius:6px; margin-bottom:3rem; font-size:0.85rem; font-weight:700;">
            ✔ {{ session('success') }}
        </div>
    @endif

    <header class="logs-header">
            <h1 class="logs-title">Riwayat <span> Pemesanan</span></h1>
        <div style="text-align:right;">
            <span class="strip-lbl">Total Aktivitas</span>
            <span class="strip-val" style="display:block; font-size:1.5rem;">{{ $bookings->total() }}</span>
        </div>
    </header>

    @if($bookings->isEmpty())
        <div class="empty-logs">
            <h2 class="logs-title" style="font-size:2rem; margin-bottom:1rem;">Tidak Ada Deployment Aktif</h2>
            <p style="margin-bottom:3rem;">Riwayat aktivitas Anda masih kosong.</p>
            <a href="{{ route('bodyguards.index') }}" class="btn-gold">Lihat Daftar Personel</a>
        </div>
    @else
        <div class="mission-list">
            @foreach($bookings as $booking)
            <a href="{{ route('bookings.show', $booking) }}" class="mission-entry">
                <div class="mission-ava">{{ substr($booking->bodyguard->user->name ?? 'A', 0, 1) }}</div>
                
                <div class="mission-details">
                    <span class="entry-label">Guard Ditugaskan</span>
                    <span class="entry-val">{{ $booking->bodyguard->user->name }}</span>
                    <span class="entry-sub">#GY-{{ $booking->id }} // {{ \Carbon\Carbon::parse($booking->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($booking->end_date)->format('M d, Y') }}</span>
                </div>

                <div class="entry-pricing" style="text-align:right;">
                    <span class="entry-label">Nilai Misi</span>
                    <span class="entry-val" style="color:var(--color-gold);">IDR {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                </div>

                <div class="entry-status">
                    <div class="status-pill status-{{ $booking->status }}">{{ $booking->status }}</div>
                </div>

                <div class="entry-arrow" style="text-align:right; opacity:0.3;">
                    →
                </div>
            </a>
            @endforeach
        </div>

        <div style="margin-top:4rem;">
            {{ $bookings->links() }}
        </div>
    @endif

</div>
@endsection