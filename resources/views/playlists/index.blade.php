@extends('layouts.app')

@section('content')
<div class="row mt-4">
    <div class="col-12 mb-4 d-flex justify-content-between align-items-end flex-wrap gap-3 border-bottom border-secondary pb-3">
        <div>
            <h2 class="fw-bold text-warning mb-1">My Playlists</h2>
            <p class="text-secondary mb-0">Kelola dan susun daftar koleksi tontonan kustom Anda.</p>
        </div>
        <button type="button" class="btn btn-warning fw-bold px-4 d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#createPlaylistModal" style="height: 42px;">
            + Buat Playlist Baru
        </button>
    </div>

    <div class="col-12 mb-3">
        @if(session('success'))
            <div class="alert alert-success bg-success text-white border-0 py-2 mb-0">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger bg-danger text-white border-0 py-2 mb-0">{{ session('error') }}</div>
        @endif
    </div>

    <div class="col-12">
        @if($playlists->isEmpty())
            <div class="d-flex flex-column justify-content-center align-items-center text-center w-100" style="min-height: 60vh;">
                <div class="display-1 text-secondary mb-3" style="opacity: 0.6;">📁</div>
                <h4 class="text-light fw-bold mb-2">Belum Ada Playlist</h4>
                <p class="text-secondary mb-4 mx-auto" style="max-width: 460px; font-size: 0.95rem; line-height: 1.6;">
                    Anda belum membuat satu pun playlist kustom. Mulai buat koleksi tematis pertama Anda sekarang!
                </p>
                <button type="button" class="btn btn-warning fw-bold px-4" data-bs-toggle="modal" data-bs-target="#createPlaylistModal" style="height: 42px;">
                    + Buat Playlist Baru
                </button>
            </div>
        @else
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
                @foreach($playlists as $playlist)
                    <div class="col">
                        <div class="card h-100 bg-dark border-secondary shadow-sm transition-hover">
                            <div class="card-body d-flex flex-column justify-content-between p-4">
                                <div>
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title fw-bold text-light mb-0 text-truncate" style="max-width: 75%;" title="{{ $playlist->nama_playlist }}">
                                            📁 {{ $playlist->nama_playlist }}
                                        </h5>
                                        <span class="badge bg-secondary text-light small">{{ $playlist->items_count }} Items</span>
                                    </div>
                                    <p class="card-text text-secondary small" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.6;">
                                        {{ $playlist->deskripsi ?? 'Tidak ada deskripsi untuk koleksi ini.' }}
                                    </p>
                                </div>
                                <div class="mt-4 pt-3 border-top border-secondary d-flex justify-content-between align-items-center">
                                    <a href="{{ route('playlists.show', $playlist->playlist_id) }}" class="btn btn-sm btn-outline-warning fw-bold px-3 py-2">
                                        Lihat Koleksi
                                    </a>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-info fw-bold px-3 py-2" data-bs-toggle="modal" data-bs-target="#editPlaylistModal{{ $playlist->playlist_id }}">
                                            Edit
                                        </button>
                                        <form action="{{ route('playlists.destroy', $playlist->playlist_id) }}" method="POST" class="m-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus seluruh playlist ini beserta semua isinya?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger fw-bold px-3 py-2">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade text-start" id="editPlaylistModal{{ $playlist->playlist_id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content bg-dark border-secondary text-light">
                                <div class="modal-header border-secondary">
                                    <h6 class="modal-title fw-bold text-warning">Edit Playlist: {{ $playlist->nama_playlist }}</h6>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('playlists.update', $playlist->playlist_id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label small text-secondary">Nama Playlist</label>
                                            <input type="text" name="nama_playlist" class="form-control bg-secondary text-light border-0" value="{{ $playlist->nama_playlist }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small text-secondary">Deskripsi (Opsional)</label>
                                            <textarea name="deskripsi" rows="4" class="form-control bg-secondary text-light border-0" style="resize: none;">{{ $playlist->deskripsi }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-secondary">
                                        <button type="button" class="btn btn-sm btn-outline-light" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-sm btn-info fw-bold text-dark px-4">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<div class="modal fade" id="createPlaylistModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border-secondary text-light">
            <div class="modal-header border-secondary">
                <h6 class="modal-title fw-bold text-warning">Buat Playlist Baru</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('playlists.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small text-secondary">Nama Playlist</label>
                        <input type="text" name="nama_playlist" class="form-control bg-secondary text-light border-0" placeholder="Contoh: Maraton Akhir Pekan, Anime Terbaik..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-secondary">Deskripsi (Opsional)</label>
                        <textarea name="deskripsi" rows="4" class="form-control bg-secondary text-light border-0" style="resize: none;" placeholder="Tulis catatan atau tujuan dari kurasi daftar ini..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-sm btn-outline-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-warning fw-bold px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .transition-hover {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .transition-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.4) !important;
        border-color: #ffc107 !important;
    }
</style>
@endsection