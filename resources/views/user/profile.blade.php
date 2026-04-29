@extends('layouts.app')

@section('page_title', 'Profil Saya')

@push('styles')
<style>
    /* ═══ FALLBACK CSS VARIABLES ═══ */
    /* Akan di-override oleh CSS external jika tersedia */
    :root {
        --color-bg: #080c14;
        --color-surface: #0f1219;
        --color-surface-elevated: #151a24;
        --color-gold: #dc143c;
        --color-gold-dark: #a0102e;
        --color-border: rgba(255, 255, 255, 0.08);
        --color-on-surface: #f1f5f9;
        --color-on-surface-muted: #94a3b8;
        --color-on-surface-variant: #64748b;
    }

    /* ═══ BASE STYLES ═══ */
    body {
        background-color: var(--color-bg);
        color: var(--color-on-surface);
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    /* ═══ TYPOGRAPHY ═══ */
    .eyebrow {
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: var(--color-gold);
        margin-bottom: 0.5rem;
    }

    .font-display {
        font-family: 'Inter', system-ui, sans-serif;
        letter-spacing: 0.05em;
    }

    h1 {
        font-weight: 700;
        letter-spacing: 0.05em;
    }

    /* ═══ INPUT STYLES ═══ */
    input[type="text"],
    input[type="email"],
    input[type="tel"],
    input[type="password"],
    textarea,
    select {
        background-color: var(--color-bg);
        border: 1px solid var(--color-border);
        color: #fff;
        outline: none;
        transition: all 0.2s ease;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="tel"]:focus,
    input[type="password"]:focus,
    textarea:focus,
    select:focus {
        border-color: var(--color-gold);
        box-shadow: 0 0 0 3px rgba(220, 20, 60, 0.15);
    }

    input::placeholder,
    textarea::placeholder {
        color: var(--color-on-surface-variant);
    }

    select option {
        background-color: var(--color-bg);
        color: #fff;
    }

    /* ═══ BUTTON STYLES ═══ */
    .btn-primary {
        background: linear-gradient(135deg, var(--color-gold) 0%, var(--color-gold-dark) 100%);
        color: #fff;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 25px rgba(220, 20, 60, 0.4);
    }

    .btn-primary:active {
        transform: translateY(0);
    }

    /* ═══ CARD EFFECTS ═══ */
    .group:hover > .absolute:first-child {
        opacity: 0.7;
    }

    /* ═══ ANIMATIONS ═══ */
    @keyframes ping {
        75%, 100% {
            transform: scale(2);
            opacity: 0;
        }
    }

    .animate-ping {
        animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
    }

    /* ═══ SCROLLBAR ═══ */
    ::-webkit-scrollbar {
        width: 6px;
    }

    ::-webkit-scrollbar-track {
        background: var(--color-bg);
    }

    ::-webkit-scrollbar-thumb {
        background: var(--color-border);
        border-radius: 3px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: var(--color-on-surface-variant);
    }

    /* ═══ RESPONSIVE ═══ */
    @media (max-width: 640px) {
        .text-3xl { font-size: 1.5rem; }
        .text-4xl { font-size: 1.875rem; }
        .text-5xl { font-size: 2.25rem; }
    }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6">

    <!-- Header -->
    <div class="mb-8 pt-4">
        <p class="eyebrow">Pengaturan</p>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl text-white">PROFIL</h1>
        <p class="text-sm sm:text-base mt-2" style="color: var(--color-on-surface-muted);">Kelola informasi akun dan preferensi keamanan Anda.</p>
    </div>

    <form method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

            <!-- ═══ Left Column: Avatar & Quick Info ═══ -->
            <div class="lg:col-span-1 space-y-6">
                
                <!-- Avatar Card -->
                <div class="group relative">
                    <div class="absolute -inset-[1px] rounded-2xl opacity-50 blur-sm transition-opacity duration-500" style="background: linear-gradient(180deg, rgba(220,20,60,0.3) 0%, transparent 50%, rgba(220,20,60,0.15) 100%);"></div>
                    
                    <div class="relative rounded-2xl p-6 text-center" style="background: var(--color-surface); border: 1px solid rgba(220,20,60,0.12);">
                        
                        <div class="relative inline-block mb-4">
                            
                            <div id="avatarPreview" class="w-28 h-28 rounded-full flex items-center justify-center text-4xl font-display text-white mx-auto shadow-lg overflow-hidden" style="background: linear-gradient(135deg, var(--color-gold) 0%, var(--color-gold-dark) 100%); box-shadow: 0 8px 30px rgba(220,20,60,0.3);">
                                
                                @if(auth()->user()->avatar)
                                    <img src="{{ asset('uploads/' . auth()->user()->avatar) }}" 
                                        class="w-full h-full object-cover" alt="Avatar"
                                        onerror="this.parentElement.innerHTML='{{ strtoupper(auth()->user()->name[0]) }}';">
                                @else
                                    {{ strtoupper(auth()->user()->name[0]) }}
                                @endif

                            </div>

                            <label for="avatar" class="absolute bottom-0 right-0 w-9 h-9 rounded-full flex items-center justify-center cursor-pointer transition-all shadow-lg" style="background: var(--color-surface); border: 2px solid var(--color-gold); color: var(--color-gold);"
                                onmouseover="this.style.background='var(--color-gold)'; this.style.color='#fff';"
                                onmouseout="this.style.background='var(--color-surface)'; this.style.color='var(--color-gold)';">
                                
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>

                                <input type="file" id="avatar" name="avatar" class="hidden" accept="image/*" onchange="previewAvatar(event)">
                            </label>

                        </div>

                        <p class="text-white font-semibold text-lg">{{ auth()->user()->name }}</p>
                        <p class="text-sm mt-0.5" style="color: var(--color-on-surface-muted);">{{ auth()->user()->email }}</p>

                        <div class="mt-3 inline-flex items-center gap-2 text-xs font-semibold px-3 py-1 rounded-full" style="background: rgba(34,197,94,0.08); color: #4ade80;">
                            <span class="relative flex h-1.5 w-1.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" style="background: #4ade80;"></span>
                                <span class="relative inline-flex rounded-full h-1.5 w-1.5" style="background: #22c55e;"></span>
                            </span>
                            Terverifikasi
                        </div>

                        @if ($errors->has('avatar'))
                            <p class="text-xs mt-3" style="color: var(--color-gold);">{{ $errors->first('avatar') }}</p>
                        @endif

                    </div>
                </div>

                <!-- Status Card -->
                <div class="group relative">
                    <div class="absolute -inset-[1px] rounded-2xl opacity-40 blur-sm transition-opacity duration-500" style="background: linear-gradient(180deg, rgba(220,20,60,0.2) 0%, transparent 50%, rgba(220,20,60,0.12) 100%);"></div>
                    <div class="relative rounded-2xl p-6" style="background: var(--color-surface); border: 1px solid rgba(220,20,60,0.1);">
                        <h3 class="font-display text-base tracking-wider text-white mb-4">INFO AKUN</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm" style="color: var(--color-on-surface-muted);">Role</span>
                                <span class="text-sm text-white font-medium capitalize">{{ auth()->user()->role }}</span>
                            </div>
                            <div style="border-top: 1px solid var(--color-border);"></div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm" style="color: var(--color-on-surface-muted);">Bergabung</span>
                                <span class="text-sm text-white font-medium">{{ auth()->user()->created_at->format('d M Y') }}</span>
                            </div>
                            <div style="border-top: 1px solid var(--color-border);"></div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm" style="color: var(--color-on-surface-muted);">Email</span>
                                <span class="text-sm font-medium" style="color: #4ade80;">Terverifikasi</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ═══ Right Column: Forms ═══ -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Personal Information -->
                <div class="group relative">
                    <div class="absolute -inset-[1px] rounded-2xl opacity-50 blur-sm transition-opacity duration-500" style="background: linear-gradient(135deg, rgba(220,20,60,0.25) 0%, transparent 40%, rgba(220,20,60,0.18) 100%);"></div>
                    <div class="relative rounded-2xl p-6 sm:p-8" style="background: var(--color-surface); border: 1px solid rgba(220,20,60,0.12);">
                        <h3 class="font-display text-lg sm:text-xl tracking-wider text-white mb-6 flex items-center gap-2.5">
                            <svg class="w-5 h-5 shrink-0" style="color: var(--color-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            INFORMASI PRIBADI
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <!-- Name -->
                            <div class="sm:col-span-2">
                                <label for="name" class="block text-sm font-medium mb-2" style="color: var(--color-on-surface-muted);">Nama Lengkap</label>
                                <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" 
                                    class="w-full rounded-xl px-4 py-3 text-sm"
                                    placeholder="Masukkan nama lengkap">
                                @error('name')
                                    <p class="text-xs mt-1.5" style="color: var(--color-gold);">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="sm:col-span-2">
                                <label for="email" class="block text-sm font-medium mb-2" style="color: var(--color-on-surface-muted);">Alamat Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" 
                                    class="w-full rounded-xl px-4 py-3 text-sm"
                                    placeholder="Masukkan alamat email">
                                @error('email')
                                    <p class="text-xs mt-1.5" style="color: var(--color-gold);">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone_number" class="block text-sm font-medium mb-2" style="color: var(--color-on-surface-muted);">Nomor Telepon</label>
                                <input type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number', auth()->user()->phone_number ?? '') }}" 
                                    class="w-full rounded-xl px-4 py-3 text-sm"
                                    placeholder="08xx-xxxx-xxxx">
                                @error('phone_number')
                                    <p class="text-xs mt-1.5" style="color: var(--color-gold);">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Gender -->
                            <div>
                                <label for="gender" class="block text-sm font-medium mb-2" style="color: var(--color-on-surface-muted);">Jenis Kelamin</label>
                                <div class="relative">
                                    <select id="gender" name="gender" 
                                        class="w-full rounded-xl px-4 py-3 text-sm appearance-none"
                                        style="padding-right: 2.5rem; background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 1.25rem;">
                                        <option value="">Pilih jenis kelamin</option>
                                        <option value="Pria" {{ (old('gender', auth()->user()->gender ?? '') == 'Pria') ? 'selected' : '' }}>Pria</option>
                                        <option value="Wanita" {{ (old('gender', auth()->user()->gender ?? '') == 'Wanita') ? 'selected' : '' }}>Wanita</option>
                                    </select>
                                </div>
                                @error('gender')
                                    <p class="text-xs mt-1.5" style="color: var(--color-gold);">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div class="sm:col-span-2">
                                <label for="address" class="block text-sm font-medium mb-2" style="color: var(--color-on-surface-muted);">Alamat Lengkap</label>
                                <textarea id="address" name="address" rows="3" 
                                    class="w-full rounded-xl px-4 py-3 text-sm resize-none"
                                    placeholder="Masukkan alamat lengkap">{{ old('address', auth()->user()->address ?? '') }}</textarea>
                                @error('address')
                                    <p class="text-xs mt-1.5" style="color: var(--color-gold);">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security -->
                <div class="group relative">
                    <div class="absolute -inset-[1px] rounded-2xl opacity-50 blur-sm transition-opacity duration-500" style="background: linear-gradient(135deg, rgba(220,20,60,0.25) 0%, transparent 40%, rgba(220,20,60,0.18) 100%);"></div>
                    <div class="relative rounded-2xl p-6 sm:p-8" style="background: var(--color-surface); border: 1px solid rgba(220,20,60,0.12);">
                        <h3 class="font-display text-lg sm:text-xl tracking-wider text-white mb-6 flex items-center gap-2.5">
                            <svg class="w-5 h-5 shrink-0" style="color: var(--color-gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            KEAMANAN
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <!-- Current Password -->
                            <div class="sm:col-span-2">
                                <label for="current_password" class="block text-sm font-medium mb-2" style="color: var(--color-on-surface-muted);">Password Saat Ini</label>
                                <div class="relative">
                                    <input type="password" id="current_password" name="current_password" 
                                        class="w-full rounded-xl px-4 py-3 text-sm"
                                        style="padding-right: 3rem;"
                                        placeholder="Kosongkan jika tidak ingin diubah">
                                    <button type="button" onclick="togglePassword('current_password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 transition-colors bg-transparent border-none cursor-pointer" style="color: var(--color-on-surface-variant);"
                                        onmouseover="this.style.color='var(--color-on-surface)';"
                                        onmouseout="this.style.color='var(--color-on-surface-variant)';">
                                        <svg class="w-5 h-5 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <svg class="w-5 h-5 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                    </button>
                                </div>
                                @error('current_password')
                                    <p class="text-xs mt-1.5" style="color: var(--color-gold);">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- New Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium mb-2" style="color: var(--color-on-surface-muted);">Password Baru</label>
                                <div class="relative">
                                    <input type="password" id="password" name="password" 
                                        class="w-full rounded-xl px-4 py-3 text-sm"
                                        style="padding-right: 3rem;"
                                        placeholder="Min. 8 karakter">
                                    <button type="button" onclick="togglePassword('password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 transition-colors bg-transparent border-none cursor-pointer" style="color: var(--color-on-surface-variant);"
                                        onmouseover="this.style.color='var(--color-on-surface)';"
                                        onmouseout="this.style.color='var(--color-on-surface-variant)';">
                                        <svg class="w-5 h-5 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <svg class="w-5 h-5 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="text-xs mt-1.5" style="color: var(--color-gold);">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium mb-2" style="color: var(--color-on-surface-muted);">Konfirmasi Password</label>
                                <div class="relative">
                                    <input type="password" id="password_confirmation" name="password_confirmation" 
                                        class="w-full rounded-xl px-4 py-3 text-sm"
                                        style="padding-right: 3rem;"
                                        placeholder="Ulangi password baru">
                                    <button type="button" onclick="togglePassword('password_confirmation', this)" class="absolute right-3 top-1/2 -translate-y-1/2 transition-colors bg-transparent border-none cursor-pointer" style="color: var(--color-on-surface-variant);"
                                        onmouseover="this.style.color='var(--color-on-surface)';"
                                        onmouseout="this.style.color='var(--color-on-surface-variant)';">
                                        <svg class="w-5 h-5 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <svg class="w-5 h-5 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 p-3 rounded-xl" style="background: rgba(8,12,20,0.6); border: 1px solid rgba(255,255,255,0.04);">
                            <p class="text-xs flex items-start gap-2" style="color: var(--color-on-surface-variant);">
                                <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Kosongkan ketiga field password jika Anda tidak ingin mengubah password.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-2">
                    <a href="{{ url('/home') }}" class="w-full sm:w-auto px-8 py-3 rounded-xl font-semibold text-center transition-all text-sm no-underline"
                        style="border: 1px solid var(--color-border); color: var(--color-on-surface-muted);"
                        onmouseover="this.style.borderColor='rgba(255,255,255,0.25)'; this.style.color='#fff'; this.style.background='rgba(255,255,255,0.03)';"
                        onmouseout="this.style.borderColor='var(--color-border)'; this.style.color='var(--color-on-surface-muted)'; this.style.background='transparent';">
                        Batal
                    </a>
                    <button type="submit" class="btn-primary w-full sm:w-auto px-8 py-3 rounded-xl text-sm flex items-center justify-center gap-2" style="box-shadow: 0 4px 20px rgba(220,20,60,0.3);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Perubahan
                    </button>
                </div>

                <!-- Success Message -->
                @if (session('status'))
                    <div id="successMsg" class="mt-5 px-4 py-3 rounded-xl flex items-center gap-3" style="background: rgba(34,197,94,0.08); border: 1px solid rgba(34,197,94,0.2); color: #4ade80;">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-sm">{{ session('status') }}</span>
                    </div>
                @endif
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Preview Avatar
    function previewAvatar(event) {
        const file = event.target.files[0];
        if (file) {
            // Validasi ukuran file (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal 2MB');
                event.target.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                const container = document.getElementById('avatarPreview');
                container.innerHTML = '<img src="' + e.target.result + '" alt="Preview" class="w-full h-full object-cover">';
            };
            reader.readAsDataURL(file);
        }
    }

    // Toggle Password Visibility
    function togglePassword(inputId, button) {
        const input = document.getElementById(inputId);
        const eyeOpen = button.querySelector('.eye-open');
        const eyeClosed = button.querySelector('.eye-closed');
        
        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }

    // Auto-hide success message
    document.addEventListener('DOMContentLoaded', function() {
        const successMsg = document.getElementById('successMsg');
        if (successMsg) {
            setTimeout(function() {
                successMsg.style.transition = 'opacity 0.5s ease';
                successMsg.style.opacity = '0';
                setTimeout(function() {
                    successMsg.remove();
                }, 500);
            }, 5000);
        }
    });
</script>
@endpush
@endsection