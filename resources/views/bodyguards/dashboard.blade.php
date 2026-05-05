@extends('layouts.app')

@section('title', 'Pusat Deployment Agen')

@push('styles')
<style>
    /* ══════════════════════════════════════
       AGENT VAULT - DEPLOYMENT CENTER
       ══════════════════════════════════════ */
    .agent-vault {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* ── VERIFICATION BANNER ── */
    .verification-status {
        background: rgba(220, 20, 60, 0.06);
        border: 1px solid rgba(220, 20, 60, 0.15);
        border-radius: 12px;
        padding: 2rem 2.25rem;
        margin-bottom: 2.5rem;
        position: relative;
        overflow: hidden;
    }
    .verification-status::before {
        content: '';
        position: absolute;
        top: 0; left: 0; bottom: 0;
        width: 3px;
        background: linear-gradient(180deg, var(--color-gold-light), var(--color-gold-dark));
        border-radius: 0 2px 2px 0;
    }

    .status-alert {
        display: inline-block;
        font-family: var(--font-display);
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: var(--color-gold-light);
        background: rgba(220, 20, 60, 0.12);
        padding: 0.3rem 0.85rem;
        border-radius: 4px;
        margin-bottom: 0.85rem;
    }

    .status-msg {
        font-family: var(--font-display);
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--color-on-surface);
        letter-spacing: -0.01em;
        line-height: 1.3;
    }

    /* ── AGENT HEADER ── */
    .agent-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        padding-bottom: 2.5rem;
        margin-bottom: 3rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    }

    .hero-label {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.18em;
        color: var(--color-gold);
    }
    .hero-label::before {
        content: '';
        width: 22px;
        height: 2px;
        background: var(--color-gold);
        border-radius: 1px;
    }

    .agent-title {
        font-family: var(--font-display);
        font-size: clamp(1.6rem, 3vw, 2.2rem);
        font-weight: 900;
        color: var(--color-on-surface);
        letter-spacing: -0.03em;
        line-height: 1.1;
        margin-top: 0.5rem;
    }
    .agent-title span {
        background: linear-gradient(135deg, var(--color-gold-light) 0%, var(--color-gold) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .strip-lbl {
        font-size: 0.6rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: var(--color-on-surface-variant);
    }

    .strip-val {
        font-family: var(--font-display);
        font-weight: 800;
        color: var(--color-on-surface);
        letter-spacing: 0.04em;
    }

    /* ── SECTION WRAPPER ── */
    .mission-section {
        margin-bottom: 3.5rem;
    }

    .section-label {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.18em;
        color: var(--color-on-surface-muted);
        margin-bottom: 1.5rem;
    }
    .section-label::before {
        content: '';
        width: 22px;
        height: 2px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 1px;
    }

    /* ── MISSION GRID ── */
    .mission-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 1.25rem;
    }

    /* ── MISSION CARD ── */
    .mission-card {
        background: var(--color-surface-container);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 12px;
        padding: 1.75rem;
        position: relative;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    .mission-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, var(--color-gold), transparent);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .mission-card:hover {
        border-color: rgba(255, 255, 255, 0.1);
        background: var(--color-surface-high);
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
    }
    .mission-card:hover::before {
        opacity: 0.6;
    }

    .card-status {
        display: inline-block;
        font-family: var(--font-display);
        font-size: 0.58rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #4ade80;
        background: rgba(74, 222, 128, 0.1);
        border: 1px solid rgba(74, 222, 128, 0.15);
        padding: 0.25rem 0.7rem;
        border-radius: 4px;
        margin-bottom: 1.25rem;
    }

    .entry-label {
        font-size: 0.65rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--color-on-surface-variant);
    }

    .card-client {
        display: block;
        font-family: var(--font-display);
        font-size: 1.2rem;
        font-weight: 800;
        color: var(--color-on-surface);
        letter-spacing: -0.01em;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
    }

    .card-meta {
        display: block;
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--color-on-surface-variant);
        line-height: 1.5;
    }

    .data-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .card-actions {
        display: flex;
        align-items: center;
        margin-top: 1.5rem;
        padding-top: 1.25rem;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
        gap: 0.5rem;
    }

    .card-link {
        font-family: var(--font-display);
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--color-on-surface-muted);
        text-decoration: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        transition: all 0.2s ease;
    }
    .card-link:hover {
        background: rgba(255, 255, 255, 0.05);
        border-color: rgba(255, 255, 255, 0.15);
        color: var(--color-on-surface);
    }

    /* ── REVENUE TABLE ── */
    .revenue-vault {
        background: var(--color-surface-container);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 12px;
        overflow: hidden;
    }

    .revenue-table {
        width: 100%;
        border-collapse: collapse;
    }

    .revenue-table thead {
        background: rgba(255, 255, 255, 0.02);
    }

    .revenue-table th {
        font-family: var(--font-display);
        font-size: 0.62rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: var(--color-on-surface-variant);
        padding: 1rem 1.5rem;
        text-align: left;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    }

    .revenue-table td {
        padding: 1.15rem 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        font-size: 0.85rem;
        color: var(--color-on-surface-muted);
        vertical-align: middle;
    }

    .revenue-table tbody tr {
        transition: background 0.15s ease;
    }
    .revenue-table tbody tr:hover {
        background: rgba(255, 255, 255, 0.02);
    }
    .revenue-table tbody tr:last-child td {
        border-bottom: none;
    }

    .revenue-val {
        font-family: var(--font-display);
        font-weight: 800;
        color: var(--color-gold-light);
        font-size: 0.9rem;
    }

    /* ══════════════════════════════════════
       RESPONSIVE
       ══════════════════════════════════════ */
    @media (max-width: 1024px) {
        .agent-header {
            flex-direction: column;
            gap: 1.5rem;
        }
        .agent-header > div:last-child {
            text-align: left !important;
            align-items: flex-start !important;
            flex-direction: row !important;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .mission-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .agent-title {
            font-size: 1.5rem;
        }

        .agent-header {
            padding-bottom: 2rem;
            margin-bottom: 2rem;
        }

        .mission-grid {
            grid-template-columns: 1fr;
        }

        .mission-card {
            padding: 1.5rem;
        }

        .card-client {
            font-size: 1rem;
        }

        .card-actions {
            flex-direction: column;
            align-items: stretch;
            gap: 0.6rem;
        }

        .card-link {
            text-align: center;
            display: block;
        }

        .card-link[style*="margin-left:auto"] {
            margin-left: 0 !important;
        }

        .verification-status {
            padding: 1.5rem;
        }

        .status-msg {
            font-size: 1rem;
        }

        .revenue-table th,
        .revenue-table td {
            padding: 0.85rem 1rem;
            font-size: 0.78rem;
        }

        .revenue-val {
            font-size: 0.8rem;
        }

        .agent-header > div:last-child {
            flex-direction: column !important;
            align-items: flex-start !important;
        }
    }

    @media (max-width: 480px) {
        .mission-card div[style*="grid-template-columns: 1fr 1fr"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endpush

@section('content')
<div class="agent-vault">

    @if(!$bodyguard->is_verified)
    <div class="verification-status">
        <span class="status-alert">AKSES KEAMANAN BELUM AKTIF</span>
        <h2 class="status-msg">Proses Verifikasi Identitas Sedang Berlangsung oleh Sistem.</h2>
        <p style="color:var(--color-on-surface-variant); margin-top:1rem; font-size:0.85rem;">
            Akses tugas dibatasi sampai token verifikasi disetujui.
        </p>
    </div>
    @endif

    <header class="agent-header">
        <div>
            <span class="hero-label" style="margin-bottom:1rem;">Halaman Guard</span>
            <h1 class="agent-title">Pantau<span> Aktivitas</span> Anda</h1>
        </div>
        <div style="text-align:right; display:flex; flex-direction:column; align-items:flex-end; gap:1rem;">
            <a href="{{ route('bodyguard.profile.edit') }}" class="btn-primary" style="font-size:0.72rem; padding:0.6rem 1.25rem;">
                Edit Profil
            </a>
        </div>
    </header>

    <!-- tugas AKTIF -->
   <section class="mission-section">
    <span class="section-label">Penugasan Aktif</span>
    @if($activeMissions->isEmpty())
        <div style="padding:5rem; text-align:center; background:var(--color-surface-container); border-radius:12px; border:1px dashed rgba(255,255,255,0.06);">
            <p style="color:var(--color-on-surface-variant); font-size:0.85rem; font-weight:500;">
                Tidak ada penugasan aktif saat ini.
            </p>
        </div>
    @else
        <div class="mission-grid">
            @foreach($activeMissions as $mission)
            <div class="mission-card">
                <span class="card-status">{{ strtoupper($mission->status) }}</span>
                <span class="entry-label" style="margin-bottom:0.5rem; display:block;">Klien Target</span>
                <span class="card-client">{{ $mission->user->name }}</span>

                <span class="card-meta">
                    ID tugas: #GY-{{ $mission->id }} //
                    {{ \Carbon\Carbon::parse($mission->start_date)->format('M d') }} -
                    {{ \Carbon\Carbon::parse($mission->end_date)->format('M d, Y') }}
                    @if($mission->alamat)
                        <br>Lokasi: {{ $mission->alamat }}
                    @endif
                </span>
                
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem; margin-top:1.5rem;">
                    <div class="data-item" style="padding-left:1rem; border-left:1px solid rgba(220,20,60,0.15);">
                        <span class="entry-label" style="font-size:0.55rem;">Pendapatan Disetujui</span>
                        <span style="font-family:var(--font-display); font-size:0.9rem; font-weight:800; color:var(--color-gold-light);">
                            IDR {{ number_format($mission->total_price, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="data-item" style="padding-left:1rem; border-left:1px solid rgba(74,222,128,0.15);">
                        <span class="entry-label" style="font-size:0.55rem;">Status Koneksi</span>
                        <span style="font-family:var(--font-display); font-size:0.9rem; font-weight:800; color:#4ade80;">
                            AKTIF
                        </span>
                    </div>
                </div>

                <div class="card-actions">
                    <a href="{{ route('bookings.show', $mission) }}" class="card-link">
                        Detail tugas
                    </a>
                    <a href="{{ route('chat.show', $mission) }}" class="card-link" style="color:#60a5fa; margin-left:auto;">
                        Chat Aman
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</section>

    <!-- tugas MENDATANG -->
 @if(!$upcomingMissions->isEmpty())
<section class="mission-section">
    <span class="section-label">Penugasan Mendatang</span>
    <div class="mission-grid">
        @foreach($upcomingMissions as $mission)
        <div class="mission-card" style="border-style: dashed;">
            <span class="card-status" style="background:rgba(255,255,255,0.04); color:var(--color-on-surface-variant); border-color:rgba(255,255,255,0.08);">
                MENUNGGU
            </span>

            <span class="entry-label" style="margin-bottom:0.5rem; display:block;">Calon Klien</span>
            <span class="card-client">{{ $mission->user->name }}</span>

            <span class="card-meta">
                Mulai: {{ \Carbon\Carbon::parse($mission->start_date)->format('M d, Y') }}
                
                @if($mission->alamat)
                    <br>Lokasi: {{ $mission->alamat }}
                @endif
            </span>
            
                    <div class="card-actions">
                        <a href="{{ route('bookings.show', $mission) }}" class="btn-primary" style="width:100%; text-align:center; padding:0.8rem; font-size:0.75rem;">
                            Rincian
                        </a>
                    </div>
                </div>
                @endforeach
            </div> 
        </section>
        @endif

    <!-- RIWAYAT tugas -->
    <section class="mission-section" style="margin-top:6rem;">
        <span class="section-label" style="margin-bottom:1.5rem;">Riwayat tugas</span>
        <div class="revenue-vault">
            @if($completedMissions->isEmpty())
                <p style="padding:4rem; text-align:center; color:var(--color-on-surface-variant); font-size:0.85rem; font-weight:500;">
                    Belum ada riwayat tugas yang tercatat.
                </p>
            @else
                <div style="overflow-x:auto;">
                <table class="revenue-table">
                    <thead>
                        <tr>
                            <th>Klien</th>
                            <th>Tanggal Selesai</th>
                            <th>Total Pendapatan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($completedMissions as $mission)
                        <tr>
                            <td>
                                <span style="font-family:var(--font-display); font-weight:800; text-transform:uppercase; color:var(--color-on-surface); font-size:0.88rem;">
                                    {{ $mission->user->name }}
                                </span><br>
                                <span style="font-size:0.65rem; color:var(--color-on-surface-variant);">
                                    #GY-{{ $mission->id }}
                                </span>
                            </td>
                            <td>
                                <span style="font-size:0.85rem; font-weight:600; color:var(--color-on-surface-muted);">
                                    {{ $mission->end_date->format('M d, Y') }}
                                </span>
                            </td>
                            <td>
                                <span class="revenue-val">
                                    IDR {{ number_format($mission->total_price, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                <span class="card-status" style="position:static; padding:0.25rem 0.7rem; color:var(--color-on-surface-muted); background:rgba(255,255,255,0.04); border-color:rgba(255,255,255,0.08);">
                                    SELESAI
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            @endif
        </div>
    </section>

</div>
@endsection
