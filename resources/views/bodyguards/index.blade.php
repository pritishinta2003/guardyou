@extends('layouts.app')

@section('title', 'Find Bodyguard — GuardYou')
@section('meta_description', 'Temukan bodyguard profesional terverifikasi. Filter berdasarkan tarif, pengalaman, dan lokasi.')

@push('styles')
<style>
    .catalog-layout {
        display: grid;
        grid-template-columns: 280px 1fr;
        min-height: calc(100vh - 72px);
    }

    /* ── SIDEBAR ── */
    .catalog-sidebar {
        padding: 2.5rem 1.75rem;
        border-right: 1px solid var(--color-border);
        background: var(--color-surface-container);
        position: sticky;
        top: 72px;
        height: calc(100vh - 72px);
        overflow-y: auto;
    }
    .catalog-sidebar::-webkit-scrollbar { width: 3px; }
    .catalog-sidebar::-webkit-scrollbar-thumb { background: rgba(220,20,60,0.15); border-radius: 2px; }

    .filter-section { margin-bottom: 2rem; }
    .filter-label {
        display: block; font-size: 0.68rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.15em;
        color: var(--color-on-surface-muted); margin-bottom: 0.75rem;
    }
    .filter-input {
        width: 100%;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px; padding: 0.75rem 1rem;
        color: var(--color-on-surface); font-size: 0.85rem;
        font-family: var(--font-body);
        margin-bottom: 0.5rem;
        transition: border-color 0.2s ease;
        outline: none;
    }
    .filter-input:focus { border-color: var(--color-gold); }
    .filter-input::placeholder { color: var(--color-on-surface-variant); }

    .sidebar-divider {
        height: 1px; background: var(--color-border);
        margin: 1.5rem 0;
    }

    /* ── MAIN ── */
    .catalog-main { padding: 2.5rem 2.5rem 3rem; }

    .catalog-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 2.5rem; gap: 1rem; flex-wrap: wrap;
    }
    .catalog-title {
        font-size: 1.8rem; font-weight: 900;
        color: #fff; letter-spacing: -0.02em;
    }
    .catalog-title span { color: var(--color-gold); }
    .catalog-count {
        font-size: 0.75rem; color: var(--color-on-surface-muted);
        background: var(--color-surface-container);
        border: 1px solid var(--color-border);
        padding: 0.4rem 1rem; border-radius: 20px;
        font-weight: 600;
    }

    /* ── AGENT CARDS ── */
    .agents-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1.25rem;
    }
    .agent-card {
        background: var(--color-surface-container);
        border: 1px solid var(--color-border);
        border-radius: 12px; overflow: hidden;
        text-decoration: none; color: inherit; display: block;
        transition: border-color 0.2s ease, transform 0.25s ease, box-shadow 0.25s ease;
    }
    .agent-card:hover {
        border-color: rgba(220,20,60,0.3);
        transform: translateY(-4px);
        box-shadow: 0 16px 40px rgba(0,0,0,0.4);
    }

    .agent-visual {
        height: 160px;
        position: relative;
        background: var(--color-surface-high);
        overflow: hidden;
    }
    .agent-visual img {
        position: absolute; inset: 0;
        width: 100%; height: 150%; object-fit: cover;
    }
    .agent-visual-gradient {
        position: absolute; inset: 0;
        background: linear-gradient(0deg, rgba(17,24,39,0.8) 0%, transparent 60%);
    }
    .agent-initial {
        width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center;
        font-family: var(--font-display);
        font-size: 3rem; font-weight: 900;
        color: rgba(220,20,60,0.5);
        background: linear-gradient(135deg, #111827, #0d1117);
    }

    .agent-body { padding: 1.15rem; }
    .agent-badge {
        font-size: 0.58rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.15em; color: var(--color-gold);
        margin-bottom: 0.35rem; display: block;
    }
    .agent-name {
        font-size: 0.95rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: -0.01em;
        color: #fff; margin-bottom: 0.75rem;
    }
    .agent-stats {
        display: flex; gap: 0.75rem; margin-bottom: 1rem;
    }
    .stat-chip {
        display: flex; flex-direction: column;
        background: rgba(255,255,255,0.04);
        border-radius: 6px; padding: 0.35rem 0.55rem;
    }
    .stat-chip-val { font-size: 0.78rem; font-weight: 800; color: #fff; }
    .stat-chip-lbl { font-size: 0.55rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--color-on-surface-muted); }

    .agent-footer {
        display: flex; justify-content: space-between; align-items: center;
        padding-top: 0.85rem;
        border-top: 1px solid var(--color-border);
    }
    .agent-price {
        font-family: var(--font-display);
        font-size: 0.95rem; font-weight: 900; color: var(--color-gold);
    }
    .agent-price-lbl { font-size: 0.58rem; color: var(--color-on-surface-muted); font-weight: 500; }

    /* ── EMPTY ── */
    .empty-state {
        grid-column: 1/-1; text-align: center;
        padding: 6rem 2rem;
        color: var(--color-on-surface-muted);
    }
    .empty-state h3 { font-size: 1.2rem; color: var(--color-on-surface); margin-bottom: 0.75rem; }

    @media (max-width: 900px) {
        .catalog-layout { grid-template-columns: 1fr; }
        .catalog-sidebar { position: static; height: auto; border-right: none; border-bottom: 1px solid var(--color-border); }
    }
    @media (max-width: 600px) {
        .catalog-main { padding: 1.5rem; }
        .agents-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="catalog-layout">

    <!-- SIDEBAR -->
    <aside class="catalog-sidebar">
        <div class="eyebrow" style="margin-bottom:1.5rem;">Filter</div>

        <form method="GET" action="{{ route('bodyguards.index') }}">

            <div class="filter-section">
                <label class="filter-label">Nama</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="filter-input" placeholder="Cari nama...">
            </div>

            <div class="sidebar-divider"></div>

            <div class="filter-section">
                <label class="filter-label">Tarif Harian (Rp)</label>
                <input type="number" name="min_rate" value="{{ request('min_rate') }}"
                    class="filter-input" placeholder="Minimum">
                <input type="number" name="max_rate" value="{{ request('max_rate') }}"
                    class="filter-input" placeholder="Maksimum">
            </div>

            <div class="sidebar-divider"></div>

            <div class="filter-section">
                <label class="filter-label">Pengalaman (Tahun)</label>
                <input type="number" name="min_experience" value="{{ request('min_experience') }}"
                    class="filter-input" placeholder="Minimum tahun">
            </div>

            <div style="display:flex; flex-direction:column; gap:0.6rem; margin-top:1rem;">
                <button type="submit" class="btn-primary" style="width:100%; padding:0.75rem;">Cari</button>
                <a href="{{ route('bodyguards.index') }}" class="btn-outline" style="width:100%; text-align:center; padding:0.75rem;">Reset</a>
            </div>
        </form>
    </aside>

    <!-- MAIN -->
    <main class="catalog-main">
        <div class="catalog-header">
            <div>
                <div class="eyebrow" style="margin-bottom:0.5rem;">Temukan Bodyguard</div>
                <h1 class="catalog-title">Guard <span>you</span></h1>
            </div>
            <div class="catalog-count">{{ $bodyguards->total() }} tersedia</div>
        </div>

        <div class="agents-grid">
            @forelse($bodyguards as $bodyguard)
            <a href="{{ route('bodyguards.show', $bodyguard) }}" class="agent-card">
                <div class="agent-visual">
                    <div class="agent-initial">{{ strtoupper(substr($bodyguard->user->name, 0, 1)) }}</div>
                    @if($bodyguard->user->avatar)
                        <img src="{{ asset('uploads/' . $bodyguard->user->avatar) }}" alt="{{ $bodyguard->user->name }}">
                    @endif
                    <div class="agent-visual-gradient"></div>
                </div>

                <div class="agent-body">
                    <span class="agent-badge">✓ Terverifikasi</span>
                    <div class="agent-name">{{ $bodyguard->user->name }}</div>

                    <div class="agent-stats">
                        <div class="stat-chip">
                            <span class="stat-chip-val">{{ $bodyguard->experience_years }}y</span>
                            <span class="stat-chip-lbl">Pengalaman</span>
                        </div>
                        <div class="stat-chip">
                            <span class="stat-chip-val">{{ $bodyguard->height }}cm</span>
                            <span class="stat-chip-lbl">Tinggi</span>
                        </div>
                    </div>

                    <div class="agent-footer">
                        <div>
                            <div class="agent-price">Rp {{ number_format($bodyguard->daily_rate, 0, ',', '.') }}</div>
                            <div class="agent-price-lbl">per hari</div>
                        </div>
                        <span class="btn-outline" style="font-size:0.65rem; padding:0.4rem 0.85rem;">Lihat →</span>
                    </div>
                </div>
            </a>

            @empty
            <div class="empty-state">
                <h3>Tidak ada bodyguard yang cocok</h3>
                <p style="margin-bottom:1.5rem;">Coba ubah filter pencarian Anda.</p>
                <a href="{{ route('bodyguards.index') }}" class="btn-primary">Tampilkan Semua</a>
            </div>
            @endforelse
        </div>

        <div style="margin-top:3rem;">
            {{ $bodyguards->withQueryString()->links() }}
        </div>
    </main>
</div>
@endsection