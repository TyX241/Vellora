@extends('layouts.app')

@section('content')
<div class="row mt-4">
    <div class="col-md-4 col-lg-3 mb-4">
        <div class="position-relative">
            <img src="{{ $media->poster_url ?? 'https://via.placeholder.com/300x450/333333/FFFFFF?text=No+Poster' }}" 
                 alt="{{ $media->judul }}" 
                 class="img-fluid rounded shadow-lg w-100" 
                 style="height: auto; max-height: 500px; object-fit: cover; object-position: top; background-color: #1a1a1a;">
            
            <span class="badge {{ $media->status_tayang == 'Ongoing' ? 'bg-success' : 'bg-secondary' }} position-absolute top-0 start-0 m-2 px-3 py-2 shadow">
                {{ $media->status_tayang }}
            </span>
        </div>
    </div>

    <div class="col-md-8 col-lg-9">
        <h1 class="fw-bold mb-1 text-light">{{ $media->judul }}</h1>
        
        <div class="d-flex align-items-center mb-3">
            <span class="fs-4 text-warning fw-bold me-2">⭐ {{ $averageRating ? number_format($averageRating, 1) : 'N/A' }}</span>
            <span class="text-secondary small">/ 10 ({{ $media->reviews->count() }} Ulasan)</span>
        </div>
        
        <div class="text-secondary mb-4 d-flex flex-wrap gap-3 align-items-center">
            @if($media->tanggal_rilis)
                <span>📅 {{ \Carbon\Carbon::parse($media->tanggal_rilis)->format('Y') }}</span>
            @endif
            <span>🎬 {{ $media->format_tayangan }}</span>
            <span>⏱️ {{ $media->total_episode ? $media->total_episode . ' Episode' : '1 Episode' }}</span>
            <span>🌍 {{ $media->negara_asal ?? 'Tidak diketahui' }}</span>
            @if($media->is_animation)
                <span class="badge bg-warning text-dark fw-bold px-2 py-1">Animation</span>
            @endif
        </div>

        <div class="mb-4">
            @forelse($media->genres as $genre)
                <span class="badge bg-dark border border-secondary text-light me-1 px-3 py-2 fs-6">
                    {{ $genre->nama_genre }}
                </span>
            @empty
                <span class="text-muted small">Belum ada genre</span>
            @endforelse
        </div>

        <h5 class="fw-bold text-light mb-2">Sinopsis</h5>
        <p class="text-secondary" style="line-height: 1.8; text-align: justify; font-size: 1.05rem;">
            {{ $media->deskripsi ?? 'Sinopsis belum tersedia untuk tayangan ini.' }}
        </p>

        <div class="mt-5 pt-4 border-top border-secondary d-flex flex-column gap-3">
            
            @if(session('success'))
                <div class="alert alert-success bg-success text-white border-0 py-2 w-100 mb-0">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger bg-danger text-white border-0 py-2 w-100 mb-0">{{ session('error') }}</div>
            @endif

            @auth
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    
                    <form action="{{ route('watchlist.store') }}" method="POST" class="d-flex gap-2 align-items-center m-0">
                        @csrf
                        <input type="hidden" name="media_id" value="{{ $media->media_id }}">
                        
                        <select name="status" class="form-select bg-dark text-light border-secondary" style="width: auto; height: 42px;">
                            <option value="Plan to Watch" {{ ($userWatchlist && $userWatchlist->status == 'Plan to Watch') ? 'selected' : '' }}>Plan to Watch</option>
                            <option value="Watching" {{ ($userWatchlist && $userWatchlist->status == 'Watching') ? 'selected' : '' }}>Watching</option>
                            <option value="Completed" {{ ($userWatchlist && $userWatchlist->status == 'Completed') ? 'selected' : '' }}>Completed</option>
                            <option value="Dropped" {{ ($userWatchlist && $userWatchlist->status == 'Dropped') ? 'selected' : '' }}>Dropped</option>
                        </select>
                        
                        <button type="submit" class="btn btn-warning fw-bold d-flex align-items-center" style="height: 42px;">
                            {{ $userWatchlist ? 'Update Watchlist' : '+ Tambah ke Watchlist' }}
                        </button>
                    </form>

                    <button type="button" class="btn btn-outline-light fw-bold d-flex align-items-center" style="height: 42px;" data-bs-toggle="modal" data-bs-target="#reviewModal">
                        {{ $userReview ? '⭐ Edit Ulasan Saya' : '⭐ Beri Ulasan' }}
                    </button>

                    @if($userReview)
                        <form action="{{ route('review.destroy', $userReview->review_id) }}" method="POST" class="m-0" onsubmit="return confirm('Yakin ingin menghapus ulasan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger fw-bold d-flex align-items-center" style="height: 42px;">
                                🗑️
                            </button>
                        </form>
                    @endif

                    <button type="button" class="btn btn-outline-warning fw-bold d-flex align-items-center" style="height: 42px;" data-bs-toggle="modal" data-bs-target="#playlistModal">
                        + Playlist
                    </button>
                </div>
            @else
                <div class="alert bg-dark border-secondary text-light w-100 mb-0">
                    <a href="{{ route('login') }}" class="text-warning fw-bold text-decoration-none">Login</a> untuk mengakses fitur daftar tontonan, koleksi kustom, atau memberikan ulasan Anda.
                </div>
            @endauth
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-12">
        <h4 class="fw-bold mb-4 border-bottom border-secondary pb-2 text-light">Ulasan Pengguna</h4>
        
        @forelse($media->reviews->sortByDesc('waktu_dibuat') as $review)
            <div class="card bg-dark border-secondary mb-3 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold text-warning mb-0">{{ $review->user->username }}</h6>
                        <span class="badge bg-secondary text-light">⭐ {{ $review->rating }} / 10</span>
                    </div>
                    <p class="text-light mb-1">{{ $review->komentar ?? 'Pengguna ini tidak meninggalkan komentar.' }}</p>
                    <small class="text-secondary">{{ \Carbon\Carbon::parse($review->waktu_dibuat)->diffForHumans() }}</small>
                </div>
            </div>
        @empty
            <div class="card bg-dark border-secondary mb-3">
                <div class="card-body text-center py-5">
                    <p class="text-secondary mb-0">Belum ada ulasan untuk tayangan ini. Jadilah yang pertama memberikan pendapatmu!</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

@auth
<div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border-secondary text-light">
            <div class="modal-header border-secondary">
                <h5 class="modal-title fw-bold text-warning">Beri Ulasan: {{ $media->judul }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('review.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="media_id" value="{{ $media->media_id }}">
                    
                    <div class="mb-3">
                        <label class="form-label small">Rating (1 - 10)</label>
                        <input type="number" name="rating" class="form-control bg-secondary text-light border-0" 
                               min="1" max="10" step="0.1" required 
                               value="{{ $userReview ? $userReview->rating : '' }}">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small">Komentar (Opsional)</label>
                        <textarea name="komentar" rows="4" class="form-control bg-secondary text-light border-0" style="resize: none;"
                                  placeholder="Bagaimana pendapat Anda tentang tayangan ini?">{{ $userReview ? $userReview->komentar : '' }}</textarea>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-sm btn-outline-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-warning fw-bold">Simpan Ulasan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="playlistModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border-secondary text-light">
            <div class="modal-header border-secondary">
                <h6 class="modal-title fw-bold text-warning">Tambahkan ke Koleksi Playlist</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('playlists.addMedia') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="media_id" value="{{ $media->media_id }}">
                    
                    @if($userPlaylists->isEmpty())
                        <div class="text-center py-4 align-middle">
                            <div class="display-6 text-secondary mb-2">📁</div>
                            <p class="small text-secondary mb-0">Anda belum memiliki playlist kustom.</p>
                        </div>
                    @else
                        <div class="mb-3">
                            <label class="form-label small text-secondary">Pilih salah satu koleksi playlist Anda:</label>
                            <select name="playlist_id" class="form-select bg-secondary text-light border-0" required>
                                <option value="" disabled selected>-- Pilih Playlist --</option>
                                @foreach($userPlaylists as $p)
                                    <option value="{{ $p->playlist_id }}">{{ $p->nama_playlist }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-sm btn-outline-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-warning fw-bold" {{ $userPlaylists->isEmpty() ? 'disabled' : '' }}>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endauth

@endsection