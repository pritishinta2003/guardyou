@extends('layouts.app')

@section('title', 'Otorisasi Misi // ' . ($bodyguard->user->name ?? 'Pengawal'))

@push('styles')
<style>
    .booking-split {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        min-height: 90vh;
        background: var(--color-surface);
        padding-top: 80px;
    }

    /* ===== LEFT: FORM OTORISASI ===== */
    .auth-terminal {
        padding: 5rem 6rem;
        background: radial-gradient(circle at 100% 0%, rgba(220,20,60,0.03) 0%, transparent 50%);
    }

    .auth-label {
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.25em;
        color: var(--color-gold);
        margin-bottom: 1rem;
        display: block;
    }

    .auth-title {
        font-size: 3.5rem;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: -0.04em;
        line-height: 1;
        margin-bottom: 3rem;
    }

    .authorization-form { max-width: 500px; }

    .field-group { margin-bottom: 2.5rem; }
    .field-label {
        display: block;
        font-size: 0.75rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: 0.15em;
        color: var(--color-on-surface); margin-bottom: 1rem;
    }

    .tactical-input {
        width: 100%;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 4px; padding: 1.2rem 1.5rem;
        color: var(--color-on-surface); font-size: 1rem;
        font-family: 'Inter', sans-serif;
        transition: all 0.3s ease;
    }
    .tactical-input:focus { border-color: var(--color-gold); outline: none; background: rgba(255,255,255,0.05); }

    textarea.tactical-input {
        min-height: 130px;
        resize: vertical;
        line-height: 1.7;
    }

    .price-calculation {
        margin-top: 4rem; padding-top: 2rem;
        border-top: 1px solid rgba(255,255,255,0.05);
    }
    .calc-row { display: flex; justify-content: space-between; margin-bottom: 1rem; }
    .calc-val { font-size: 1.2rem; font-weight: 900; color: var(--color-on-surface); }
    .calc-lbl { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--color-on-surface-variant); }
    .calc-total { margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(220,20,60,0.2); }
    .total-val { font-size: 2rem; font-weight: 950; color: var(--color-gold); letter-spacing: -0.04em; }

    /* Tombol kirim */
    .deploy-btn {
        width: 100%; padding: 1.2rem; margin-top: 3rem;
        font-size: 0.95rem; font-weight: 900; letter-spacing: 0.1em;
        text-transform: uppercase; font-family: var(--font-display);
        background: linear-gradient(135deg, var(--color-gold) 0%, var(--color-gold-dark) 100%);
        color: #fff; border: none; border-radius: 6px; cursor: pointer;
        box-shadow: 0 4px 24px rgba(220,20,60,0.4);
        transition: all 0.25s ease; display: flex; align-items: center; justify-content: center; gap: 0.75rem;
    }
    .deploy-btn:hover {
        background: linear-gradient(135deg, var(--color-gold-light) 0%, var(--color-gold) 100%);
        box-shadow: 0 8px 36px rgba(220,20,60,0.6);
        transform: translateY(-2px);
    }
    .deploy-btn:active { transform: translateY(0); }
    .deploy-btn svg { width: 20px; height: 20px; fill: #fff; flex-shrink: 0; }

    /* ===== RIGHT: RINGKASAN ASET ===== */
    .asset-summary {
        background: #050a0f;
        padding: 5rem;
        border-left: 1px solid rgba(255,255,255,0.05);
        display: flex; flex-direction: column; justify-content: center;
    }

    .asset-card-mini {
        background: rgba(255,255,255,0.02);
        border: 1px solid rgba(255,255,255,0.05);
        border-radius: 12px; overflow: hidden;
    }

    /* Avatar with fallback */
    .asset-visual-mini {
        height: 350px;
        position: relative;
        background: linear-gradient(135deg, #111827, #0d1117);
        overflow: hidden;
    }
    .asset-initial {
        position: absolute; inset: 0;
        display: flex; align-items: center; justify-content: center;
        font-family: var(--font-display);
        font-size: 9rem; font-weight: 900;
        color: rgba(220,20,60,0.15); user-select: none;
    }
    .asset-visual-mini img {
        position: absolute; inset: 0;
        width: 100%; height: 100%; object-fit: cover;
        filter: grayscale(60%); z-index: 1;
    }
    .asset-visual-mini::after {
        content: ''; position: absolute; inset: 0; z-index: 2;
        background: linear-gradient(to top, rgba(5,10,15,0.7) 0%, transparent 50%);
    }

    .asset-info-mini { padding: 2.5rem; }
    .asset-name-mini {
        font-size: 1.8rem; font-weight: 900;
        text-transform: uppercase; letter-spacing: -0.02em;
        margin-bottom: 0.5rem; display: block;
    }
    .asset-stats-row {
        display: flex; gap: 1rem; margin-top: 1.25rem;
    }
    .asset-stat {
        background: rgba(255,255,255,0.04);
        border-radius: 6px; padding: 0.5rem 0.85rem;
        display: flex; flex-direction: column;
    }
    .asset-stat-val { font-size: 0.9rem; font-weight: 800; color: #fff; }
    .asset-stat-lbl { font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--color-on-surface-muted); }

    .warning-box {
        margin-top: 2.5rem; padding: 1.5rem;
        background: rgba(220,20,60,0.05);
        border: 1px solid rgba(220,20,60,0.15);
        border-radius: 6px;
        font-size: 0.8rem; color: var(--color-on-surface-variant); line-height: 1.6;
    }
    .warning-box b { color: var(--color-gold); }

    @media (max-width: 900px) {
        .booking-split { grid-template-columns: 1fr; }
        .auth-terminal { padding: 3rem 2rem; }
        .asset-summary { display: none; }
    }
</style>
@endpush

@section('content')
<div class="booking-split">

    <!-- LEFT: AUTHORIZATION -->
    <main class="auth-terminal">
        <span class="auth-label">Otoritas Operasional</span>
        <h1 class="auth-title">Otorisasi<br>Misi</h1>

        <form action="{{ route('bookings.store', $bodyguard) }}" method="POST" class="authorization-form" id="bookingForm">
            @csrf

            <div class="field-group">
                <label class="field-label">Tanggal Mulai Misi</label>
                <input type="date" name="start_date" id="start_date"
                    value="{{ old('start_date') }}" min="{{ date('Y-m-d') }}"
                    class="tactical-input" required>
                @error('start_date')
                    <span style="color:var(--color-gold); font-size:0.75rem; margin-top:0.5rem; display:block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="field-group">
                <label class="field-label">Tanggal Selesai Misi</label>
                <input type="date" name="end_date" id="end_date"
                    value="{{ old('end_date') }}"
                    class="tactical-input" required>
                @error('end_date')
                    <span style="color:var(--color-); font-size:0.75rem; margin-top:0.5rem; display:block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="field-group">
                <label class="field-label">Alamat Misi</label>
                <textarea
                    name="alamat"
                    id="alamat"
                    class="tactical-input"
                    placeholder="Masukkan alamat lengkap lokasi penugasan..."
                >{{ old('alamat') }}</textarea>
                @error('alamat')
                    <span style="color:var(--color-gold); font-size:0.75rem; margin-top:0.5rem; display:block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="price-calculation" id="pricePreview" style="display:none;">
                <div class="calc-row">
                    <span class="calc-lbl">Durasi Penugasan</span>
                    <span class="calc-val" id="durationText">—</span>
                </div>
                <div class="calc-row">
                    <span class="calc-lbl">Tarif Harian Agen</span>
                    <span class="calc-val">IDR {{ number_format($bodyguard->daily_rate, 0, ',', '.') }}</span>
                </div>
                <div class="calc-row calc-total">
                    <span class="calc-lbl" style="color:var(--color-gold); font-weight:900;">Total Biaya</span>
                    <span class="total-val" id="totalText">—</span>
                </div>
            </div>

            <button type="submit" class="deploy-btn">
                <svg viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                Konfirmasi Pemesanan
            </button>
        </form>
    </main>

    <!-- RIGHT: ASSET SUMMARY -->
    <aside class="asset-summary">
        <span class="auth-label" style="margin-bottom:2rem;">Agen yang Dipilih</span>

        <div class="asset-card-mini">
            <div class="asset-visual-mini">
                <div class="asset-initial">{{ strtoupper(substr($bodyguard->user->name, 0, 1)) }}</div>
                @if($bodyguard->user->avatar)
                    <img src="{{ asset('uploads/' . $bodyguard->user->avatar) }}"
                         alt="{{ $bodyguard->user->name }}"
                         onerror="this.style.display='none'">
                @endif
            </div>
            <div class="asset-info-mini">
                <span class="auth-label" style="margin-bottom:0.5rem;">Agen Lapangan Elite</span>
                <span class="asset-name-mini">{{ $bodyguard->user->name }}</span>
                <p style="color:var(--color-on-surface-variant); font-size:0.85rem; line-height:1.6; margin-top:0.5rem;">
                    Terverifikasi untuk pengawalan eksekutif dan perlindungan aset berisiko tinggi.
                </p>
                <div class="asset-stats-row">
                    <div class="asset-stat">
                        <span class="asset-stat-val">{{ $bodyguard->experience_years }} th</span>
                        <span class="asset-stat-lbl">Pengalaman</span>
                    </div>
                    <div class="asset-stat">
                        <span class="asset-stat-val">{{ $bodyguard->height }} cm</span>
                        <span class="asset-stat-lbl">Tinggi</span>
                    </div>
                    <div class="asset-stat">
                        <span class="asset-stat-val">IDR {{ number_format($bodyguard->daily_rate/1000, 0) }}K</span>
                        <span class="asset-stat-lbl">Per Hari</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="warning-box">
            <b>PERINGATAN KEAMANAN:</b> Semua penugasan dipantau melalui jalur komunikasi terenkripsi.
            Pembatalan harus dilakukan minimal 24 jam sebelum waktu mulai.
            Otorisasi ini merupakan perjanjian hukum yang mengikat.
        </div>
    </aside>

</div>

@push('scripts')
<script>
const dailyRate  = {{ $bodyguard->daily_rate }};
const startInput = document.getElementById('start_date');
const endInput   = document.getElementById('end_date');
const pricePreview = document.getElementById('pricePreview');
const durationText = document.getElementById('durationText');
const totalText    = document.getElementById('totalText');

function calculatePrice() {
    const start = new Date(startInput.value);
    const end   = new Date(endInput.value);

    if (startInput.value && endInput.value && end > start) {
        const days  = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
        const total = days * dailyRate;

        durationText.textContent = days + ' Hari';
        totalText.textContent    = 'IDR ' + total.toLocaleString('id-ID');
        pricePreview.style.display = 'block';
        endInput.min = startInput.value;
    } else {
        pricePreview.style.display = 'none';
    }
}

startInput.addEventListener('change', calculatePrice);
endInput.addEventListener('change', calculatePrice);
</script>
@endpush
@endsection
