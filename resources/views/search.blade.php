@extends('layouts.app')

@section('content')
<style>
    .media-card {
        text-decoration: none;
        color: inherit;
        display: block;
        transition: transform 0.2s ease;
    }
    .media-card:hover { transform: translateY(-5px); }
    .poster-wrapper {
        width: 100%;
        aspect-ratio: 2/3; 
        border-radius: 8px;
        overflow: hidden; 
        background-color: #1a1a1a;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 8px;
    }
    .media-poster { width: 100%; height: 100%; object-fit: contain; }
    .media-title {
        font-size: 0.95rem; font-weight: 600;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
        overflow: hidden; transition: color 0.3s ease; 
    }
    .media-card:hover .media-title { color: #ffc107; }
</style>

<div class="row mb-4">
    <div class="col-12 border-bottom border-secondary pb-3">
        @if($query)
            <h3 class="fw-bold text-light mb-1">Hasil Pencarian: <span class="text-warning">"{{ $query }}"</span></h3>
            <p class="text-secondary mb-0">Ditemukan {{ $results->count() }} tayangan.</p>
        @else
            <h3 class="fw-bold text-light mb-1">Eksplorasi Tayangan</h3>
            <p class="text-secondary mb-0">Ketikkan judul tayangan yang ingin Anda cari di atas.</p>
        @endif
    </div>
</div>

<div class="row row-cols-2 row-cols-md-4 row-cols-lg-5 g-4 mb-5">
    @forelse($results as $media)
        <div class="col">
            <a href="/tayangan/{{ $media->media_id }}" class="media-card">
                <div class="poster-wrapper">
                    <img src="{{ $media->poster_url ?? 'https://via.placeholder.com/160x240/333333/FFFFFF?text=No+Poster' }}" 
                         class="media-poster" alt="{{ $media->judul }}">
                </div>
                <div class="media-title">{{ $media->judul }}</div>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <small class="text-secondary">{{ $media->format_tayangan }}</small>
                    <span class="badge {{ $media->status_tayang == 'Ongoing' ? 'bg-success' : 'bg-secondary' }}" style="font-size: 0.65rem;">
                        {{ $media->status_tayang }}
                    </span>
                </div>
            </a>
        </div>
    @empty
        @if($query)
            <div class="col-12 text-center py-5 w-100">
                <div class="display-1 text-secondary mb-3">🔍</div>
                <h5 class="text-light fw-bold">Tayangan tidak ditemukan.</h5>
                <p class="text-secondary">Tidak ada hasil untuk "{{ $query }}". Coba gunakan kata kunci yang berbeda.</p>
            </div>
        @endif
    @endforelse
</div>
@endsection