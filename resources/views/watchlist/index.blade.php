@extends('layouts.app')

@push('styles')
<style>
.page-header {
    display: flex; justify-content: space-between; align-items: flex-end;
    flex-wrap: wrap; gap: 16px; margin-bottom: 28px;
    padding-bottom: 24px; border-bottom: 1px solid var(--border);
}
.page-title { font-family: 'Bebas Neue', sans-serif; font-size: 38px; letter-spacing: 2px; color: var(--text-primary); line-height: 1; margin-bottom: 4px; }
.page-sub { font-size: 14px; color: var(--text-muted); }
.wl-table-wrap {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
}
.wl-table { width: 100%; border-collapse: collapse; }
.wl-table th {
    padding: 14px 16px; font-size: 12px; font-weight: 600; color: var(--text-muted);
    text-transform: uppercase; letter-spacing: 0.8px; text-align: left;
    background: var(--bg-elevated); border-bottom: 1px solid var(--border);
}
.wl-table td { padding: 14px 16px; border-bottom: 1px solid var(--border); vertical-align: middle; }
.wl-table tr:last-child td { border-bottom: none; }
.wl-table tr:hover td { background: var(--bg-elevated); }
.wl-poster { width: 44px; height: 62px; object-fit: cover; border-radius: 6px; background: var(--bg-elevated); }
.wl-title a { font-weight: 500; color: var(--text-primary); text-decoration: none; font-size: 14px; transition: color 0.2s; }
.wl-title a:hover { color: var(--gold); }
.wl-format { font-size: 13px; color: var(--text-muted); }
.wl-time { font-size: 13px; color: var(--text-muted); }
.status-badge { font-size: 12px; font-weight: 600; padding: 3px 10px; border-radius: 4px; }
.status-completed { background: rgba(76,175,114,0.15); color: #4CAF72; }
.status-watching { background: rgba(201,168,76,0.15); color: var(--gold); }
.status-plan { background: var(--bg-elevated); color: var(--text-muted); border: 1px solid var(--border); }
.status-dropped { background: rgba(224,82,82,0.12); color: #E05252; }
.action-btns { display: flex; gap: 6px; align-items: center; }
.btn-sm-v {
    padding: 5px 12px; border-radius: 6px; font-size: 12px; font-weight: 500;
    cursor: pointer; font-family: 'DM Sans', sans-serif; border: 1px solid var(--border);
    background: transparent; color: var(--text-secondary); transition: all 0.15s;
}
.btn-sm-v:hover { border-color: var(--border-gold); color: var(--gold); background: var(--gold-dim); }
.btn-sm-danger { border-color: rgba(224,82,82,0.3); color: #E05252; }
.btn-sm-danger:hover { background: rgba(224,82,82,0.1); border-color: #E05252; color: #E05252; }
.empty-state { text-align: center; padding: 80px 0; color: var(--text-muted); }
.empty-state-icon { font-size: 48px; margin-bottom: 12px; opacity: 0.4; }
@media (max-width: 700px) {
    .col-hide { display: none; }
}
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">My Watchlist</h1>
        <p class="page-sub">Kelola daftar tontonan Anda.</p>
    </div>
</div>

@if(session('success'))
    <div class="v-alert v-alert-success">{{ session('success') }}</div>
@endif

<div class="wl-table-wrap">
    <table class="wl-table">
        <thead>
            <tr>
                <th style="width:60px;">Poster</th>
                <th>Judul</th>
                <th class="col-hide">Format</th>
                <th>Status</th>
                <th class="col-hide">Diubah</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($watchlists as $item)
                <tr>
                    <td>
                        <img src="{{ $item->media->poster_url ?? 'https://via.placeholder.com/44x62/1A1A24/4A4860?text=?' }}"
                             alt="Poster" class="wl-poster">
                    </td>
                    <td class="wl-title">
                        <a href="{{ route('media.show', $item->media_id) }}">{{ $item->media->judul }}</a>
                    </td>
                    <td class="wl-format col-hide">{{ $item->media->format_tayangan }}</td>
                    <td>
                        @php
                            $sc = match($item->status) {
                                'Watching' => 'status-watching',
                                'Completed' => 'status-completed',
                                'Dropped' => 'status-dropped',
                                default => 'status-plan'
                            };
                        @endphp
                        <span class="status-badge {{ $sc }}">{{ $item->status }}</span>
                    </td>
                    <td class="wl-time col-hide">
                        {{ \Carbon\Carbon::parse($item->waktu_diubah)->diffForHumans() }}
                    </td>
                    <td>
                        <div class="action-btns">
                            <button type="button" class="btn-sm-v"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editWL{{ $item->watchlist_id }}">Edit</button>
                            <form action="{{ route('watchlist.destroy', $item->watchlist_id) }}" method="POST" style="margin:0;" onsubmit="return confirm('Hapus dari watchlist?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-sm-v btn-sm-danger">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <div class="empty-state-icon">📺</div>
                            <p style="font-size:16px;color:var(--text-secondary);font-weight:500;margin-bottom:6px;">Watchlist masih kosong</p>
                            <p style="font-size:14px;">Tambahkan tayangan dari halaman beranda atau halaman detail.</p>
                            <a href="{{ route('home') }}" class="btn-gold" style="margin-top:16px;display:inline-flex;">Jelajahi Tayangan</a>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Edit Modals diletakkan di luar tabel agar tidak ada masalah z-index --}}
@foreach($watchlists as $item)
<div class="modal fade" id="editWL{{ $item->watchlist_id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Status: {{ $item->media->judul }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('watchlist.update', $item->watchlist_id) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="v-form-group">
                        <label class="v-label">Status Tontonan</label>
                        <select name="status" class="v-select">
                            <option value="Plan to Watch" {{ $item->status == 'Plan to Watch' ? 'selected' : '' }}>Plan to Watch</option>
                            <option value="Watching" {{ $item->status == 'Watching' ? 'selected' : '' }}>Watching</option>
                            <option value="Completed" {{ $item->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                            <option value="Dropped" {{ $item->status == 'Dropped' ? 'selected' : '' }}>Dropped</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-ghost" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-gold">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection
