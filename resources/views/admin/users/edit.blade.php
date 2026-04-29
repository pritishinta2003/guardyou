@extends('layouts.app')

@section('title', 'Edit User — Admin')

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
    .form-input:disabled { opacity: 0.5; cursor: not-allowed; }
    .form-select {
        width: 100%; background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.1); border-radius: 8px;
        padding: 0.85rem 1rem; color: var(--color-on-surface);
        font-size: 0.9rem; font-family: var(--font-body);
        outline: none; cursor: pointer;
        transition: border-color 0.2s ease;
    }
    .form-select:focus { border-color: var(--color-gold); }
    .error-msg { font-size: 0.75rem; color: #f87171; font-weight: 500; margin-top: 0.4rem; display: block; }
    .form-actions { display: flex; gap: 1rem; justify-content: flex-end; }
</style>
@endpush

@section('content')
<div class="admin-form-page">
    <div class="page-header">
        <a href="{{ route('admin.users.index') }}" style="color:var(--color-gold); text-decoration:none; font-size:0.85rem;">&larr; Kembali ke User List</a>
        <h1>Edit User <span style="color:var(--color-gold);">{{ $user->name }}</span></h1>
    </div>

    @if(session('success'))
        <div style="padding:1rem; background:rgba(34,197,94,0.1); color:#4ade80; border:1px solid rgba(34,197,94,0.2); border-radius:8px; margin-bottom:1.5rem; font-size:0.9rem;">
            ✔ {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PATCH')

        <div class="form-card">
            <span class="form-card-title">Informasi Akun</span>

            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input" required>
                @error('name') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" required>
                @error('email') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Nomor Telepon</label>
                <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="form-input" placeholder="Opsional">
                @error('phone_number') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Role</label>
                <select name="role" class="form-select" @if($user->id === auth()->id()) disabled @endif>
                    <option value="user" @selected(old('role', $user->role) === 'user')>User</option>
                    <option value="bodyguard" @selected(old('role', $user->role) === 'bodyguard')>Bodyguard</option>
                    <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                </select>
                @if($user->id === auth()->id())
                    <input type="hidden" name="role" value="{{ $user->role }}">
                    <span style="font-size:0.72rem; color:var(--color-on-surface-variant); margin-top:0.35rem; display:block;">Anda tidak dapat mengubah role akun Anda sendiri.</span>
                @endif
                @error('role') <span class="error-msg">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.users.index') }}" class="btn-outline">Batal</a>
            <button type="submit" class="btn-primary" style="padding:0.8rem 2rem;">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
