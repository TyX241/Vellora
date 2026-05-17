@extends('layouts.app')

@push('styles')
<style>
.profile-hero {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 32px 36px;
    display: flex;
    align-items: center;
    gap: 28px;
    margin-bottom: 32px;
}
.profile-avatar {
    width: 88px; height: 88px;
    background: var(--gold-dim);
    border: 2px solid var(--border-gold);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-family: 'Bebas Neue', sans-serif;
    font-size: 40px; color: var(--gold);
    flex-shrink: 0;
}
.profile-name { font-family: 'Bebas Neue', sans-serif; font-size: 36px; letter-spacing: 1px; color: var(--text-primary); line-height: 1; margin-bottom: 4px; }
.profile-join { font-size: 13px; color: var(--text-muted); }
.stats-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 16px; margin-bottom: 32px; }
@media (max-width: 600px) { .stats-grid { grid-template-columns: 1fr; } .profile-hero { flex-direction: column; text-align: center; } }
.stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 22px;
    text-align: center;
}
.stat-card-num { font-family: 'Bebas Neue', sans-serif; font-size: 36px; color: var(--gold); letter-spacing: 1px; line-height: 1; margin-bottom: 4px; }
.stat-card-label { font-size: 13px; color: var(--text-muted); }
.two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
@media (max-width: 768px) { .two-col { grid-template-columns: 1fr; } }
.section-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
}
.section-card-header {
    padding: 18px 22px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: 8px;
}
.section-card-title { font-size: 15px; font-weight: 600; color: var(--text-primary); display: flex; align-items: center; gap: 8px; }
.section-card-title .dot { width: 3px; height: 15px; background: var(--gold); border-radius: 2px; }
.section-card-body { padding: 0; }
.watch-item {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 22px; border-bottom: 1px solid var(--border);
    transition: background 0.15s;
}
.watch-item:last-child { border-bottom: none; }
.watch-item:hover { background: var(--bg-elevated); }
.watch-item-title { font-size: 14px; font-weight: 500; color: var(--text-primary); }
.watch-item-format { font-size: 12px; color: var(--text-muted); margin-top: 2px; }
.status-badge { font-size: 12px; font-weight: 600; padding: 3px 10px; border-radius: 4px; flex-shrink: 0; }
.status-completed { background: rgba(76,175,114,0.15); color: #4CAF72; }
.status-watching { background: rgba(201,168,76,0.15); color: var(--gold); }
.status-plan { background: var(--bg-elevated); color: var(--text-muted); }
.status-dropped { background: rgba(224,82,82,0.12); color: #E05252; }
.playlist-item {
    padding: 14px 22px; border-bottom: 1px solid var(--border);
    transition: background 0.15s;
}
.playlist-item:last-child { border-bottom: none; }
.playlist-item:hover { background: var(--bg-elevated); }
.playlist-item-name { font-size: 14px; font-weight: 600; color: var(--text-primary); margin-bottom: 3px; display: flex; align-items: center; justify-content: space-between; }
.playlist-item-desc { font-size: 13px; color: var(--text-muted); }
.empty-state-sm { padding: 32px 22px; text-align: center; color: var(--text-muted); font-size: 14px; }
.section-card-footer { padding: 14px 22px; border-top: 1px solid var(--border); text-align: center; }
.section-card-footer a { font-size: 13px; color: var(--gold); text-decoration: none; font-weight: 500; }
.section-card-footer a:hover { text-decoration: underline; }
</style>
@endpush

@section('content')

<!-- PROFILE HERO -->
<div class="profile-hero">
    <div class="profile-avatar">{{ strtoupper(substr($user->username, 0, 1)) }}</div>
    <div>
        <h1 class="profile-name">{{ $user->username }}</h1>
        <p class="profile-join">Bergabung sejak {{ $user->created_at->format('F Y') }}</p>
    </div>
</div>

<!-- STATS -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-num">{{ $totalReviews }}</div>
        <div class="stat-card-label">Total Ulasan</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-num" style="font-size:22px;padding-top:7px;">{{ $watchTime }}</div>
        <div class="stat-card-label">Waktu Menonton</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-num" style="font-size:22px;padding-top:7px;">{{ $favoriteGenre }}</div>
        <div class="stat-card-label">Genre Favorit</div>
    </div>
</div>

<!-- TWO COL -->
<div class="two-col">
    <!-- WATCHLIST -->
    <div class="section-card">
        <div class="section-card-header">
            <div class="section-card-title"><span class="dot"></span>Aktivitas Tontonan</div>
        </div>
        <div class="section-card-body">
            @forelse($watchlists as $watch)
                <div class="watch-item">
                    <div>
                        <div class="watch-item-title">{{ $watch->judul }}</div>
                        <div class="watch-item-format">{{ $watch->format_tayangan }}</div>
                    </div>
                    @if($watch->status == 'Completed')
                        <span class="status-badge status-completed">Selesai</span>
                    @elseif($watch->status == 'Watching')
                        <span class="status-badge status-watching">Menonton</span>
                    @elseif($watch->status == 'Plan to Watch')
                        <span class="status-badge status-plan">Rencana</span>
                    @else
                        <span class="status-badge status-dropped">Dropped</span>
                    @endif
                </div>
            @empty
                <div class="empty-state-sm">Belum ada aktivitas tontonan.</div>
            @endforelse
        </div>
        <div class="section-card-footer">
            <a href="{{ route('watchlist.index') }}">Lihat Semua Watchlist →</a>
        </div>
    </div>

    <!-- PLAYLISTS -->
    <div class="section-card">
        <div class="section-card-header">
            <div class="section-card-title"><span class="dot"></span>Playlist Saya</div>
        </div>
        <div class="section-card-body">
            @forelse($playlists as $playlist)
                <div class="playlist-item">
                    <div class="playlist-item-name">
                        {{ $playlist->nama_playlist }}
                        <span class="v-badge v-badge-gold" style="font-size:11px;">Public</span>
                    </div>
                    <div class="playlist-item-desc">{{ $playlist->deskripsi ?? 'Tidak ada deskripsi' }}</div>
                </div>
            @empty
                <div class="empty-state-sm">Belum ada playlist yang dibuat.</div>
            @endforelse
        </div>
        <div class="section-card-footer">
            <a href="{{ route('playlists.index') }}">Kelola Playlist →</a>
        </div>
    </div>
</div>

@endsection
