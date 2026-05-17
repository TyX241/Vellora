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
.playlists-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 20px; }
@media (max-width: 900px) { .playlists-grid { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 560px) { .playlists-grid { grid-template-columns: 1fr; } }
.playlist-card {
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: var(--radius-lg); padding: 24px;
    display: flex; flex-direction: column; justify-content: space-between;
    transition: border-color 0.2s, box-shadow 0.2s;
    min-height: 180px;
}
.playlist-card:hover { border-color: var(--border-gold); box-shadow: 0 8px 30px rgba(0,0,0,0.3); }
.playlist-card-top {}
.playlist-card-name-row { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; margin-bottom: 10px; }
.playlist-card-name { font-size: 16px; font-weight: 600; color: var(--text-primary); line-height: 1.3; }
.playlist-card-count { font-size: 12px; color: var(--text-muted); background: var(--bg-elevated); border: 1px solid var(--border); border-radius: 4px; padding: 2px 8px; flex-shrink: 0; }
.playlist-card-desc { font-size: 13px; color: var(--text-muted); line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.playlist-card-footer { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--border); }
.playlist-card-actions { display: flex; gap: 6px; }
.btn-sm-v { padding: 5px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; cursor: pointer; font-family: 'DM Sans', sans-serif; border: 1px solid var(--border); background: transparent; color: var(--text-secondary); transition: all 0.15s; }
.btn-sm-v:hover { border-color: var(--border-gold); color: var(--gold); background: var(--gold-dim); }
.btn-sm-danger { border-color: rgba(224,82,82,0.3); color: #E05252; }
.btn-sm-danger:hover { background: rgba(224,82,82,0.1); border-color: #E05252; color: #E05252; }
.empty-state { text-align: center; padding: 100px 0; color: var(--text-muted); }
.empty-state-icon { font-size: 52px; margin-bottom: 14px; opacity: 0.35; }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">My Playlists</h1>
        <p class="page-sub">Susun koleksi tontonan kustom Anda.</p>
    </div>
    <button type="button" class="btn-gold" data-bs-toggle="modal" data-bs-target="#createPlaylistModal">
        + Buat Playlist
    </button>
</div>

@if(session('success'))
    <div class="v-alert v-alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="v-alert v-alert-danger">{{ session('error') }}</div>
@endif

@if($playlists->isEmpty())
    <div class="empty-state">
        <div class="empty-state-icon">📁</div>
        <p style="font-size:16px;color:var(--text-secondary);font-weight:500;margin-bottom:6px;">Belum Ada Playlist</p>
        <p style="font-size:14px;margin-bottom:20px;">Buat koleksi tematis pertama Anda sekarang!</p>
        <button type="button" class="btn-gold" data-bs-toggle="modal" data-bs-target="#createPlaylistModal">+ Buat Playlist Baru</button>
    </div>
@else
    <div class="playlists-grid">
        @foreach($playlists as $playlist)
            <div class="playlist-card">
                <div class="playlist-card-top">
                    <div class="playlist-card-name-row">
                        <div class="playlist-card-name">{{ $playlist->nama_playlist }}</div>
                        <span class="playlist-card-count">{{ $playlist->items_count }} item</span>
                    </div>
                    <p class="playlist-card-desc">{{ $playlist->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                </div>
                <div class="playlist-card-footer">
                    <a href="{{ route('playlists.show', $playlist->playlist_id) }}" class="btn-gold" style="font-size:12px;padding:6px 14px;">Lihat Koleksi</a>
                    <div class="playlist-card-actions">
                        <button type="button" class="btn-sm-v"
                                data-bs-toggle="modal"
                                data-bs-target="#editPL{{ $playlist->playlist_id }}">Edit</button>
                        <form action="{{ route('playlists.destroy', $playlist->playlist_id) }}" method="POST" style="margin:0;" onsubmit="return confirm('Hapus playlist ini beserta semua isinya?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-sm-v btn-sm-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

{{-- Edit Modals diletakkan di luar grid agar tidak terjebak dalam stacking context --}}
@foreach($playlists as $playlist)
<div class="modal fade" id="editPL{{ $playlist->playlist_id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Playlist</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('playlists.update', $playlist->playlist_id) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="v-form-group">
                        <label class="v-label">Nama Playlist</label>
                        <input type="text" name="nama_playlist" class="v-input" value="{{ $playlist->nama_playlist }}" required>
                    </div>
                    <div class="v-form-group">
                        <label class="v-label">Deskripsi (opsional)</label>
                        <textarea name="deskripsi" class="v-textarea">{{ $playlist->deskripsi }}</textarea>
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

<!-- Create Modal -->
<div class="modal fade" id="createPlaylistModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Buat Playlist Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('playlists.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="v-form-group">
                        <label class="v-label">Nama Playlist</label>
                        <input type="text" name="nama_playlist" class="v-input" placeholder="Contoh: Maraton Akhir Pekan, Anime Terbaik..." required>
                    </div>
                    <div class="v-form-group">
                        <label class="v-label">Deskripsi (opsional)</label>
                        <textarea name="deskripsi" class="v-textarea" placeholder="Tulis tujuan atau catatan untuk koleksi ini..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-ghost" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-gold">Buat Playlist</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
