@extends('layouts.app')

@section('title', 'Daftar Sebagai Bodyguard — GuardYou')

@push('styles')
<style>
    .edit-page {
        min-height: calc(100vh - 72px);
        padding: 3rem 2rem;
        max-width: 860px;
        margin: 0 auto;
    }

    .edit-header {
        margin-bottom: 2.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .form-card {
        background: var(--color-surface-container);
        border: 1px solid var(--color-border);
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 1.5rem;
    }
    .form-card-title {
        font-size: 0.65rem; font-weight: 800; text-transform: uppercase;
        letter-spacing: 0.2em; color: var(--color-gold);
        margin-bottom: 1.5rem; display: block;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }
    .form-group { margin-bottom: 1.25rem; }
    .form-group:last-child { margin-bottom: 0; }
    .form-label {
        display: block; font-size: 0.7rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.12em;
        color: var(--color-on-surface-muted); margin-bottom: 0.5rem;
    }
    .form-input {
        width: 100%;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px; padding: 0.85rem 1rem;
        color: var(--color-on-surface); font-size: 0.9rem;
        font-family: var(--font-body);
        transition: border-color 0.2s ease, background 0.2s ease;
        outline: none;
    }
    .form-input:focus {
        border-color: var(--color-gold);
        background: rgba(220,20,60,0.04);
    }
    .form-input::placeholder { color: var(--color-on-surface-variant); }
    .form-hint {
        font-size: 0.7rem; color: var(--color-on-surface-variant);
        margin-top: 0.35rem; display: block;
    }
    .error-msg { font-size: 0.75rem; color: #f87171; font-weight: 500; margin-top: 0.4rem; display: block; }

    .input-prefix-wrap {
        display: flex; align-items: center;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px;
        overflow: hidden;
        transition: border-color 0.2s ease;
    }
    .input-prefix-wrap:focus-within {
        border-color: var(--color-gold);
        background: rgba(220,20,60,0.04);
    }
    .input-prefix {
        padding: 0.85rem 1rem;
        font-size: 0.8rem; font-weight: 700;
        color: var(--color-on-surface-muted);
        background: rgba(255,255,255,0.03);
        border-right: 1px solid rgba(255,255,255,0.08);
        white-space: nowrap;
        flex-shrink: 0;
    }
    .input-prefix-wrap .form-input {
        border: none; background: transparent;
        border-radius: 0;
    }
    .input-prefix-wrap .form-input:focus {
        border: none; background: transparent;
    }

    .form-actions {
        display: flex; gap: 1rem; justify-content: flex-end;
        padding-top: 1rem;
    }

    .info-banner {
        background: rgba(255, 193, 7, 0.08);
        border: 1px solid rgba(255, 193, 7, 0.25);
        border-radius: 10px;
        padding: 1rem 1.25rem;
        margin-bottom: 2rem;
        display: flex;
        gap: 0.75rem;
        align-items: flex-start;
    }
    .info-banner-icon { font-size: 1.1rem; flex-shrink: 0; margin-top: 0.05rem; }
    .info-banner-text { font-size: 0.82rem; color: var(--color-on-surface-muted); line-height: 1.5; }
    .info-banner-text strong { color: #fbbf24; font-weight: 700; }

    @media (max-width: 600px) {
        .form-row { grid-template-columns: 1fr; }
        .form-actions { flex-direction: column; }
        .form-actions .btn-primary,
        .form-actions .btn-outline { width: 100%; text-align: center; }
    }
</style>
@endpush

@section('content')
<div class="edit-page">

    <div class="edit-header">
        <div>
            <div class="eyebrow" style="margin-bottom:0.5rem;">Bergabung sebagai Mitra</div>
            <h1 style="font-size:1.8rem; font-weight:900; color:#fff; letter-spacing:-0.02em;">
                Daftar Sebagai <span style="color:var(--color-gold);">Bodyguard</span>
            </h1>
        </div>
        <a href="{{ route('dashboard') }}" class="btn-outline" style="font-size:0.72rem;">← Kembali</a>
    </div>

    <div class="info-banner">
        <span class="info-banner-icon">&#9432;</span>
        <div class="info-banner-text">
            Setelah mendaftar, profil Anda akan <strong>ditinjau oleh admin</strong> sebelum aktif dan dapat ditemukan klien.
            Pastikan data yang Anda isi sudah benar.
        </div>
    </div>

    @if ($errors->any())
    <div style="background:rgba(248,113,113,0.08); border:1px solid rgba(248,113,113,0.25); border-radius:10px; padding:1rem 1.25rem; margin-bottom:1.5rem;">
        <ul style="list-style:none; padding:0; margin:0; font-size:0.82rem; color:#f87171;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('bodyguard.register.store') }}">
        @csrf

        <!-- IDENTITAS DIRI -->
        <div class="form-card">
            <span class="form-card-title">Identitas Diri</span>
            <div class="form-group">
                <label class="form-label">Nomor KTP</label>
                <input type="text" name="ktp_number" value="{{ old('ktp_number') }}"
                    class="form-input" required maxlength="20" placeholder="16 digit nomor KTP">
                <span class="form-hint">Nomor KTP harus unik dan valid.</span>
                @error('ktp_number') <span class="error-msg">{{ $message }}</span> @enderror
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Tanggal Lahir</label>
                <input type="date" name="dob" value="{{ old('dob') }}"
                    class="form-input" required>
                <span class="form-hint">Anda harus berusia minimal 17 tahun.</span>
                @error('dob') <span class="error-msg">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- DATA FISIK -->
        <div class="form-card">
            <span class="form-card-title">Data Fisik</span>
            <div class="form-row">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Tinggi Badan (cm)</label>
                    <input type="number" name="height" value="{{ old('height') }}"
                        class="form-input" required min="100" max="250" placeholder="170">
                    @error('height') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Berat Badan (kg)</label>
                    <input type="number" name="weight" value="{{ old('weight') }}"
                        class="form-input" required min="40" max="200" placeholder="75">
                    @error('weight') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- PENGALAMAN & TARIF -->
        <div class="form-card">
            <span class="form-card-title">Pengalaman & Tarif</span>
            <div class="form-row">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Pengalaman (Tahun)</label>
                    <input type="number" name="experience_years" value="{{ old('experience_years') }}"
                        class="form-input" required min="0" max="50" placeholder="5">
                    <span class="form-hint">Pengalaman dalam bidang keamanan</span>
                    @error('experience_years') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Tarif Harian</label>
                    <div class="input-prefix-wrap">
                        <span class="input-prefix">Rp</span>
                        <input type="number" name="daily_rate" value="{{ old('daily_rate') }}"
                            class="form-input" required min="10000" placeholder="500000">
                    </div>
                    <span class="form-hint">Ajukan tarif dari jasa anda</span>
                    @error('daily_rate') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('dashboard') }}" class="btn-outline">Batal</a>
            <button type="submit" class="btn-primary" style="padding:0.8rem 2rem;">
                Kirim Pendaftaran
            </button>
        </div>
    </form>

</div>
@endsection
