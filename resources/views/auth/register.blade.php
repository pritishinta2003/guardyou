@extends('layouts.guest')

@section('title', 'Daftar — GuardYou')

@push('styles')
<style>
    .auth-page {
        min-height: calc(100vh - 72px);
        display: flex; align-items: center; justify-content: center;
        padding: 3rem 1.5rem;
        background: radial-gradient(ellipse 60% 50% at 50% 50%, rgba(220,20,60,0.04) 0%, transparent 65%);
    }
    .auth-card {
        width: 100%; max-width: 520px;
        background: var(--color-surface-container);
        border: 1px solid var(--color-border);
        border-radius: 16px; padding: 2.5rem 2.5rem 2rem;
        box-shadow: 0 30px 80px rgba(0,0,0,0.4);
    }
    .auth-header { margin-bottom: 2rem; }
    .auth-brand {
        font-family: var(--font-display);
        font-size: 1.2rem; font-weight: 900;
        text-transform: uppercase; letter-spacing: -0.02em;
        color: #fff; margin-bottom: 1.25rem;
    }
    .auth-brand span { color: var(--color-gold); }
    .auth-title {
        font-family: var(--font-display);
        font-size: 1.6rem; font-weight: 900;
        color: #fff; margin-bottom: 0.4rem;
    }
    .auth-sub { font-size: 0.85rem; color: var(--color-on-surface-muted); }

    .form-group { margin-bottom: 1.1rem; }
    .form-label {
        display: block; font-size: 0.7rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.12em;
        color: var(--color-on-surface-muted); margin-bottom: 0.5rem;
    }
    .form-input, .form-select {
        width: 100%;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px; padding: 0.85rem 1rem;
        color: var(--color-on-surface); font-size: 0.9rem;
        font-family: var(--font-body);
        transition: border-color 0.2s ease, background 0.2s ease;
        outline: none;
    }
    .form-input:focus, .form-select:focus {
        border-color: var(--color-gold);
        background: rgba(220,20,60,0.04);
    }
    .form-input::placeholder { color: var(--color-on-surface-variant); }
    .form-select option { background: var(--color-surface-container); color: #fff; }

    .error-msg { font-size: 0.75rem; color: #f87171; font-weight: 500; margin-top: 0.4rem; display: block; }

    .auth-footer { text-align: center; margin-top: 1.5rem; }
    .auth-link {
        font-size: 0.78rem; color: var(--color-on-surface-muted);
        text-decoration: none; transition: color 0.2s;
    }
    .auth-link:hover { color: var(--color-on-surface); }
</style>
@endpush

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-brand">Guard<span>You</span></div>
            <h1 class="auth-title">Buat Akun</h1>
            <p class="auth-sub">Bergabung dan temukan perlindungan terbaik untuk Anda</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="form-input" required autofocus autocomplete="name"
                    placeholder="Masukkan nama lengkap">
                @error('name') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="form-input" required autocomplete="username"
                    placeholder="nama@email.com">
                @error('email') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem;">
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password"
                        class="form-input" required autocomplete="new-password"
                        placeholder="Min. 8 karakter">
                    @error('password') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation"
                        class="form-input" required autocomplete="new-password"
                        placeholder="Ulangi password">
                </div>
            </div>

            <button type="submit" class="btn-primary" style="width:100%; padding:0.9rem; font-size:0.85rem; margin-top:0.5rem;">
                Buat Akun
            </button>

            <div class="auth-footer">
                <a href="{{ route('login') }}" class="auth-link">
                    Sudah punya akun? <span style="color:var(--color-gold);">Masuk</span>
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
