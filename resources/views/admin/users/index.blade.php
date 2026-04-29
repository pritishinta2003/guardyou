@extends('layouts.app')

@section('title', 'Kelola Pengguna — Admin')

@push('styles')
<style>
    .admin-container { padding: 7rem 1.5rem 4rem; max-width: 1200px; margin: 0 auto; }
    .page-header { margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem; }
    .page-header h1 { font-size: 1.8rem; font-weight: 800; letter-spacing: -0.02em; margin-bottom: 0.3rem; }

    .filter-bar {
        display: flex; gap: 0.75rem; margin-bottom: 1.5rem; flex-wrap: wrap;
    }
    .filter-bar input, .filter-bar select {
        background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px; padding: 0.6rem 1rem; color: var(--color-on-surface);
        font-size: 0.85rem; outline: none;
    }
    .filter-bar input:focus, .filter-bar select:focus { border-color: var(--color-gold); }

    .table-container { background: var(--color-surface-container); border-radius: 12px; border: 1px solid rgba(68,71,76,0.15); overflow: hidden; margin-bottom: 2rem; }
    table { width: 100%; border-collapse: collapse; }
    th { text-align: left; padding: 1rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; color: var(--color-on-surface-variant); border-bottom: 1px solid rgba(68,71,76,0.1); }
    td { padding: 1rem 1.5rem; border-bottom: 1px solid rgba(68,71,76,0.05); font-size: 0.875rem; vertical-align: middle; }

    .user-row { display: flex; align-items: center; gap: 0.8rem; }
    .user-ava { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #2f353e, #242a33); display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 700; border: 1px solid rgba(220,20,60,0.2); color: var(--color-gold); overflow: hidden; flex-shrink: 0; }
    .user-ava img { width: 100%; height: 100%; object-fit: cover; }

    .badge { padding: 0.3rem 0.8rem; border-radius: 2rem; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; }
    .badge-user { background: rgba(96,165,250,0.1); color: #60a5fa; }
    .badge-bodyguard { background: rgba(220,20,60,0.1); color: var(--color-gold); }
    .badge-admin { background: rgba(248,113,113,0.1); color: #f87171; }

    .action-btns { display: flex; gap: 0.5rem; align-items: center; }
    .btn-edit { padding: 0.4rem 0.9rem; font-size: 0.72rem; font-weight: 700; border-radius: 4px; background: rgba(220,20,60,0.1); border: 1px solid rgba(220,20,60,0.3); color: var(--color-gold); text-decoration: none; transition: all 0.2s; }
    .btn-edit:hover { background: rgba(220,20,60,0.2); }
    .btn-del { padding: 0.4rem 0.9rem; font-size: 0.72rem; font-weight: 700; border-radius: 4px; background: transparent; border: 1px solid rgba(248,113,113,0.3); color: #f87171; cursor: pointer; transition: all 0.2s; }
    .btn-del:hover { background: rgba(248,113,113,0.1); }
    .role-filter {
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    font-size: 0.8rem;
    }

    .role-filter option {
        color: #000 !important;
        background: #fff !important;
    }
    
</style>
@endpush

@section('content')
<div class="admin-container">
    <div class="page-header">
        <div>
            <a href="{{ route('admin.dashboard') }}" style="color:var(--color-gold); text-decoration:none; font-size:0.85rem;">&larr; Dashboard</a>
            <h1 style="margin-top:0.5rem;">Manajemen Pengguna</h1>
        </div>
        <p style="color:var(--color-on-surface-variant); font-size:0.85rem;">
            Total: {{ $users->total() }} pengguna
        </p>
    </div>

    @if(session('success'))
        <div style="padding:1rem; background:rgba(34,197,94,0.1); color:#4ade80; border:1px solid rgba(34,197,94,0.2); border-radius:8px; margin-bottom:1.5rem; font-size:0.9rem;">
            ✔ {{ session('success') }}
        </div>
    @endif

    <form method="GET" class="filter-bar">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / email...">
        <select name="role" class="role-filter">
            <option value="">Semua Role</option>
            <option value="user" @selected(request('role') === 'user')>Pengguna</option>
            <option value="bodyguard" @selected(request('role') === 'bodyguard')>Bodyguard</option>
            <option value="admin" @selected(request('role') === 'admin')>Admin</option>
        </select>
        <button type="submit" class="btn-primary" style="padding:0.6rem 1.25rem; font-size:0.8rem;">Terapkan</button>
        <a href="{{ route('admin.users.index') }}" class="btn-outline" style="padding:0.6rem 1.25rem; font-size:0.8rem;">Reset</a>
    </form>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Pengguna</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Peran</th>
                    <th>Bergabung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>
                        <div class="user-row">
                            <div class="user-ava">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" onerror="this.style.display='none'">
                                @endif
                                <span>{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                            <span style="font-weight:700;">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td style="color:var(--color-on-surface-variant);">{{ $user->email }}</td>
                    <td style="color:var(--color-on-surface-variant);">{{ $user->phone_number ?? '—' }}</td>
                    <td>
                        <span class="badge badge-{{ $user->role }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td style="color:var(--color-on-surface-variant); font-size:0.8rem;">
                        {{ $user->created_at->format('d M Y') }}
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn-edit">Edit</a>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                onsubmit="return confirm('Hapus pengguna {{ $user->name }}? Tindakan ini tidak dapat dibatalkan.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-del">Hapus</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $users->withQueryString()->links() }}
</div>
@endsection