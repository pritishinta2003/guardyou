@extends('layouts.app')

@section('title', 'Pemesanan Platform — Admin')

@push('styles')
<style>
    .admin-container { padding: 7rem 1.5rem 4rem; max-width: 1200px; margin: 0 auto; }
    .page-header { margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-end; }
    .page-header h1 { font-size: 1.8rem; font-weight: 800; letter-spacing: -0.02em; margin-bottom: 0.3rem; }
    
    .table-container { background: var(--color-surface-container); border-radius: 12px; border: 1px solid rgba(68,71,76,0.15); overflow: hidden; margin-bottom: 2rem; }
    table { width: 100%; border-collapse: collapse; }
    th { text-align: left; padding: 1rem 1.5rem; font-size: 0.7rem; text-transform: uppercase; color: var(--color-on-surface-variant); border-bottom: 1px solid rgba(68,71,76,0.1); }
    td { padding: 1rem 1.5rem; border-bottom: 1px solid rgba(68,71,76,0.05); font-size: 0.875rem; vertical-align: middle; }
    
    .status-pill { padding: 0.35rem 0.9rem; border-radius: 2rem; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; }
    .status-pending { background: rgba(220,20,60,0.12); color: var(--color-gold); }
    .status-paid    { background: rgba(34,197,94,0.12); color: #4ade80; }
    .status-active  { background: rgba(59,130,246,0.12); color: #60a5fa; }
    .status-completed{ background: rgba(148,163,184,0.12); color: #94a3b8; }
    .status-cancelled{ background: rgba(239,68,68,0.12); color: #f87171; }

    .price-col { color: var(--color-gold); font-weight: 800; font-family: 'Outfit', sans-serif; font-size: 1rem; }
    .view-btn { padding: 0.4rem 0.8rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 4px; color: var(--color-on-surface); text-decoration: none; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; transition: all 0.2s; }
    .view-btn:hover { background: rgba(255,255,255,0.1); border-color: var(--color-gold); }

    .pagination-wrapper { margin-top: 2rem; }
</style>
@endpush

@section('content')
<div class="admin-container">
    <div class="page-header">
        <div>
            <a href="{{ route('admin.dashboard') }}" style="color:var(--color-gold); text-decoration:none; font-size:0.85rem;">&larr; Dashboard</a>
            <h1 style="margin-top:0.5rem;">Platform Pemesanan</h1>
        </div>
        <p style="color:var(--color-on-surface-variant); font-size:0.85rem; text-align:right;">
            Semua penugasan guard
        </p>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID Misi</th>
                    <th>Klien</th>
                    <th>Agen</th>
                    <th>Rentang Tanggal</th>
                    <th>Alamat</th> <!-- TAMBAHAN -->
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                <tr>
                    <td style="font-family:monospace; font-weight:700;">#GY-{{ $booking->id }}</td>

                    <td>
                        <div style="font-weight:700;">{{ $booking->user->name }}</div>
                        <div style="font-size:0.75rem; color:var(--color-on-surface-variant);">
                            {{ $booking->user->email }}
                        </div>
                    </td>

                    <td>
                        <div style="font-weight:700;">{{ $booking->bodyguard->user->name ?? 'Tidak tersedia' }}</div>
                        <div style="font-size:0.75rem; color:var(--color-gold);">
                            Agen #BG-{{ $booking->bodyguard_id }}
                        </div>
                    </td>

                    <td style="font-size:0.78rem;">
                        {{ \Carbon\Carbon::parse($booking->start_date)->format('d M') }} - 
                        {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}
                    </td>

                    <!-- ALAMAT -->
                    <td style="font-size:0.75rem; color:var(--color-on-surface-muted); max-width:220px; word-break:break-word;">
                        {{ $booking->alamat ? '📍 ' . \Illuminate\Support\Str::limit($booking->alamat, 60) : '-' }}
                    </td>

                    <td class="price-col">
                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                    </td>

                    <td>
                        <span class="status-pill status-{{ $booking->status }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>

                    <td>
                        <a href="{{ route('chat.show', $booking) }}" class="view-btn" style="border-color:rgba(220,20,60,0.3); margin-left:0.3rem;">Chat</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper">
        {{ $bookings->links() }}
    </div>
</div>
@endsection