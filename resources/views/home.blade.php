@extends('layouts.app')

@section('content')

<style>
    /* CSS untuk Horizontal Scroll bergaya Netflix */
    .netflix-row {
        display: flex;
        overflow-x: auto;
        gap: 15px;
        padding-bottom: 15px;
        scroll-behavior: smooth;
    }
    
    .netflix-row::-webkit-scrollbar {
        display: none;
    }
    .netflix-row {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .media-card {
        flex: 0 0 auto;
        width: 160px;
        text-decoration: none;
        color: inherit;
        position: relative;
    }

    .poster-wrapper {
        width: 100%;
        height: 240px;
        border-radius: 8px;
        overflow: hidden; 
        background-color: #1a1a1a; /* Tambahkan background gelap agar jika gambar kurang tinggi, tidak terlihat bolong */
        display: flex; /* Membantu menengahkan gambar */
        align-items: center; 
        justify-content: center;
        margin-bottom: 8px;
    }

    .media-poster {
        width: 100%;
        height: 100%;
        object-fit: contain; /* UBAH dari cover menjadi contain */
    }

    /* .media-card:hover .poster-wrapper {
        box-shadow: 0 0 20px rgba(255, 193, 7, 0.7); 
    } */

    .media-title {
        font-size: 0.9rem;
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

    /* CSS untuk Hero Section / Jumbotron */
    .hero-section {
        position: relative;
        height: 400px; /* Tinggi banner */
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 40px;
        display: flex;
        align-items: center;
        background-image: url('https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?q=80&w=1000&auto=format&fit=crop'); /* Gambar bioskop/sinema gelap */
        background-size: cover;
        background-position: center;
    }

    /* Overlay gelap agar teks terbaca */
    .hero-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(to right, rgba(10,10,10,0.9) 0%, rgba(10,10,10,0.4) 100%);
        z-index: 1;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        padding: 40px;
        max-width: 600px;
    }
</style>

<div class="hero-section mt-2 shadow-lg">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1 class="display-5 fw-bold text-light">Jelajahi Dunia Sinema Tanpa Batas</h1>
        <p class="lead text-light mt-3">Lacak, catat, dan ulas semua film, series, hingga anime favoritmu dalam satu tempat.</p>
        
        @guest
            <a href="{{ route('register') }}" class="btn btn-warning btn-lg px-4 fw-bold mt-2">Daftar Sekarang</a>
        @endguest
        @auth
            <a href="#explore" class="btn btn-warning btn-lg px-4 fw-bold mt-2">Mulai Eksplorasi</a>
        @endauth
    </div>
</div>

<div id="explore" class="mt-4">
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">🔥 Sedang Hangat (Hot)</h5>
        <a href="#" class="text-warning text-decoration-none small fw-bold">Browse all ></a>
    </div>
    <div class="netflix-row mb-4">
        @forelse($hotMedia as $media) <a href="/tayangan/{{ $media->media_id }}" class="media-card">
                <div class="poster-wrapper">
                    <img src="{{ $media->poster_url ?? 'https://via.placeholder.com/160x240/333333/FFFFFF?text=No+Poster' }}" class="media-poster" alt="{{ $media->judul }}">
                </div>
                <div class="media-title">{{ $media->judul }}</div>
            </a>
        @empty
            <p class="text-secondary">Belum ada data tayangan.</p>
        @endforelse
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3 mt-5">
        <h5 class="fw-bold mb-0">📺 Sedang Tayang (Ongoing)</h5>
        <a href="#" class="text-warning text-decoration-none small fw-bold">Browse all ></a>
    </div>
    <div class="netflix-row mb-4">
        @forelse($ongoingMedia as $media)
            <a href="/tayangan/{{ $media->media_id }}" class="media-card">
                <div class="poster-wrapper">
                    <img src="{{ $media->poster_url ?? 'https://via.placeholder.com/160x240/333333/FFFFFF?text=No+Poster' }}" class="media-poster" alt="{{ $media->judul }}">
                </div>
                <div class="media-title">{{ $media->judul }}</div>
            </a>
        @empty
            <p class="text-secondary">Belum ada data tayangan.</p>
        @endforelse
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3 mt-5">
        <h5 class="fw-bold mb-0">✅ Sudah Selesai (Completed)</h5>
        <a href="#" class="text-warning text-decoration-none small fw-bold">Browse all ></a>
    </div>
    <div class="netflix-row mb-4">
        @forelse($completedMedia as $media)
            <a href="/tayangan/{{ $media->media_id }}" class="media-card">
                <div class="poster-wrapper">
                    <img src="{{ $media->poster_url ?? 'https://via.placeholder.com/160x240/333333/FFFFFF?text=No+Poster' }}" class="media-poster" alt="{{ $media->judul }}">
                </div>  
                <div class="media-title">{{ $media->judul }}</div>
            </a>
        @empty
            <p class="text-secondary">Belum ada data tayangan.</p>
        @endforelse
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3 mt-5">
        <h5 class="fw-bold mb-0">⭐ Rekomendasi Spesial (Top Rated)</h5>
        <a href="#" class="text-warning text-decoration-none small fw-bold">Browse all ></a>
    </div>
    <div class="netflix-row mb-5">
        @forelse($topRatedMedia as $media)
            <a href="/tayangan/{{ $media->media_id }}" class="media-card">
                <div class="poster-wrapper">
                    <img src="{{ $media->poster_url ?? 'https://via.placeholder.com/160x240/333333/FFFFFF?text=No+Poster' }}" class="media-poster" alt="{{ $media->judul }}">
                </div>      
                <div class="media-title">{{ $media->judul }}</div>
            </a>
        @empty
            <p class="text-secondary">Belum ada data tayangan.</p>
        @endforelse
    </div>

</div>
@endsection