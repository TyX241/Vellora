<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vellora — Katalog Film & Series</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --gold: #C9A84C;
            --gold-light: #E8C96A;
            --gold-dim: rgba(201,168,76,0.15);
            --bg-base: #0B0B0F;
            --bg-card: #13131A;
            --bg-elevated: #1A1A24;
            --bg-hover: #21212E;
            --border: rgba(255,255,255,0.07);
            --border-gold: rgba(201,168,76,0.35);
            --text-primary: #F0EDE8;
            --text-secondary: #8A8799;
            --text-muted: #4A4860;
            --radius: 10px;
            --radius-lg: 16px;
        }
        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            background-color: var(--bg-base);
            color: var(--text-primary);
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }
        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none; z-index: -1; opacity: 0.5;
        }
        /* Fix: pastikan Bootstrap modal backdrop & dialog berada di atas noise layer dan navbar */
        .modal-backdrop { z-index: 1040 !important; }
        .modal { z-index: 1050 !important; }
        .modal-content { position: relative; z-index: 1060 !important; }
        .modal-content * { position: relative; z-index: 1065 !important; }
        .vellora-nav {
            position: sticky; top: 0; z-index: 1000;
            background: rgba(11,11,15,0.88);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border); padding: 0;
        }
        .nav-inner {
            display: flex; align-items: center; gap: 24px;
            height: 64px; max-width: 1280px; margin: 0 auto; padding: 0 24px;
        }
        .nav-brand {
            font-family: 'Bebas Neue', sans-serif; font-size: 28px;
            letter-spacing: 3px; color: var(--gold); text-decoration: none;
            flex-shrink: 0; transition: opacity 0.2s;
        }
        .nav-brand:hover { opacity: 0.8; color: var(--gold); }
        .nav-search { flex: 1; max-width: 480px; margin: 0 auto; position: relative; }
        .nav-search form { position: relative; }
        .nav-search input {
            width: 100%; background: var(--bg-elevated);
            border: 1px solid var(--border); border-radius: 50px;
            color: var(--text-primary); padding: 8px 90px 8px 40px;
            font-family: 'DM Sans', sans-serif; font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s; outline: none;
        }
        .nav-search input::placeholder { color: var(--text-muted); }
        .nav-search input:focus { border-color: var(--border-gold); box-shadow: 0 0 0 3px var(--gold-dim); }
        .nav-search-icon {
            position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
            color: var(--text-muted); pointer-events: none; width: 16px; height: 16px;
        }
        .nav-search-btn {
            position: absolute; right: 5px; top: 50%; transform: translateY(-50%);
            background: var(--gold); border: none; border-radius: 50px; color: #000;
            font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 600;
            padding: 5px 14px; cursor: pointer; transition: background 0.2s;
        }
        .nav-search-btn:hover { background: var(--gold-light); }
        .nav-actions { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
        .nav-link-item {
            color: var(--text-secondary); text-decoration: none;
            font-size: 14px; font-weight: 500; padding: 6px 12px;
            border-radius: 6px; transition: color 0.2s, background 0.2s; white-space: nowrap;
        }
        .nav-link-item:hover { color: var(--text-primary); background: var(--bg-elevated); }
        .btn-gold {
            background: var(--gold); color: #000; border: none; border-radius: 6px;
            font-family: 'DM Sans', sans-serif; font-weight: 600; font-size: 13px;
            padding: 7px 18px; cursor: pointer; text-decoration: none;
            display: inline-flex; align-items: center; gap: 6px;
            transition: background 0.2s, transform 0.15s; white-space: nowrap;
        }
        .btn-gold:hover { background: var(--gold-light); color: #000; transform: translateY(-1px); }
        .btn-ghost {
            background: transparent; color: var(--text-secondary);
            border: 1px solid var(--border); border-radius: 6px;
            font-family: 'DM Sans', sans-serif; font-weight: 500; font-size: 13px;
            padding: 7px 18px; cursor: pointer; text-decoration: none;
            display: inline-flex; align-items: center; gap: 6px;
            transition: border-color 0.2s, color 0.2s; white-space: nowrap;
        }
        .btn-ghost:hover { border-color: var(--border-gold); color: var(--gold); }
        .nav-dropdown { position: relative; }
        .nav-dropdown-trigger {
            display: flex; align-items: center; gap: 8px;
            color: var(--text-secondary); font-size: 14px; font-weight: 500;
            padding: 6px 12px; border-radius: 6px; cursor: pointer;
            transition: color 0.2s, background 0.2s; user-select: none; border: none;
            background: transparent; font-family: 'DM Sans', sans-serif;
        }
        .nav-dropdown-trigger:hover { color: var(--text-primary); background: var(--bg-elevated); }
        .nav-avatar {
            width: 30px; height: 30px; background: var(--gold-dim);
            border: 1px solid var(--border-gold); border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700; color: var(--gold);
        }
        .nav-dropdown-menu {
            position: absolute; top: calc(100% + 8px); right: 0;
            background: var(--bg-card); border: 1px solid var(--border);
            border-radius: var(--radius); padding: 6px; min-width: 185px;
            box-shadow: 0 16px 40px rgba(0,0,0,0.5); opacity: 0; visibility: hidden;
            transform: translateY(-6px); transition: opacity 0.2s, transform 0.2s, visibility 0.2s;
            z-index: 999;
        }
        .nav-dropdown:hover .nav-dropdown-menu,
        .nav-dropdown:focus-within .nav-dropdown-menu { opacity: 1; visibility: visible; transform: translateY(0); }
        .dropdown-item-v {
            display: block; padding: 9px 12px; color: var(--text-secondary);
            text-decoration: none; font-size: 13px; font-weight: 500;
            border-radius: 6px; transition: color 0.15s, background 0.15s;
            background: transparent; border: none; cursor: pointer;
            font-family: 'DM Sans', sans-serif; width: 100%; text-align: left;
        }
        .dropdown-item-v:hover { color: var(--text-primary); background: var(--bg-elevated); }
        .dropdown-item-v.danger { color: #E05252; }
        .dropdown-item-v.danger:hover { background: rgba(224,82,82,0.1); }
        .dropdown-divider-v { height: 1px; background: var(--border); margin: 4px 0; }
        .nav-hamburger {
            display: none; background: transparent; border: 1px solid var(--border);
            border-radius: 6px; padding: 6px 10px; color: var(--text-secondary);
            cursor: pointer; margin-left: auto;
        }
        .nav-mobile-menu {
            display: none; padding: 12px 24px 16px;
            border-top: 1px solid var(--border); flex-direction: column; gap: 8px;
        }
        @media (max-width: 768px) {
            .nav-search { display: none; }
            .nav-actions { display: none; }
            .nav-hamburger { display: block; }
            .nav-mobile-menu.open { display: flex; }
        }
        .page-wrapper {
            max-width: 1280px; margin: 0 auto;
            padding: 40px 24px 80px; position: relative;
        }
        .section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
        .section-title {
            font-size: 18px; font-weight: 600; color: var(--text-primary);
            display: flex; align-items: center; gap: 10px;
        }
        .section-title .dot { width: 4px; height: 18px; background: var(--gold); border-radius: 2px; flex-shrink: 0; }
        .section-link { font-size: 13px; font-weight: 500; color: var(--gold); text-decoration: none; opacity: 0.8; transition: opacity 0.2s; }
        .section-link:hover { opacity: 1; color: var(--gold); }
        .media-card { display: block; text-decoration: none; color: inherit; }
        .poster-wrap {
            position: relative; border-radius: var(--radius); overflow: hidden;
            background: var(--bg-elevated); aspect-ratio: 2/3; margin-bottom: 10px;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .media-card:hover .poster-wrap {
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(0,0,0,0.5), 0 0 0 1px var(--border-gold);
        }
        .poster-wrap img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .poster-badge {
            position: absolute; top: 8px; left: 8px;
            background: rgba(0,0,0,0.75); backdrop-filter: blur(6px);
            border-radius: 4px; padding: 2px 7px; font-size: 11px; font-weight: 600; color: #fff;
        }
        .poster-badge.ongoing { color: #4CAF72; border: 1px solid rgba(76,175,114,0.4); }
        .poster-badge.finished { color: var(--text-muted); border: 1px solid rgba(255,255,255,0.1); }
        .media-card-title {
            font-size: 13px; font-weight: 500; color: var(--text-primary); line-height: 1.4;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
            overflow: hidden; transition: color 0.2s;
        }
        .media-card:hover .media-card-title { color: var(--gold); }
        .media-card-meta { font-size: 12px; color: var(--text-muted); margin-top: 3px; }
        .rating-pill {
            display: inline-flex; align-items: center; gap: 4px;
            background: var(--gold-dim); border: 1px solid var(--border-gold);
            border-radius: 4px; padding: 1px 7px; font-size: 12px;
            font-weight: 600; color: var(--gold); margin-top: 4px;
        }
        .media-row { display: flex; gap: 14px; overflow-x: auto; padding-bottom: 8px; scrollbar-width: none; }
        .media-row::-webkit-scrollbar { display: none; }
        .media-row .media-card { flex: 0 0 148px; }
        .v-alert { padding: 12px 16px; border-radius: var(--radius); font-size: 14px; font-weight: 500; margin-bottom: 16px; }
        .v-alert-success { background: rgba(76,175,114,0.12); border: 1px solid rgba(76,175,114,0.3); color: #4CAF72; }
        .v-alert-danger { background: rgba(224,82,82,0.12); border: 1px solid rgba(224,82,82,0.3); color: #E05252; }
        .modal-content { background: var(--bg-card) !important; border: 1px solid var(--border) !important; border-radius: var(--radius-lg) !important; color: var(--text-primary) !important; }
        .modal-header { border-bottom: 1px solid var(--border) !important; padding: 20px 24px !important; }
        .modal-footer { border-top: 1px solid var(--border) !important; padding: 16px 24px !important; }
        .modal-body { padding: 20px 24px !important; }
        .modal-title { font-weight: 600 !important; color: var(--text-primary) !important; }
        .v-input, .v-select, .v-textarea {
            width: 100%; background: var(--bg-elevated); border: 1px solid var(--border);
            border-radius: var(--radius); color: var(--text-primary);
            font-family: 'DM Sans', sans-serif; font-size: 14px; padding: 10px 14px;
            outline: none; transition: border-color 0.2s, box-shadow 0.2s;
        }
        .v-input::placeholder, .v-textarea::placeholder { color: var(--text-muted); }
        .v-input:focus, .v-select:focus, .v-textarea:focus { border-color: var(--border-gold); box-shadow: 0 0 0 3px var(--gold-dim); }
        .v-select option { background: var(--bg-card); }
        .v-textarea { resize: vertical; min-height: 100px; }
        .v-label { display: block; font-size: 13px; font-weight: 500; color: var(--text-secondary); margin-bottom: 6px; }
        .v-form-group { margin-bottom: 16px; }
        .v-badge { display: inline-block; padding: 3px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; }
        .v-badge-ongoing { background: rgba(76,175,114,0.15); color: #4CAF72; }
        .v-badge-finished { background: var(--bg-elevated); color: var(--text-muted); }
        .v-badge-gold { background: var(--gold-dim); color: var(--gold); }
        .vellora-footer { border-top: 1px solid var(--border); padding: 32px 24px; text-align: center; color: var(--text-muted); font-size: 13px; position: relative; z-index: 1; }
        .vellora-footer .brand-foot { color: var(--gold); font-family: 'Bebas Neue', sans-serif; letter-spacing: 1px; font-size: 15px; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        .page-wrapper { animation: none; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-base); }
        ::-webkit-scrollbar-thumb { background: var(--bg-elevated); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-muted); }
    </style>
    @stack('styles')
</head>
<body>

<nav class="vellora-nav">
    <div class="nav-inner">
        <a href="{{ route('home') }}" class="nav-brand">VELLORA</a>

        <div class="nav-search">
            <form action="{{ route('search') }}" method="GET">
                <svg class="nav-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="text" name="q" placeholder="Cari film, series, anime..." value="{{ request('q') }}">
                <button type="submit" class="nav-search-btn">Cari</button>
            </form>
        </div>

        <div class="nav-actions">
            @guest
                <a href="{{ route('login') }}" class="nav-link-item">Masuk</a>
                <a href="{{ route('register') }}" class="btn-gold">Daftar</a>
            @endguest
            @auth
                <div class="nav-dropdown">
                    <button class="nav-dropdown-trigger">
                        <div class="nav-avatar">{{ strtoupper(substr(Auth::user()->username, 0, 1)) }}</div>
                        <span>{{ Auth::user()->username }}</span>
                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </button>
                    <div class="nav-dropdown-menu">
                        <a href="{{ route('profile.index') }}" class="dropdown-item-v">👤 &nbsp;Profil Saya</a>
                        <a href="{{ route('watchlist.index') }}" class="dropdown-item-v">📺 &nbsp;Watchlist</a>
                        <a href="{{ route('playlists.index') }}" class="dropdown-item-v">📁 &nbsp;Playlist</a>
                        <div class="dropdown-divider-v"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item-v danger">Keluar</button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>

        <button class="nav-hamburger" onclick="document.getElementById('mobileMenu').classList.toggle('open')" aria-label="Menu">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </div>
    <div class="nav-mobile-menu" id="mobileMenu">
        <form action="{{ route('search') }}" method="GET" style="display:flex;gap:8px;">
            <input type="text" name="q" placeholder="Cari tayangan..." value="{{ request('q') }}" class="v-input" style="flex:1;">
            <button type="submit" class="btn-gold">Cari</button>
        </form>
        @guest
            <a href="{{ route('login') }}" class="nav-link-item">Masuk</a>
            <a href="{{ route('register') }}" class="btn-gold" style="text-align:center;justify-content:center;">Daftar</a>
        @endguest
        @auth
            <a href="{{ route('profile.index') }}" class="nav-link-item">👤 Profil</a>
            <a href="{{ route('watchlist.index') }}" class="nav-link-item">📺 Watchlist</a>
            <a href="{{ route('playlists.index') }}" class="nav-link-item">📁 Playlist</a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" style="background:transparent;border:none;color:#E05252;cursor:pointer;font-family:inherit;padding:6px 12px;font-size:14px;">Keluar</button>
            </form>
        @endauth
    </div>
</nav>

<div class="page-wrapper">
    @yield('content')
</div>

<footer class="vellora-footer">
    <span class="brand-foot">VELLORA</span> &nbsp;·&nbsp; Katalog Film & Series &nbsp;·&nbsp; &copy; {{ date('Y') }}
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
