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
.pl-table-wrap { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-lg); overflow: hidden; }
.pl-table { width: 100%; border-collapse: collapse; }
.pl-table th { padding: 14px 16px; font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.8px; text-align: left; background: var(--bg-elevated); border-bottom: 1px solid var(--border); }
.pl-table td { padding: 14px 16px; border-bottom: 1px solid var(--border); vertical-align: middle; }
.pl-table tr:last-child td { border-bottom: none; }
.pl-table tr:hover td { background: var(--bg-elevated); }
.pl-poster { width: 44px; height: 62px; object-fit: cover; border-radius: 6px; background: var(--bg-elevated); }
.pl-title a { font-weight: 500; color: var(--text-primary); text-decoration: none; font-size: 14px; transition: color 0.2s; }
.pl-title a:hover { color: var(--gold); }
.pl-format { font-size: 13px; color: var(--text-muted); }
.btn-sm-danger { padding: 5px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; font-family: 'DM Sans', sans-serif; border: 1px solid rgba(224,82,82,0.3); background: transparent; color: #E05252; transition: all 0.15s; }
.btn-sm-danger:hover { background: rgba(224,82,82,0.1); border-color: #E05252; }
.empty-state { text-align: center; padding: 64px 0; color: var(--text-muted); }
@media (max-width: 700px) { .col-hide { display: none; } }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $playlist->nama_playlist }}</h1>
        <p class="page-sub">{{ $playlist->deskripsi ?? 'Koleksi playlist tanpa deskripsi.' }}</p>
    </div>
    <a href="{{ route('playlists.index') }}" class="btn-ghost">← Daftar Playlist</a>
</div>

@if(session('success'))
    <div class="v-alert v-alert-success">{{ session('success') }}</div>
@endif

<div class="pl-table-wrap">
    <table class="pl-table">
        <thead>
            <tr>
                <th style="width:70px;">Poster</th>
                <th>Judul Tayangan</th>
                <th class="col-hide">Format</th>
                <th class="col-hide">Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($playlist->items as $item)
                <tr>
                    <td>
                        <img src="{{ $item->media->poster_url ?? 'https://via.placeholder.com/44x62/1A1A24/4A4860?text=?' }}"
                             alt="Poster" class="pl-poster">
                    </td>
                    <td class="pl-title">
                        <a href="/tayangan/{{ $item->media_id }}">{{ $item->media->judul }}</a>
                    </td>
                    <td class="pl-format col-hide">{{ $item->media->format_tayangan }}</td>
                    <td class="col-hide">
                        <span class="v-badge {{ $item->media->status_tayang == 'Ongoing' ? 'v-badge-ongoing' : 'v-badge-finished' }}">
                            {{ $item->media->status_tayang }}
                        </span>
                    </td>
                    <td>
                        <form action="{{ route('playlists.removeMedia', $item->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('Keluarkan tayangan ini dari playlist?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-sm-danger">Keluarkan</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <div style="font-size:36px;margin-bottom:10px;opacity:0.35;">📁</div>
                            <p style="font-size:15px;color:var(--text-secondary);font-weight:500;margin-bottom:6px;">Playlist ini kosong</p>
                            <p style="font-size:13px;">Buka halaman detail tayangan untuk menambahkan ke playlist ini.</p>
                            <a href="{{ route('home') }}" class="btn-gold" style="margin-top:14px;display:inline-flex;">Jelajahi Tayangan</a>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
