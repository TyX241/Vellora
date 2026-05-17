@extends('layouts.app')

@push('styles')
<style>
.browse-header {
    margin-bottom: 28px;
    padding-bottom: 24px;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    flex-wrap: wrap;
    gap: 16px;
}
.browse-title {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 38px;
    letter-spacing: 2px;
    color: var(--text-primary);
    line-height: 1;
    margin-bottom: 4px;
}
.browse-sub { color: var(--text-muted); font-size: 14px; }
/* Filter bar */
.filter-bar {
    display: flex; flex-wrap: wrap; gap: 10px; align-items: flex-end;
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: var(--radius-lg); padding: 16px 20px; margin-bottom: 24px;
}
.filter-group { display: flex; flex-direction: column; gap: 5px; }
.filter-label { font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.8px; }
.filter-select {
    background: var(--bg-elevated); border: 1px solid var(--border);
    border-radius: 8px; color: var(--text-primary);
    font-family: 'DM Sans', sans-serif; font-size: 13px;
    padding: 8px 12px; outline: none; cursor: pointer;
    transition: border-color 0.2s; min-width: 140px;
}
.filter-select:focus { border-color: var(--border-gold); }
.filter-select option { background: var(--bg-card); }
.filter-active-tag {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--gold-dim); border: 1px solid var(--border-gold);
    border-radius: 50px; padding: 4px 12px;
    font-size: 12px; font-weight: 600; color: var(--gold);
    margin-bottom: 16px;
}
.grid-5 {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 20px;
}
@media (max-width: 1100px) { .grid-5 { grid-template-columns: repeat(4, 1fr); } }
@media (max-width: 820px)  { .grid-5 { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 560px)  { .grid-5 { grid-template-columns: repeat(2, 1fr); } }
.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 80px 0;
    color: var(--text-muted);
}
.empty-state-icon { font-size: 48px; margin-bottom: 12px; opacity: 0.5; }
</style>
@endpush

@section('content')
<div class="browse-header">
    <div>
        <h1 class="browse-title">{{ $title }}</h1>
        <p class="browse-sub">Menampilkan {{ $results->count() }} tayangan yang memenuhi kriteria.</p>
    </div>
    <a href="{{ route('home') }}" class="btn-ghost">← Kembali</a>
</div>

<!-- FILTER BAR -->
<form action="{{ route('browse', $type) }}" method="GET">
    <div class="filter-bar">
        <div class="filter-group">
            <label class="filter-label">Kategori</label>
            <select name="kategori" class="filter-select">
                <option value="">Semua Kategori</option>
                <option value="Film" {{ $filterKategori == 'Film' ? 'selected' : '' }}>Film</option>
                <option value="Series" {{ $filterKategori == 'Series' ? 'selected' : '' }}>Series</option>
            </select>
        </div>
        <div class="filter-group">
            <label class="filter-label">Genre</label>
            <select name="genre" class="filter-select">
                <option value="">Semua Genre</option>
                @foreach($genres as $genre)
                    <option value="{{ $genre->genre_id }}" {{ $filterGenre == $genre->genre_id ? 'selected' : '' }}>
                        {{ $genre->nama_genre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label class="filter-label">Animasi</label>
            <select name="animasi" class="filter-select">
                <option value="">Semua</option>
                <option value="1" {{ $filterAnimasi === '1' ? 'selected' : '' }}>Animasi / Anime</option>
                <option value="0" {{ $filterAnimasi === '0' ? 'selected' : '' }}>Non-Animasi</option>
            </select>
        </div>
        <div class="filter-group" style="justify-content:flex-end;">
            <button type="submit" class="btn-gold" style="font-size:13px;padding:8px 20px;">Terapkan Filter</button>
        </div>
        @if($filterKategori || $filterGenre || $filterAnimasi !== null)
        <div class="filter-group" style="justify-content:flex-end;">
            <a href="{{ route('browse', $type) }}" class="btn-ghost" style="font-size:13px;padding:8px 16px;">Reset</a>
        </div>
        @endif
    </div>
</form>

@if($filterKategori || $filterGenre || $filterAnimasi !== null)
<div style="margin-bottom:16px;display:flex;flex-wrap:wrap;gap:8px;">
    @if($filterKategori)
        <span class="filter-active-tag">Kategori: {{ $filterKategori }}</span>
    @endif
    @if($filterGenre && $genres->firstWhere('genre_id', $filterGenre))
        <span class="filter-active-tag">Genre: {{ $genres->firstWhere('genre_id', $filterGenre)->nama_genre }}</span>
    @endif
    @if($filterAnimasi === '1')
        <span class="filter-active-tag">Hanya Animasi</span>
    @elseif($filterAnimasi === '0')
        <span class="filter-active-tag">Non-Animasi</span>
    @endif
</div>
@endif

<div class="grid-5">
    @forelse($results as $media)
        <a href="/tayangan/{{ $media->media_id }}" class="media-card">
            <div class="poster-wrap">
                <img src="{{ $media->poster_url ?? 'https://via.placeholder.com/160x240/1A1A24/4A4860?text=No+Poster' }}" alt="{{ $media->judul }}" loading="lazy">
                <span class="poster-badge {{ $media->status_tayang == 'Ongoing' ? 'ongoing' : 'finished' }}">{{ $media->status_tayang }}</span>
            </div>
            <div class="media-card-title">{{ $media->judul }}</div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-top:5px;">
                @if($type === 'top-rated' && isset($media->reviews_avg_rating) && $media->reviews_avg_rating)
                    <div class="rating-pill">★ {{ number_format($media->reviews_avg_rating,1) }}</div>
                @else
                    <span style="font-size:12px;color:var(--text-muted);">{{ $media->format_tayangan }}</span>
                @endif
            </div>
        </a>
    @empty
        <div class="empty-state">
            <div class="empty-state-icon">🎬</div>
            <p>Tidak ada tayangan yang cocok dengan filter ini.</p>
        </div>
    @endforelse
</div>
@endsection
