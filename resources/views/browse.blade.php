@extends('layouts.app')

@section('content')
<style>
    .media-card {
        text-decoration: none;
        color: inherit;
        display: block;
        transition: transform 0.2s ease;
    }
    .media-card:hover { 
        transform: translateY(-5px); 
    }
    .poster-wrapper {
        width: 100%;
        aspect-ratio: 2/3; 
        border-radius: 8px;
        overflow: hidden; 
        background-color: #1a1a1a;
        display: flex; 
        align-items: center; 
        justify-content: center;
        margin-bottom: 8px;
    }
    .media-poster { 
        width: 100%; 
        height: 100%; 
        object-fit: contain; 
    }
    .media-title {
        font-size: 0.95rem; 
        font-weight: 600;
        display: -webkit-box; 
        -webkit-line-clamp: 2; 
        -webkit-box-orient: vertical;
        overflow: hidden; 
        transition: color 0.3s ease; 
    }
    .media-card:hover .media-title { 
        color: #ffc107; 
    }
</style>

<div class="row mb-4">
    <div class="col-12 border-bottom border-secondary pb-3 d-flex justify-content-between align-items-end flex-wrap gap-2">
        <div>
            <h3 class="fw-bold text-light mb-1">{{ $title }}</h3>
            <p class="text-secondary mb-0">Menampilkan seluruh koleksi arsip yang memenuhi kriteria terkurasi.</p>
        </div>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm fw-bold px-3">
            < Kembali ke Beranda
        </a>
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
                
                <div class="d-flex justify-content-between align-items-center mt-2">
                    @if($type === 'top-rated' && isset($media->reviews_avg_rating))
                        <span class="text-warning small fw-bold">⭐ {{ number_format($media->reviews_avg_rating, 1) }}</span>
                    @else
                        <small class="text-secondary">{{ $media->format_tayangan }}</small>
                    @endif
                    
                    <span class="badge {{ $media->status_tayang == 'Ongoing' ? 'bg-success' : 'bg-secondary' }}" style="font-size: 0.65rem;">
                        {{ $media->status_tayang }}
                    </span>
                </div>
            </a>
        </div>
    @empty
        <div class="col-12 text-center py-5 w-100">
            <p class="text-secondary">Tidak ada arsip media yang ditemukan dalam kategori ini.</p>
        </div>
    @endforelse
</div>
@endsection