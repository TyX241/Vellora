@extends('layouts.app')

@push('styles')
<style>
.search-header { margin-bottom: 28px; padding-bottom: 24px; border-bottom: 1px solid var(--border); }
.search-title { font-family: 'Bebas Neue', sans-serif; font-size: 36px; letter-spacing: 1px; color: var(--text-primary); margin-bottom: 4px; }
.search-sub { color: var(--text-muted); font-size: 14px; }
.search-keyword { color: var(--gold); }
/* Filter bar */
.filter-bar {
    display: flex; flex-wrap: wrap; gap: 10px; align-items: flex-end;
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: var(--radius-lg); padding: 18px 20px; margin-bottom: 28px;
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
.filter-search-input {
    flex: 1; min-width: 200px;
    background: var(--bg-elevated); border: 1px solid var(--border);
    border-radius: 8px; color: var(--text-primary);
    font-family: 'DM Sans', sans-serif; font-size: 13px;
    padding: 8px 12px; outline: none;
    transition: border-color 0.2s;
}
.filter-search-input::placeholder { color: var(--text-muted); }
.filter-search-input:focus { border-color: var(--border-gold); box-shadow: 0 0 0 3px var(--gold-dim); }
.filter-active-tag {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--gold-dim); border: 1px solid var(--border-gold);
    border-radius: 50px; padding: 4px 12px;
    font-size: 12px; font-weight: 600; color: var(--gold);
    margin-bottom: 16px;
}
.grid-5 { display: grid; grid-template-columns: repeat(5,1fr); gap: 20px; }
@media (max-width: 1100px) { .grid-5 { grid-template-columns: repeat(4,1fr); } }
@media (max-width: 820px)  { .grid-5 { grid-template-columns: repeat(3,1fr); } }
@media (max-width: 560px)  { .grid-5 { grid-template-columns: repeat(2,1fr); } }
.empty-state { grid-column: 1/-1; text-align: center; padding: 80px 0; color: var(--text-muted); }
.empty-state-icon { font-size: 48px; margin-bottom: 12px; opacity: 0.4; }
</style>
@endpush

@section('content')
<div class="search-header">
    @if($query || $filterKategori || $filterGenre)
        <h1 class="search-title">
            @if($query)Hasil: <span class="search-keyword">"{{ $query }}"</span>
            @else Eksplorasi Tayangan @endif
        </h1>
        <p class="search-sub">Ditemukan {{ $results->count() }} tayangan.</p>
    @else
        <h1 class="search-title">Eksplorasi Tayangan</h1>
        <p class="search-sub">Gunakan filter di bawah untuk menemukan tayangan sesuai selera.</p>
    @endif
</div>

<!-- FILTER BAR -->
<form action="{{ route('search') }}" method="GET">
    <div class="filter-bar">
        <div class="filter-group" style="flex:1;min-width:200px;">
            <label class="filter-label">Judul</label>
            <input type="text" name="q" class="filter-search-input" placeholder="Cari judul..." value="{{ $query }}">
        </div>
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
            <label class="filter-label">Status</label>
            <select name="status" class="filter-select">
                <option value="">Semua Status</option>
                <option value="Ongoing" {{ $filterStatus == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                <option value="Completed" {{ $filterStatus == 'Completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>
        <div class="filter-group" style="justify-content:flex-end;">
            <button type="submit" class="btn-gold" style="font-size:13px;padding:8px 20px;">Terapkan Filter</button>
        </div>
        @if($query || $filterKategori || $filterGenre || $filterStatus)
        <div class="filter-group" style="justify-content:flex-end;">
            <a href="{{ route('search') }}" class="btn-ghost" style="font-size:13px;padding:8px 16px;">Reset</a>
        </div>
        @endif
    </div>
</form>

@if($filterKategori || $filterGenre || $filterStatus)
<div style="margin-bottom:16px;display:flex;flex-wrap:wrap;gap:8px;">
    @if($filterKategori)
        <span class="filter-active-tag">Kategori: {{ $filterKategori }}</span>
    @endif
    @if($filterGenre && $genres->firstWhere('genre_id', $filterGenre))
        <span class="filter-active-tag">Genre: {{ $genres->firstWhere('genre_id', $filterGenre)->nama_genre }}</span>
    @endif
    @if($filterStatus)
        <span class="filter-active-tag">Status: {{ $filterStatus }}</span>
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
            <div class="media-card-meta">{{ $media->format_tayangan }}</div>
        </a>
    @empty
        <div class="empty-state">
            <div class="empty-state-icon">🔍</div>
            @if($query || $filterKategori || $filterGenre || $filterStatus)
                <p style="font-size:16px;color:var(--text-secondary);font-weight:500;margin-bottom:6px;">Tidak ada hasil</p>
                <p style="font-size:14px;">Coba ubah kata kunci atau filter pencarian.</p>
            @else
                <p style="font-size:14px;">Ketikkan judul atau gunakan filter di atas.</p>
            @endif
        </div>
    @endforelse
</div>
@endsection
