@extends('layouts.app')

@section('title', 'Kelola Agen — Admin')

@push('styles')
<style>
    .admin-container { padding: 7rem 1.5rem 4rem; max-width: 1200px; margin: 0 auto; }
    .page-header { margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-end; }
    .page-header h1 { font-size: 1.8rem; font-weight: 800; letter-spacing: -0.02em; margin-bottom: 0.3rem; }

    .table-container { background: var(--color-surface-container); border-radius: 12px; border: 1px solid rgba(68,71,76,0.15); overflow: hidden; margin-bottom: 2rem; }
    table { width: 100%; border-collapse: collapse; }
    th { text-align: left; padding: 1rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; color: var(--color-on-surface-variant); border-bottom: 1px solid rgba(68,71,76,0.1); }
    td { padding: 1rem 1.5rem; border-bottom: 1px solid rgba(68,71,76,0.05); font-size: 0.875rem; vertical-align: middle; }

    .agent-row { display: flex; align-items: center; gap: 0.8rem; }
    .agent-ava { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #2f353e, #242a33); display: flex; align-items: center; justify-content: center; font-size: 0.8rem; border: 1px solid rgba(220,20,60,0.2); }

    .badge { padding: 0.3rem 0.8rem; border-radius: 2rem; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; }
    .badge-verified { background: rgba(34,197,94,0.1); color: #4ade80; }
    .badge-pending { background: rgba(220,20,60,0.1); color: var(--color-gold); }

    .btn-verify {
        padding: 0.5rem 1rem;
        font-size: 0.75rem;
        font-weight: 700;
        border-radius: 4px;
        border: none;
        cursor: pointer;
        text-transform: uppercase;
        transition: all 0.2s;
    }

    .btn-approve { background: #4ade80; color: #000; }
    .btn-revoke { background: transparent; border: 1px solid rgba(248,113,113,0.3); color: #f87171; }
    .btn-approve:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(74,222,128,0.25); }

    .pagination-wrapper { margin-top: 2rem; }
</style>
@endpush

@section('content')
<div class="admin-container">
    <div class="page-header">
        <div>
            <a href="{{ route('admin.dashboard') }}" style="color:var(--color-gold); text-decoration:none; font-size:0.85rem;">&larr; Dashboard</a>
            <h1 style="margin-top:0.5rem;">Verifikasi Guard</h1>
        </div>
        <p style="color:var(--color-on-surface-variant); text-align:right; font-size:0.85rem;">
            Total Guard: {{ $bodyguards->total() }}
        </p>
    </div>

    @if(session('success'))
        <div style="padding: 1rem; background: rgba(34,197,94,0.1); color: #4ade80; border: 1px solid rgba(34,197,94,0.2); border-radius: 8px; margin-bottom: 2rem; font-size: 0.9rem;">
            ✔ {{ session('success') }}
        </div>
    @endif

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Nama Agen</th>
                    <th>Tarif</th>
                    <th>Pengalaman</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bodyguards as $bg)
                <tr>
                    <td>
                        <div class="agent-row">
                            <div class="agent-ava">{{ substr($bg->user->name, 0, 1) }}</div>
                            <div>
                                <div style="font-weight:700;">{{ $bg->user->name }}</div>
                                <div style="font-size:0.75rem; color:var(--color-on-surface-variant);">
                                    ID: BG-{{ $bg->id }}
                                </div>
                            </div>
                        </div>
                    </td>

                    <td style="color:var(--color-gold); font-weight:600;">
                        Rp {{ number_format($bg->daily_rate, 0, ',', '.') }}
                    </td>

                    <td>{{ $bg->experience_years }} Tahun</td>

                    <td>
                        @if($bg->is_verified)
                            <span class="badge badge-verified">Terverifikasi</span>
                        @else
                            <span class="badge badge-pending">Menunggu</span>
                        @endif
                    </td>

                    <td>
                        <div style="display:flex; gap:0.5rem; align-items:center; flex-wrap:wrap;">
                            <a href="{{ route('admin.bodyguards.edit', $bg) }}"
                               style="padding:0.5rem 0.9rem; font-size:0.72rem; font-weight:700; border-radius:4px; background:rgba(220,20,60,0.1); border:1px solid rgba(220,20,60,0.3); color:var(--color-gold); text-decoration:none; transition:all 0.2s;">
                                Edit
                            </a>

                            <form action="{{ route('admin.bodyguards.verify', $bg) }}" method="POST" style="margin:0;">
                                @csrf
                                @method('PATCH')

                                @if($bg->is_verified)
                                    <button type="submit" class="btn-verify btn-revoke"
                                        onclick="return confirm('Cabut verifikasi untuk {{ $bg->user->name }}?')">
                                        Cabut Akses
                                    </button>
                                @else
                                    <button type="submit" class="btn-verify btn-approve">
                                        Verifikasi Agen
                                    </button>
                                @endif
                            </form>

                            @if(!$bg->is_verified)
                                <form action="{{ route('admin.bodyguards.destroy', $bg) }}" method="POST" style="margin:0;"
                                      onsubmit="return confirm('Yakin ingin menghapus {{ $bg->user->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        style="padding:0.5rem 0.9rem; font-size:0.72rem; font-weight:700; border-radius:4px; background:rgba(248,113,113,0.1); border:1px solid rgba(248,113,113,0.35); color:#f87171; cursor:pointer;">
                                        Hapus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper">
        {{ $bodyguards->links() }}
    </div>
</div>
@endsection