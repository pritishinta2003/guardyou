@extends('layouts.guest')

@section('title', 'Login — GuardYou')

@push('styles')
<style>
    .auth-page {
        min-height: calc(100vh - 72px);
        display: flex; align-items: center; justify-content: center;
        padding: 3rem 1.5rem;
        background: radial-gradient(ellipse 60% 50% at 50% 50%, rgba(220,20,60,0.04) 0%, transparent 65%);
    }
    .auth-wrap {
        display: grid; grid-template-columns: 1fr 1fr;
        width: 100%; max-width: 900px;
        background: var(--color-surface-container);
        border: 1px solid var(--color-border);
        border-radius: 16px; overflow: hidden;
        box-shadow: 0 30px 80px rgba(0,0,0,0.4);
    }
    .auth-visual {
        position: relative;
        background: url({{ asset('image/login.jpeg') }}) center/cover;
        min-height: 500px;
    }
    .auth-visual::before {
        content: '';
        position: absolute; inset: 0;
        background:
            linear-gradient(135deg, rgba(8,12,20,0.75) 0%, rgba(8,12,20,0.35) 100%),
            linear-gradient(to top, rgba(176,16,48,0.4) 0%, transparent 60%);
    }
    .auth-visual-content {
        position: absolute; inset: 0;
        display: flex; flex-direction: column; justify-content: flex-end;
        padding: 2.5rem;
    }
    .auth-visual-title {
        font-family: var(--font-display);
        font-size: 2rem; font-weight: 900;
        line-height: 1.1; color: #fff;
        margin-bottom: 0.75rem;
    }
    .auth-visual-title span { color: var(--color-gold); }
    .auth-visual-sub { font-size: 0.85rem; color: rgba(255,255,255,0.6); }

    .auth-form-side { padding: 3rem; }
    .auth-header { margin-bottom: 2.5rem; }
    .auth-title {
        font-family: var(--font-display);
        font-size: 1.6rem; font-weight: 900;
        color: #fff; margin-bottom: 0.4rem;
    }
    .auth-sub { font-size: 0.85rem; color: var(--color-on-surface-muted); }

    .form-group { margin-bottom: 1.25rem; }
    .form-label {
        display: block; font-size: 0.7rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.12em;
        color: var(--color-on-surface-muted); margin-bottom: 0.6rem;
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

    .error-msg { font-size: 0.75rem; color: #f87171; font-weight: 500; margin-top: 0.4rem; display: block; }

    .auth-footer {
        display: flex; justify-content: space-between; align-items: center;
        margin-top: 1.75rem; flex-wrap: wrap; gap: 0.75rem;
    }
    .auth-link {
        font-size: 0.78rem; color: var(--color-on-surface-muted);
        text-decoration: none; font-weight: 500; transition: color 0.2s;
    }
    .auth-link:hover { color: var(--color-gold); }

    @media (max-width: 700px) {
        .auth-wrap { grid-template-columns: 1fr; }
        .auth-visual { display: none; }
        .auth-form-side { padding: 2rem; }
    }
</style>
@endpush

@section('content')
<div class="auth-page">
    <div class="auth-wrap">
        <!-- Visual side -->
        <div class="auth-visual">
            <div class="auth-visual-content">
                <div class="eyebrow" style="margin-bottom:1rem;">Platform Terpercaya</div>
                <div class="auth-visual-title">Guard<span>You</span></div>
                <p class="auth-visual-sub">Keamanan personal profesional, kapan saja Anda membutuhkan.</p>
            </div>
        </div>

        <!-- Form side -->
        <div class="auth-form-side">
            <div class="auth-header">
                <h1 class="auth-title">Selamat Datang Kembali</h1>
                <p class="auth-sub">Masuk ke akun GuardYou Anda</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="form-input" required autofocus autocomplete="username"
                        placeholder="nama@email.com">
                    @error('email') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password"
                        class="form-input" required autocomplete="current-password"
                        placeholder="Masukkan password">
                    @error('password') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn-primary" style="width:100%; padding:0.9rem; font-size:0.85rem; margin-top:0.5rem;">
                    Masuk
                </button>

                <div class="auth-footer">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="auth-link">Belum punya akun? <span style="color:var(--color-gold);">Daftar</span></a>
                    @endif
                    <a href="{{ route('password.request') }}" class="auth-link">Lupa Password?</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
