@extends('layouts.app')

@push('styles')
<style>
.hero {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    height: 420px;
    margin-bottom: 56px;
    display: flex;
    align-items: flex-end;
    background-image: url('https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?q=80&w=1400&auto=format&fit=crop');
    background-size: cover;
    background-position: center 40%;
}
.hero::before {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(11,11,15,0.92) 0%, rgba(11,11,15,0.4) 60%, rgba(11,11,15,0.2) 100%);
}
.hero-content {
    position: relative; z-index: 1; padding: 40px;
}
.hero-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--gold-dim); border: 1px solid var(--border-gold);
    border-radius: 50px; padding: 4px 14px;
    font-size: 12px; font-weight: 600; color: var(--gold);
    letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 16px;
}
.hero-title {
    font-family: 'Bebas Neue', sans-serif;
    font-size: clamp(36px, 5vw, 60px);
    letter-spacing: 2px;
    color: var(--text-primary);
    line-height: 1.05;
    margin-bottom: 12px;
    max-width: 580px;
}
.hero-subtitle {
    color: var(--text-secondary);
    font-size: 15px;
    line-height: 1.6;
    max-width: 440px;
    margin-bottom: 24px;
    font-weight: 300;
}
.hero-actions { display: flex; gap: 12px; flex-wrap: wrap; }
.hero-stat {
    text-align: right; position: absolute; right: 40px; bottom: 40px; z-index: 1;
    display: flex; gap: 24px;
}
.stat-item { text-align: center; }
.stat-num {
    font-family: 'Bebas Neue', sans-serif; font-size: 32px;
    color: var(--gold); letter-spacing: 1px; line-height: 1;
}
.stat-label { font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-top: 4px; }

.home-section { margin-bottom: 48px; }
</style>
@endpush

@section('content')

<!-- HERO -->
<div class="hero">
    <div class="hero-content">
        <div class="hero-eyebrow">
            <span>✦</span> Platform Katalog Sinema
        </div>
        <h1 class="hero-title">Jelajahi Dunia<br>Sinema Tanpa Batas</h1>
        <p class="hero-subtitle">Lacak, catat, dan ulas semua film, series, hingga anime favoritmu dalam satu platform yang elegan.</p>
        <div class="hero-actions">
            @guest
                <a href="{{ route('register') }}" class="btn-gold" style="font-size:14px;padding:10px 24px;">Mulai Sekarang</a>
                <a href="{{ route('browse','top-rated') }}" class="btn-ghost" style="font-size:14px;padding:10px 24px;">Jelajahi Koleksi</a>
            @endguest
            @auth
                <a href="{{ route('browse','hot') }}" class="btn-gold" style="font-size:14px;padding:10px 24px;">Mulai Eksplorasi</a>
                <a href="{{ route('watchlist.index') }}" class="btn-ghost" style="font-size:14px;padding:10px 24px;">Watchlist Saya</a>
            @endauth
        </div>
    </div>
    <div class="hero-stat d-none d-md-flex">
        <div class="stat-item">
            <div class="stat-num">Film</div>
            <div class="stat-label">& Series</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">Anime</div>
            <div class="stat-label">& Kartun</div>
        </div>
    </div>
</div>

<!-- SECTIONS -->
<div class="home-section">
    <div class="section-header">
        <div class="section-title"><span class="dot"></span>Sedang Hangat</div>
        <a href="{{ route('browse','hot') }}" class="section-link">Lihat Semua →</a>
    </div>
    <div class="media-row">
        @forelse($hotMedia as $media)
            <a href="/tayangan/{{ $media->media_id }}" class="media-card">
                <div class="poster-wrap">
                    <img src="{{ $media->poster_url ?? 'https://via.placeholder.com/160x240/1A1A24/4A4860?text=No+Poster' }}" alt="{{ $media->judul }}" loading="lazy">
                    <span class="poster-badge {{ $media->status_tayang == 'Ongoing' ? 'ongoing' : 'finished' }}">{{ $media->status_tayang }}</span>
                </div>
                <div class="media-card-title">{{ $media->judul }}</div>
                <div class="media-card-meta">{{ $media->format_tayangan }}</div>
            </a>
        @empty
            <p style="color:var(--text-muted);font-size:14px;">Belum ada data.</p>
        @endforelse
    </div>
</div>

<div class="home-section">
    <div class="section-header">
        <div class="section-title"><span class="dot"></span>Skor Tertinggi</div>
        <a href="{{ route('browse','top-rated') }}" class="section-link">Lihat Semua →</a>
    </div>
    <div class="media-row">
        @forelse($topRatedMedia as $media)
            <a href="/tayangan/{{ $media->media_id }}" class="media-card">
                <div class="poster-wrap">
                    <img src="{{ $media->poster_url ?? 'https://via.placeholder.com/160x240/1A1A24/4A4860?text=No+Poster' }}" alt="{{ $media->judul }}" loading="lazy">
                    <span class="poster-badge {{ $media->status_tayang == 'Ongoing' ? 'ongoing' : 'finished' }}">{{ $media->status_tayang }}</span>
                </div>
                <div class="media-card-title">{{ $media->judul }}</div>
                @if($media->reviews_avg_rating)
                    <div class="rating-pill">★ {{ number_format($media->reviews_avg_rating,1) }}</div>
                @else
                    <div class="media-card-meta">{{ $media->format_tayangan }}</div>
                @endif
            </a>
        @empty
            <p style="color:var(--text-muted);font-size:14px;">Belum ada data ulasan.</p>
        @endforelse
    </div>
</div>

<div class="home-section">
    <div class="section-header">
        <div class="section-title"><span class="dot"></span>Paling Populer</div>
        <a href="{{ route('browse','populer') }}" class="section-link">Lihat Semua →</a>
    </div>
    <div class="media-row">
        @forelse($populerMedia as $media)
            <a href="/tayangan/{{ $media->media_id }}" class="media-card">
                <div class="poster-wrap">
                    <img src="{{ $media->poster_url ?? 'https://via.placeholder.com/160x240/1A1A24/4A4860?text=No+Poster' }}" alt="{{ $media->judul }}" loading="lazy">
                    <span class="poster-badge {{ $media->status_tayang == 'Ongoing' ? 'ongoing' : 'finished' }}">{{ $media->status_tayang }}</span>
                </div>
                <div class="media-card-title">{{ $media->judul }}</div>
                <div class="media-card-meta">{{ $media->format_tayangan }}</div>
            </a>
        @empty
            <p style="color:var(--text-muted);font-size:14px;">Belum ada data popularitas.</p>
        @endforelse
    </div>
</div>

<div class="home-section">
    <div class="section-header">
        <div class="section-title"><span class="dot"></span>Sedang Tayang</div>
        <a href="{{ route('browse','ongoing') }}" class="section-link">Lihat Semua →</a>
    </div>
    <div class="media-row">
        @forelse($ongoingMedia as $media)
            <a href="/tayangan/{{ $media->media_id }}" class="media-card">
                <div class="poster-wrap">
                    <img src="{{ $media->poster_url ?? 'https://via.placeholder.com/160x240/1A1A24/4A4860?text=No+Poster' }}" alt="{{ $media->judul }}" loading="lazy">
                    <span class="poster-badge ongoing">Ongoing</span>
                </div>
                <div class="media-card-title">{{ $media->judul }}</div>
                <div class="media-card-meta">{{ $media->format_tayangan }}</div>
            </a>
        @empty
            <p style="color:var(--text-muted);font-size:14px;">Belum ada tayangan aktif.</p>
        @endforelse
    </div>
</div>

@endsection
