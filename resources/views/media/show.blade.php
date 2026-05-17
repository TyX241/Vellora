@extends('layouts.app')

@push('styles')
<style>
.media-hero {
    display: grid;
    grid-template-columns: 260px 1fr;
    gap: 40px;
    margin-bottom: 48px;
}
@media (max-width: 768px) { .media-hero { grid-template-columns: 1fr; } }
.media-poster-col {}
.media-poster-img {
    width: 100%;
    border-radius: 14px;
    box-shadow: 0 24px 60px rgba(0,0,0,0.6);
    display: block;
    aspect-ratio: 2/3;
    object-fit: cover;
    background: var(--bg-elevated);
}
.media-info-col { padding-top: 8px; }
.media-format-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--gold-dim); border: 1px solid var(--border-gold);
    border-radius: 4px; padding: 3px 10px;
    font-size: 12px; font-weight: 600; color: var(--gold);
    text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 12px;
}
.media-title-main {
    font-family: 'Bebas Neue', sans-serif;
    font-size: clamp(30px, 4vw, 52px);
    letter-spacing: 1px;
    color: var(--text-primary);
    line-height: 1.05;
    margin-bottom: 14px;
}
.media-rating-row {
    display: flex; align-items: baseline; gap: 8px; margin-bottom: 20px;
}
.media-score {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 42px; color: var(--gold); letter-spacing: 1px; line-height: 1;
}
.media-score-denom { font-size: 18px; color: var(--text-muted); }
.media-review-count { font-size: 13px; color: var(--text-muted); }
.media-meta-row {
    display: flex; flex-wrap: wrap; gap: 16px;
    margin-bottom: 20px; padding-bottom: 20px;
    border-bottom: 1px solid var(--border);
}
.meta-item { display: flex; align-items: center; gap: 6px; font-size: 14px; color: var(--text-secondary); }
.meta-item svg { color: var(--text-muted); flex-shrink: 0; }
.genres-row { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 24px; }
.genre-chip {
    padding: 5px 12px; border-radius: 6px;
    background: var(--bg-elevated); border: 1px solid var(--border);
    font-size: 13px; color: var(--text-secondary); font-weight: 500;
}
.synopsis-label { font-size: 13px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
.synopsis-text { color: var(--text-secondary); line-height: 1.75; font-size: 15px; font-weight: 300; }
.action-bar {
    display: flex; flex-wrap: wrap; gap: 10px; align-items: center;
    margin-top: 28px; padding-top: 24px; border-top: 1px solid var(--border);
}
.guest-prompt {
    background: var(--bg-elevated); border: 1px solid var(--border);
    border-radius: var(--radius); padding: 14px 18px;
    font-size: 14px; color: var(--text-secondary);
}
.guest-prompt a { color: var(--gold); text-decoration: none; font-weight: 600; }
.select-v {
    background: var(--bg-elevated); border: 1px solid var(--border);
    border-radius: 8px; color: var(--text-primary);
    font-family: 'DM Sans', sans-serif; font-size: 14px;
    padding: 9px 14px; outline: none; cursor: pointer;
    transition: border-color 0.2s;
}
.select-v:focus { border-color: var(--border-gold); }
.select-v option { background: var(--bg-card); }
.btn-outline-action {
    background: transparent; border: 1px solid var(--border);
    border-radius: 8px; color: var(--text-secondary);
    font-family: 'DM Sans', sans-serif; font-size: 14px; font-weight: 500;
    padding: 9px 18px; cursor: pointer;
    transition: border-color 0.2s, color 0.2s, background 0.2s;
    display: inline-flex; align-items: center; gap: 6px;
}
.btn-outline-action:hover { border-color: var(--border-gold); color: var(--gold); background: var(--gold-dim); }
.btn-outline-danger-v {
    background: transparent; border: 1px solid rgba(224,82,82,0.3);
    border-radius: 8px; color: #E05252;
    font-family: 'DM Sans', sans-serif; font-size: 14px; font-weight: 500;
    padding: 9px 14px; cursor: pointer;
    transition: border-color 0.2s, background 0.2s;
}
.btn-outline-danger-v:hover { background: rgba(224,82,82,0.1); border-color: #E05252; }
/* Reviews */
.reviews-section { margin-top: 48px; }
.reviews-title {
    font-size: 20px; font-weight: 600; color: var(--text-primary);
    display: flex; align-items: center; gap: 10px; margin-bottom: 24px;
    padding-bottom: 16px; border-bottom: 1px solid var(--border);
}
.review-card {
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: var(--radius); padding: 18px 20px; margin-bottom: 12px;
    transition: border-color 0.2s;
}
.review-card:hover { border-color: rgba(255,255,255,0.12); }
.review-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
.review-author { font-weight: 600; color: var(--gold); font-size: 14px; }
.review-score { background: var(--gold-dim); border: 1px solid var(--border-gold); border-radius: 4px; padding: 2px 10px; font-size: 13px; font-weight: 700; color: var(--gold); }
.review-body { color: var(--text-secondary); font-size: 14px; line-height: 1.65; font-weight: 300; }
.review-time { font-size: 12px; color: var(--text-muted); margin-top: 8px; }
</style>
@endpush

@section('content')
@if(session('success'))
    <div class="v-alert v-alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="v-alert v-alert-danger">{{ session('error') }}</div>
@endif

<div class="media-hero">
    <!-- POSTER -->
    <div class="media-poster-col">
        <img src="{{ $media->poster_url ?? 'https://via.placeholder.com/300x450/1A1A24/4A4860?text=No+Poster' }}"
             alt="{{ $media->judul }}" class="media-poster-img">
    </div>

    <!-- INFO -->
    <div class="media-info-col">
        <div class="media-format-badge">{{ $media->format_tayangan }}</div>
        <h1 class="media-title-main">{{ $media->judul }}</h1>

        <div class="media-rating-row">
            <span class="media-score">{{ $averageRating ? number_format($averageRating, 1) : 'N/A' }}</span>
            <span class="media-score-denom">/ 10</span>
            <span class="media-review-count">({{ $media->reviews->count() }} ulasan)</span>
        </div>

        <div class="media-meta-row">
            @if($media->tanggal_rilis)
            <div class="meta-item">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                {{ \Carbon\Carbon::parse($media->tanggal_rilis)->format('Y') }}
            </div>
            @endif
            <div class="meta-item">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M23 7l-7 5 7 5V7z"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
                {{ $media->total_episode ? $media->total_episode . ' Episode' : '1 Episode' }}
            </div>
            @if($media->durasi_per_episode)
            <div class="meta-item">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                {{ $media->durasi_per_episode }} menit/ep
            </div>
            @endif
            @if($media->negara_asal)
            <div class="meta-item">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                {{ $media->negara_asal }}
            </div>
            @endif
            <div class="meta-item">
                <span class="v-badge {{ $media->status_tayang == 'Ongoing' ? 'v-badge-ongoing' : 'v-badge-finished' }}">{{ $media->status_tayang }}</span>
            </div>
            @if($media->is_animation)
            <div class="meta-item">
                <span class="v-badge v-badge-gold">Animation</span>
            </div>
            @endif
        </div>

        <div class="genres-row">
            @forelse($media->genres as $genre)
                <span class="genre-chip">{{ $genre->nama_genre }}</span>
            @empty
                <span class="genre-chip" style="color:var(--text-muted);">Belum ada genre</span>
            @endforelse
        </div>

        <p class="synopsis-label">Sinopsis</p>
        <p class="synopsis-text">{{ $media->deskripsi ?? 'Sinopsis belum tersedia untuk tayangan ini.' }}</p>

        <!-- ACTIONS -->
        <div class="action-bar">
            @auth
                <form action="{{ route('watchlist.store') }}" method="POST" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin:0;">
                    @csrf
                    <input type="hidden" name="media_id" value="{{ $media->media_id }}">
                    <select name="status" class="select-v">
                        <option value="Plan to Watch" {{ ($userWatchlist && $userWatchlist->status == 'Plan to Watch') ? 'selected' : '' }}>Plan to Watch</option>
                        <option value="Watching" {{ ($userWatchlist && $userWatchlist->status == 'Watching') ? 'selected' : '' }}>Watching</option>
                        <option value="Completed" {{ ($userWatchlist && $userWatchlist->status == 'Completed') ? 'selected' : '' }}>Completed</option>
                        <option value="Dropped" {{ ($userWatchlist && $userWatchlist->status == 'Dropped') ? 'selected' : '' }}>Dropped</option>
                    </select>
                    <button type="submit" class="btn-gold" style="font-size:14px;padding:9px 20px;">
                        {{ $userWatchlist ? 'Update Watchlist' : '+ Watchlist' }}
                    </button>
                </form>

                <button type="button" class="btn-outline-action" data-bs-toggle="modal" data-bs-target="#reviewModal">
                    ★ {{ $userReview ? 'Edit Ulasan' : 'Beri Ulasan' }}
                </button>

                @if($userReview)
                    <form action="{{ route('review.destroy', $userReview->review_id) }}" method="POST" style="margin:0;" onsubmit="return confirm('Hapus ulasan ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-outline-danger-v" title="Hapus Ulasan">🗑</button>
                    </form>
                @endif

                <button type="button" class="btn-outline-action" data-bs-toggle="modal" data-bs-target="#playlistModal">
                    + Playlist
                </button>
            @else
                <div class="guest-prompt">
                    <a href="{{ route('login') }}">Login</a> untuk menambah ke watchlist, memberi ulasan, atau membuat playlist.
                </div>
            @endauth
        </div>
    </div>
</div>
{{-- CAST & CHARACTERS --}}
@if($media->characters->isNotEmpty())
<div style="margin-top: 48px;">
    <h3 style="font-size:18px;font-weight:600;color:var(--text-primary);display:flex;align-items:center;gap:10px;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid var(--border);">
        <span style="display:flex;align-items:center;gap:10px;">
            <span style="width:4px;height:18px;background:var(--gold);border-radius:2px;display:inline-block;flex-shrink:0;"></span>
            Karakter &amp; Pemeran
        </span>
    </h3>

    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:2px;">
        @foreach($media->characters->take(10) as $char)
        <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;background:var(--bg-card);border:1px solid var(--border);margin:1px;transition:background 0.15s;"
             onmouseover="this.style.background='var(--bg-elevated)'"
             onmouseout="this.style.background='var(--bg-card)'">
            {{-- Karakter (kiri) --}}
            <div style="display:flex;align-items:center;gap:12px;min-width:0;">
                <div style="width:48px;height:48px;border-radius:50%;background:var(--bg-elevated);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:18px;color:var(--text-muted);">
                    👤
                </div>
                <div style="min-width:0;">
                    <div style="font-size:13px;font-weight:600;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ $char->nama_karakter }}
                    </div>
                    <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">
                        {{ $char->peran }}
                    </div>
                </div>
            </div>

            {{-- Aktor (kanan) --}}
            @if($char->actor)
            <div style="display:flex;align-items:center;gap:12px;min-width:0;text-align:right;">
                <div style="min-width:0;">
                    <div style="font-size:13px;font-weight:600;color:var(--gold);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ $char->actor->nama_aktor }}
                    </div>
                    <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">
                        Voice Actor
                    </div>
                </div>
                <div style="width:48px;height:48px;border-radius:50%;background:var(--bg-elevated);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:18px;color:var(--text-muted);">
                    {{ $media->is_animation ? '🎙' : '👤' }}
                </div>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    @if($media->characters->count() > 10)
    <div style="text-align:center;margin-top:12px;">
        <span style="font-size:13px;color:var(--text-muted);">
            +{{ $media->characters->count() - 10 }} karakter lainnya
        </span>
    </div>
    @endif
</div>
@endif

<!-- REVIEWS -->
<div class="reviews-section">
    <h3 class="reviews-title"><span class="section-title"><span class="dot"></span>Ulasan Pengguna</span></h3>
    @forelse($media->reviews->sortByDesc('waktu_dibuat') as $review)
        <div class="review-card">
            <div class="review-header">
                <span class="review-author">{{ $review->user->username }}</span>
                <span class="review-score">★ {{ $review->rating }} / 10</span>
            </div>
            <p class="review-body">{{ $review->komentar ?? 'Pengguna ini tidak meninggalkan komentar.' }}</p>
            <div class="review-time">{{ \Carbon\Carbon::parse($review->waktu_dibuat)->diffForHumans() }}</div>
        </div>
    @empty
        <div style="text-align:center;padding:48px 0;color:var(--text-muted);">
            <div style="font-size:36px;margin-bottom:10px;opacity:0.4;">★</div>
            <p>Belum ada ulasan. Jadilah yang pertama!</p>
        </div>
    @endforelse
</div>

@auth
{{-- MODALS diletakkan di luar konten utama agar tidak ada masalah z-index --}}

<!-- REVIEW MODAL -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $userReview ? 'Edit Ulasan' : 'Beri Ulasan' }}: {{ $media->judul }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('review.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="media_id" value="{{ $media->media_id }}">
                    <div class="v-form-group">
                        <label class="v-label">Rating (1 – 10)</label>
                        <input type="number" name="rating" class="v-input" min="1" max="10" step="0.1" required value="{{ $userReview ? $userReview->rating : '' }}" placeholder="Contoh: 8.5">
                    </div>
                    <div class="v-form-group">
                        <label class="v-label">Komentar (opsional)</label>
                        <textarea name="komentar" class="v-textarea" placeholder="Bagaimana pendapat Anda tentang tayangan ini?">{{ $userReview ? $userReview->komentar : '' }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-ghost" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-gold">Simpan Ulasan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- PLAYLIST MODAL -->
<div class="modal fade" id="playlistModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambahkan ke Playlist</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('playlists.addMedia') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="media_id" value="{{ $media->media_id }}">
                    @if($userPlaylists->isEmpty())
                        <div style="text-align:center;padding:24px 0;color:var(--text-muted);">
                            <div style="font-size:32px;margin-bottom:8px;opacity:0.4;">📁</div>
                            <p style="font-size:14px;">Anda belum memiliki playlist.</p>
                            <a href="{{ route('playlists.index') }}" style="color:var(--gold);font-size:13px;">Buat playlist sekarang →</a>
                        </div>
                    @else
                        <div class="v-form-group">
                            <label class="v-label">Pilih Playlist</label>
                            <select name="playlist_id" class="v-select" required>
                                <option value="" disabled selected>-- Pilih Playlist --</option>
                                @foreach($userPlaylists as $p)
                                    <option value="{{ $p->playlist_id }}">{{ $p->nama_playlist }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-ghost" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-gold" {{ $userPlaylists->isEmpty() ? 'disabled' : '' }}>Tambahkan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endauth

@endsection
