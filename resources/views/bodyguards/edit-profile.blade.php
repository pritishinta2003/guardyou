@extends('layouts.app')

@section('title', 'Edit Profil — GuardYou')

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

    /* ── AVATAR UPLOAD ── */
    .avatar-section {
        display: flex;
        align-items: center;
        gap: 2rem;
        padding: 2rem;
        background: var(--color-surface-container);
        border: 1px solid var(--color-border);
        border-radius: 12px;
        margin-bottom: 2rem;
    }
    .avatar-preview {
        width: 100px; height: 100px;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid rgba(220,20,60,0.4);
        flex-shrink: 0;
        background: linear-gradient(135deg, #111827, #0d1117);
        position: relative;
        display: flex; align-items: center; justify-content: center;
    }
    .avatar-preview img {
        position: absolute; inset: 0;
        width: 100%; height: 100%; object-fit: cover;
    }
    .avatar-initial {
        font-family: var(--font-display);
        font-size: 2.8rem; font-weight: 900;
        color: rgba(220,20,60,0.7);
        text-transform: uppercase;
        line-height: 1;
        position: relative; z-index: 0;
    }
    .avatar-upload-btn {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.6rem 1.25rem;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 8px;
        color: var(--color-on-surface);
        font-size: 0.78rem; font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .avatar-upload-btn:hover {
        background: rgba(220,20,60,0.08);
        border-color: rgba(220,20,60,0.3);
        color: var(--color-gold);
    }
    #avatarInput { display: none; }

    /* ── FORM CARD ── */
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

    /* Rate input with prefix */
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

    @media (max-width: 600px) {
        .form-row { grid-template-columns: 1fr; }
        .avatar-section { flex-direction: column; text-align: center; }
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
            <div class="eyebrow" style="margin-bottom:0.5rem;">Profil Saya</div>
            <h1 style="font-size:1.8rem; font-weight:900; color:#fff; letter-spacing:-0.02em;">
                Edit <span style="color:var(--color-gold);">Profil</span>
            </h1>
        </div>
        <a href="{{ route('dashboard') }}" class="btn-outline" style="font-size:0.72rem;">← Kembali ke Dashboard</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success" style="margin-bottom:1.5rem;">
        {{ session('success') }}
    </div>
    @endif

    <form method="POST" action="{{ route('bodyguard.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <!-- FOTO PROFIL -->
        <div class="avatar-section">
            <div class="avatar-preview" id="avatarPreview">
                <span class="avatar-initial" id="avatarInitial">
                    {{ strtoupper(substr($bodyguard->user->name, 0, 1)) }}
                </span>
                @if($bodyguard->user->avatar)
                    <img src="{{ asset('uploads/' . $bodyguard->user->avatar) }}"
                         alt="{{ $bodyguard->user->name }}" id="avatarImg"
                         style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:1;"
                         onerror="this.style.display='none'">
                @endif
            </div>
            <div>
                <div style="font-size:0.9rem; font-weight:700; color:#fff; margin-bottom:0.4rem;">
                    Foto Profil
                </div>
                <div style="font-size:0.78rem; color:var(--color-on-surface-muted); margin-bottom:1rem;">
                    JPG, PNG atau WebP. Maksimal 2MB.
                </div>
                <label for="avatarInput" class="avatar-upload-btn">
                    Ganti Foto
                </label>
                <input type="file" id="avatarInput" name="avatar" accept="image/*">
                @error('avatar') <span class="error-msg" style="margin-top:0.5rem;">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- INFORMASI DASAR -->
        <div class="form-card">
            <span class="form-card-title">Informasi Dasar</span>
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $bodyguard->user->name) }}"
                    class="form-input" required placeholder="Nama lengkap Anda">
                @error('name') <span class="error-msg">{{ $message }}</span> @enderror
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Email</label>
                <input type="email" value="{{ $bodyguard->user->email }}"
                    class="form-input" disabled style="opacity:0.5; cursor:not-allowed;">
                <span class="form-hint">Email tidak dapat diubah di sini.</span>
            </div>
        </div>

        <!-- DATA FISIK -->
        <div class="form-card">
            <span class="form-card-title">Data Fisik</span>
            <div class="form-row">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Tinggi Badan (cm)</label>
                    <input type="number" name="height" value="{{ old('height', $bodyguard->height) }}"
                        class="form-input" required min="100" max="250" placeholder="170">
                    @error('height') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Berat Badan (kg)</label>
                    <input type="number" name="weight" value="{{ old('weight', $bodyguard->weight) }}"
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
                    <input type="number" name="experience_years" value="{{ old('experience_years', $bodyguard->experience_years) }}"
                        class="form-input" required min="0" max="50" placeholder="5">
                    @error('experience_years') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Tarif Harian</label>
                    <div class="input-prefix-wrap">
                        <span class="input-prefix">Rp</span>
                        <input type="number" name="daily_rate" value="{{ old('daily_rate', $bodyguard->daily_rate) }}"
                            class="form-input" required min="10000" placeholder="500000">
                    </div>
                    @error('daily_rate') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('dashboard') }}" class="btn-outline">Batal</a>
            <button type="submit" class="btn-primary" style="padding:0.8rem 2rem;">Simpan Perubahan</button>
        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
    // Live avatar preview
    document.getElementById('avatarInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(ev) {
            const preview = document.getElementById('avatarPreview');
            // Remove any existing preview img
            let existing = preview.querySelector('img');
            if (existing) existing.remove();
            // Add new preview on top of initial
            const img = document.createElement('img');
            img.src = ev.target.result;
            img.alt = 'Preview';
            img.style.cssText = 'position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:1;';
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush
