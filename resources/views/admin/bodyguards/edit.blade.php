@extends('layouts.app')

@section('title', 'Edit Agent — Admin')

@push('styles')
<style>
    .admin-form-page { padding: 7rem 1.5rem 4rem; max-width: 700px; margin: 0 auto; }
    .page-header { margin-bottom: 2rem; }
    .page-header h1 { font-size: 1.8rem; font-weight: 800; letter-spacing: -0.02em; margin-top: 0.5rem; }

    .form-card {
        background: var(--color-surface-container);
        border: 1px solid var(--color-border);
        border-radius: 12px; padding: 2rem; margin-bottom: 1.5rem;
    }
    .form-card-title {
        font-size: 0.65rem; font-weight: 800; text-transform: uppercase;
        letter-spacing: 0.2em; color: var(--color-gold);
        margin-bottom: 1.5rem; display: block;
    }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
    .form-group { margin-bottom: 1.25rem; }
    .form-group:last-child { margin-bottom: 0; }
    .form-label {
        display: block; font-size: 0.7rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.12em;
        color: var(--color-on-surface-muted); margin-bottom: 0.5rem;
    }
    .form-input {
        width: 100%; background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.1); border-radius: 8px;
        padding: 0.85rem 1rem; color: var(--color-on-surface);
        font-size: 0.9rem; font-family: var(--font-body);
        transition: border-color 0.2s ease; outline: none;
    }
    .form-input:focus { border-color: var(--color-gold); background: rgba(220,20,60,0.04); }
    .input-prefix-wrap {
        display: flex; align-items: center;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px; overflow: hidden;
    }
    .input-prefix-wrap:focus-within { border-color: var(--color-gold); }
    .input-prefix {
        padding: 0.85rem 1rem; font-size: 0.8rem; font-weight: 700;
        color: var(--color-on-surface-muted);
        background: rgba(255,255,255,0.03);
        border-right: 1px solid rgba(255,255,255,0.08);
        white-space: nowrap; flex-shrink: 0;
    }
    .input-prefix-wrap .form-input { border: none; background: transparent; border-radius: 0; }
    .input-prefix-wrap .form-input:focus { border: none; background: transparent; }
    .error-msg { font-size: 0.75rem; color: #f87171; font-weight: 500; margin-top: 0.4rem; display: block; }
    .form-actions { display: flex; gap: 1rem; justify-content: flex-end; }

    /* Toggle switch for is_verified */
    .toggle-wrap { display: flex; align-items: center; gap: 1rem; }
    .toggle-label { font-size: 0.85rem; color: var(--color-on-surface); font-weight: 600; }
    input[type="checkbox"].toggle { width: 44px; height: 24px; cursor: pointer; accent-color: var(--color-gold); }

    @media (max-width: 600px) { .form-row { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="admin-form-page">
    <div class="page-header">
        <a href="{{ route('admin.bodyguards.index') }}" style="color:var(--color-gold); text-decoration:none; font-size:0.85rem;">&larr; Kembali ke Agent List</a>
        <h1>Edit Agent <span style="color:var(--color-gold);">{{ $bodyguard->user->name }}</span></h1>
    </div>

    @if(session('success'))
        <div style="padding:1rem; background:rgba(34,197,94,0.1); color:#4ade80; border:1px solid rgba(34,197,94,0.2); border-radius:8px; margin-bottom:1.5rem; font-size:0.9rem;">
            ✔ {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.bodyguards.update', $bodyguard) }}">
        @csrf
        @method('PATCH')

        <!-- INFORMASI DASAR -->
        <div class="form-card">
            <span class="form-card-title">Informasi Dasar</span>
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $bodyguard->user->name) }}" class="form-input" required>
                @error('name') <span class="error-msg">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">No. KTP</label>
                <input type="text" name="ktp_number" value="{{ old('ktp_number', $bodyguard->ktp_number) }}" class="form-input" maxlength="20">
                @error('ktp_number') <span class="error-msg">{{ $message }}</span> @enderror
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Status Verifikasi</label>
                <div class="toggle-wrap" style="margin-top:0.5rem;">
                    <input type="checkbox" name="is_verified" id="is_verified" class="toggle" value="1"
                        @checked(old('is_verified', $bodyguard->is_verified))>
                    <label for="is_verified" class="toggle-label">
                        {{ $bodyguard->is_verified ? 'Terverifikasi' : 'Belum Terverifikasi' }}
                    </label>
                </div>
                @error('is_verified') <span class="error-msg">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- DATA FISIK -->
        <div class="form-card">
            <span class="form-card-title">Data Fisik</span>
            <div class="form-row">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Tinggi Badan (cm)</label>
                    <input type="number" name="height" value="{{ old('height', $bodyguard->height) }}"
                        class="form-input" required min="100" max="250">
                    @error('height') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Berat Badan (kg)</label>
                    <input type="number" name="weight" value="{{ old('weight', $bodyguard->weight) }}"
                        class="form-input" required min="40" max="200">
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
                    <input type="number" name="experience_years" value="{{ old('experience_years', $bodyguard->experience_years) }}"
                        class="form-input" required min="0" max="50">
                    @error('experience_years') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Tarif Harian</label>
                    <div class="input-prefix-wrap">
                        <span class="input-prefix">Rp</span>
                        <input type="number" name="daily_rate" value="{{ old('daily_rate', $bodyguard->daily_rate) }}"
                            class="form-input" required min="10000">
                    </div>
                    @error('daily_rate') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.bodyguards.index') }}" class="btn-outline">Batal</a>
            <button type="submit" class="btn-primary" style="padding:0.8rem 2rem;">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
