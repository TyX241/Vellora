@extends('layouts.app')

@section('content')
<div style="background-color: #121212; min-height: 100vh; padding: 40px 0; color: #ffffff; font-family: sans-serif;">
    <div style="max-width: 1000px; margin: 0 auto;">
        
        <!-- HEADER PROFILE -->
        <div style="background-color: #1e1e1e; padding: 30px; border-radius: 10px; display: flex; align-items: center; margin-bottom: 20px;">
            <div style="width: 120px; height: 120px; background-color: #ffc107; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 50px; font-weight: bold; color: #000;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div style="margin-left: 30px;">
                <h1 style="margin: 0; color: #ffc107; font-size: 36px;">{{ $user->name }}</h1>
                <p style="margin: 5px 0 0 0; color: #aaa; font-size: 16px;">Bergabung sejak {{ $user->created_at->format('F Y') }}</p>
            </div>
        </div>

        <!-- STATISTIK PERSONAL -->
        <h2 style="color: #ffc107; border-left: 4px solid #ffc107; padding-left: 10px; margin-top: 30px;">Statistik Personal</h2>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px;">
            <div style="background-color: #1e1e1e; padding: 20px; border-radius: 8px; text-align: center;">
                <h3 style="color: #aaa; margin: 0; font-size: 16px;">Total Ulasan</h3>
                <p style="color: #fff; font-size: 28px; font-weight: bold; margin: 10px 0 0 0;">{{ $stats['total_reviews'] }} <span style="font-size: 14px; font-weight: normal;">Judul</span></p>
            </div>
            <div style="background-color: #1e1e1e; padding: 20px; border-radius: 8px; text-align: center;">
                <h3 style="color: #aaa; margin: 0; font-size: 16px;">Waktu Menonton</h3>
                <p style="color: #fff; font-size: 28px; font-weight: bold; margin: 10px 0 0 0;">{{ $stats['total_watch_time'] }}</p>
            </div>
            <div style="background-color: #1e1e1e; padding: 20px; border-radius: 8px; text-align: center;">
                <h3 style="color: #aaa; margin: 0; font-size: 16px;">Genre Favorit</h3>
                <p style="color: #ffc107; font-size: 24px; font-weight: bold; margin: 10px 0 0 0;">{{ $stats['top_genre'] }}</p>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            <!-- KOLEKSI (WATCHLIST & HISTORY) -->
            <div>
                <h2 style="color: #ffc107; border-left: 4px solid #ffc107; padding-left: 10px;">Aktivitas Tontonan</h2>
                <div style="background-color: #1e1e1e; padding: 20px; border-radius: 8px;">
                    @foreach($watchlists as $watch)
                    <div style="border-bottom: 1px solid #333; padding: 15px 0; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <strong style="font-size: 18px;">{{ $watch['title'] }}</strong>
                            <span style="background-color: #333; padding: 2px 8px; border-radius: 4px; font-size: 12px; margin-left: 10px; color: #aaa;">{{ $watch['type'] }}</span>
                        </div>
                        
                        @if($watch['status'] == 'Selesai')
                            <span style="color: #4caf50; font-weight: bold; font-size: 14px;">✔ Selesai</span>
                        @elseif($watch['status'] == 'Sedang Menonton')
                            <span style="color: #ffc107; font-weight: bold; font-size: 14px;">▶ Sedang Menonton</span>
                        @else
                            <span style="color: #aaa; font-size: 14px;">🗓 Rencana</span>
                        @endif
                    </div>
                    @endforeach
                    <a href="#" style="display: block; text-align: center; color: #ffc107; text-decoration: none; margin-top: 15px; font-size: 14px;">Lihat Semua Aktivitas &rarr;</a>
                </div>
            </div>

            <!-- GALERI PLAYLIST KUSTOM -->
            <div>
                <h2 style="color: #ffc107; border-left: 4px solid #ffc107; padding-left: 10px;">Playlist Saya</h2>
                <div style="background-color: #1e1e1e; padding: 20px; border-radius: 8px;">
                    @foreach($playlists as $playlist)
                    <div style="background-color: #2a2a2a; padding: 15px; border-radius: 6px; margin-bottom: 15px; border-left: 3px solid #ffc107;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <strong style="font-size: 18px;">{{ $playlist['name'] }}</strong>
                            <span style="font-size: 12px; color: {{ $playlist['visibility'] == 'Public' ? '#4caf50' : '#f44336' }}; border: 1px solid currentColor; padding: 2px 6px; border-radius: 4px;">
                                {{ $playlist['visibility'] }}
                            </span>
                        </div>
                        <p style="margin: 5px 0 0 0; color: #aaa; font-size: 14px;">Berisi {{ $playlist['count'] }} tayangan</p>
                    </div>
                    @endforeach
                    <a href="#" style="display: block; text-align: center; color: #ffc107; text-decoration: none; margin-top: 15px; font-size: 14px;">Kelola Playlist &rarr;</a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection