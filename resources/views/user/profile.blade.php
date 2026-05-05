@extends('layouts.app')

@section('page_title', 'Profil Saya')

@push('styles')
<style>
    /* ═══ CSS VARIABLES ═══ */
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
        --color-success: #4ade80;
        --color-success-bg: rgba(34,197,94,0.08);
        --color-success-border: rgba(34,197,94,0.2);
    }

    /* ═══ BASE STYLES ═══ */
    body {
        background-color: var(--color-bg);
        color: var(--color-on-surface);
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    /* ═══ UTILITY CLASSES ═══ */
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

    .text-gold { color: var(--color-gold); }
    .text-muted { color: var(--color-on-surface-muted); }
    .text-variant { color: var(--color-on-surface-variant); }
    .text-success { color: var(--color-success); }
    .bg-surface { background: var(--color-surface); }
    .border-subtle { border: 1px solid var(--color-border); }
    .border-gold-subtle { border: 1px solid rgba(220,20,60,0.12); }
    .divider { border-top: 1px solid var(--color-border); }

    /* ═══ CARD GLOW EFFECT ═══ */
    .card-glow {
        position: relative;
    }
    .card-glow::before {
        content: '';
        position: absolute;
        inset: -1px;
        border-radius: 1rem;
        opacity: 0.4;
        blur: 8px;
        transition: opacity 0.5s;
        background: linear-gradient(180deg, rgba(220,20,60,0.3) 0%, transparent 50%, rgba(220,20,60,0.15) 100%);
        z-index: 0;
        pointer-events: none;
    }
    .card-glow:hover::before {
        opacity: 0.7;
    }
    .card-glow > .card-inner {
        position: relative;
        z-index: 1;
    }

    /* ═══ INPUT STYLES ═══ */
    .form-input {
        background-color: var(--color-bg);
        border: 1px solid var(--color-border);
        color: #fff;
        outline: none;
        transition: all 0.2s ease;
        height: 46px;
        border-radius: 0.75rem;
        padding: 0 1rem;
        font-size: 0.875rem;
        width: 100%;
    }

    .form-input:focus {
        border-color: var(--color-gold);
        box-shadow: 0 0 0 3px rgba(220, 20, 60, 0.15);
    }

    .form-input::placeholder { color: var(--color-on-surface-variant); }
    select.form-input option { background-color: var(--color-bg); color: #fff; }

    /* ═══ BUTTON STYLES ═══ */
    .btn-primary {
        background: linear-gradient(135deg, var(--color-gold) 0%, var(--color-gold-dark) 100%);
        color: #fff;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 20px rgba(220,20,60,0.3);
    }
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 25px rgba(220,20,60,0.4);
    }
    .btn-primary:active { transform: translateY(0); }

    .btn-cancel {
        border: 1px solid var(--color-border);
        color: var(--color-on-surface-muted);
        background: transparent;
        transition: all 0.2s ease;
    }
    .btn-cancel:hover {
        border-color: rgba(255,255,255,0.25);
        color: #fff;
        background: rgba(255,255,255,0.03);
    }

    .avatar-edit-btn {
        background: var(--color-surface);
        border: 2px solid var(--color-gold);
        color: var(--color-gold);
        transition: all 0.2s ease;
    }
    .avatar-edit-btn:hover {
        background: var(--color-gold);
        color: #fff;
    }

    /* ═══ ANIMATIONS ═══ */
    @keyframes ping {
        75%, 100% { transform: scale(2); opacity: 0; }
    }
    .animate-ping { animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite; }

    /* ═══ SCROLLBAR ═══ */
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: var(--color-bg); }
    ::-webkit-scrollbar-thumb { background: var(--color-border); border-radius: 3px; }
    ::-webkit-scrollbar-thumb:hover { background: var(--color-on-surface-variant); }

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
        <h1 class="text-3xl sm:text-4xl lg:text-5xl text-white font-bold font-display">PROFIL</h1>
        <p class="text-sm sm:text-base mt-2 text-muted">Kelola informasi akun dan preferensi keamanan Anda.</p>
    </div>

    <form method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

            <!-- ═══ Left Column: Avatar & Quick Info ═══ -->
            <div class="lg:col-span-1 space-y-6">
                
                <!-- Avatar Card -->
                <div class="card-glow">
                    <div class="card-inner bg-surface border-gold-subtle rounded-2xl p-6 text-center">
                        
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

                            <label for="avatar" class="avatar-edit-btn absolute bottom-0 right-0 w-9 h-9 rounded-full flex items-center justify-center cursor-pointer shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <input type="file" id="avatar" name="avatar" class="hidden" accept="image/*" onchange="previewAvatar(event)">
                            </label>
                        </div>

                        <p class="text-white font-semibold text-lg">{{ auth()->user()->name }}</p>
                        <p class="text-sm mt-0.5 text-muted">{{ auth()->user()->email }}</p>

                        <div class="mt-3 inline-flex items-center gap-2 text-xs font-semibold px-3 py-1.5 rounded-full" style="background: var(--color-success-bg); color: var(--color-success);">
                            <span class="relative flex h-1.5 w-1.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" style="background: var(--color-success);"></span>
                                <span class="relative inline-flex rounded-full h-1.5 w-1.5" style="background: #22c55e;"></span>
                            </span>
                            Terverifikasi
                        </div>

                        @if ($errors->has('avatar'))
                            <p class="text-xs mt-3 text-gold">{{ $errors->first('avatar') }}</p>
                        @endif
                    </div>
                </div>

                <!-- Status Card -->
                <div class="card-glow">
                    <div class="card-inner bg-surface border-gold-subtle rounded-2xl p-6">
                        <h3 class="font-display text-base tracking-wider text-white mb-4">INFO AKUN</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-muted">Role</span>
                                <span class="text-sm text-white font-medium capitalize">{{ auth()->user()->role }}</span>
                            </div>
                            <div class="divider"></div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-muted">Bergabung</span>
                                <span class="text-sm text-white font-medium">{{ auth()->user()->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="divider"></div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-muted">Email</span>
                                <span class="text-sm font-medium text-success">Terverifikasi</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ═══ Right Column: Forms ═══ -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Personal Information -->
                <div class="card-glow">
                    <div class="card-inner bg-surface border-gold-subtle rounded-2xl p-6 sm:p-8">
                        <h3 class="font-display text-lg sm:text-xl tracking-wider text-white mb-6 flex items-center gap-2.5">
                            <svg class="w-5 h-5 shrink-0 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            INFORMASI PRIBADI
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium mb-2 text-muted">Nama Lengkap</label>
                                <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" 
                                    class="form-input" placeholder="Masukkan nama lengkap">
                                @error('name')
                                    <p class="text-xs mt-1.5 text-gold">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium mb-2 text-muted">Alamat Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" 
                                    class="form-input" placeholder="Masukkan alamat email">
                                @error('email')
                                    <p class="text-xs mt-1.5 text-gold">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone (Full Width) -->
                            <div class="sm:col-span-2">
                                <label for="phone_number" class="block text-sm font-medium mb-2 text-muted">Nomor Telepon</label>
                                <input type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number', auth()->user()->phone_number ?? '') }}" 
                                    class="form-input" placeholder="08xx-xxxx-xxxx">
                                @error('phone_number')
                                    <p class="text-xs mt-1.5 text-gold">{{ $message }}</p>
                                @enderror
                            </div>                         
                        </div>
                    </div>
                </div>

                <!-- Security -->
                <div class="card-glow">
                    <div class="card-inner bg-surface border-gold-subtle rounded-2xl p-6 sm:p-8">
                        <h3 class="font-display text-lg sm:text-xl tracking-wider text-white mb-6 flex items-center gap-2.5">
                            <svg class="w-5 h-5 shrink-0 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            KEAMANAN
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <!-- Current Password -->
                            <div class="sm:col-span-2">
                                <label for="current_password" class="block text-sm font-medium mb-2 text-muted">Password Saat Ini</label>
                                <div class="relative">
                                    <input type="password" id="current_password" name="current_password" 
                                        class="form-input pr-10" placeholder="Kosongkan jika tidak ingin diubah">
                                    <button type="button" onclick="togglePassword('current_password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-variant hover:text-white transition-colors bg-transparent border-none cursor-pointer">
                                        <svg class="w-5 h-5 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <svg class="w-5 h-5 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                    </button>
                                </div>
                                @error('current_password')
                                    <p class="text-xs mt-1.5 text-gold">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- New Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium mb-2 text-muted">Password Baru</label>
                                <div class="relative">
                                    <input type="password" id="password" name="password" 
                                        class="form-input pr-10" placeholder="Min. 8 karakter">
                                    <button type="button" onclick="togglePassword('password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-variant hover:text-white transition-colors bg-transparent border-none cursor-pointer">
                                        <svg class="w-5 h-5 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <svg class="w-5 h-5 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="text-xs mt-1.5 text-gold">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium mb-2 text-muted">Konfirmasi Password</label>
                                <div class="relative">
                                    <input type="password" id="password_confirmation" name="password_confirmation" 
                                        class="form-input pr-10" placeholder="Ulangi password baru">
                                    <button type="button" onclick="togglePassword('password_confirmation', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-variant hover:text-white transition-colors bg-transparent border-none cursor-pointer">
                                        <svg class="w-5 h-5 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <svg class="w-5 h-5 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 p-3.5 rounded-xl border-subtle" style="background: rgba(8,12,20,0.6);">
                            <p class="text-xs flex items-start gap-2.5 text-variant">
                                <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Kosongkan ketiga field password jika Anda tidak ingin mengubah password.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-2">
                    <a href="{{ url('/home') }}" class="btn-cancel w-full sm:w-auto px-8 py-3 rounded-xl font-semibold text-center text-sm no-underline h-[46px] flex items-center justify-center">
                        Batal
                    </a>
                    <button type="submit" class="btn-primary w-full sm:w-auto px-8 py-3 rounded-xl text-sm flex items-center justify-center gap-2 h-[46px]">
                        Simpan
                    </button>
                </div>

                <!-- Success Message -->
                @if (session('status'))
                    <div id="successMsg" class="mt-5 px-4 py-3 rounded-xl flex items-center gap-3" style="background: var(--color-success-bg); border: 1px solid var(--color-success-border); color: var(--color-success);">
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
                successMsg.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                successMsg.style.opacity = '0';
                successMsg.style.transform = 'translateY(-10px)';
                setTimeout(function() {
                    successMsg.remove();
                }, 500);
            }, 5000);
        }
    });
</script>
@endpush
@endsection
