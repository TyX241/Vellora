<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userId = $user->user_id ?? $user->id;

        $totalReviews = DB::table('reviews')->where('user_id', $userId)->count();

        // 2. Statistik Waktu Menonton (Total Menit)
        // Ambil media yang statusnya 'Completed' oleh user tersebut
        $totalMinutes = DB::table('watchlists')
            ->join('media', 'watchlists.media_id', '=', 'media.media_id')
            ->where('watchlists.user_id', $userId)
            ->where('watchlists.status', 'Completed')
            ->sum(DB::raw('media.total_episode * media.durasi_per_episode'));

        // Ubah ke format jam dan menit
        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;
        $watchTime = ($hours > 0 ? $hours . ' Jam ' : '') . $minutes . ' Menit';

       $favoriteGenre = DB::table('watchlists')
            ->join('media_genres', 'watchlists.media_id', '=', 'media_genres.media_id')
            ->join('genres', 'media_genres.genre_id', '=', 'genres.genre_id')
            ->where('watchlists.user_id', $userId)
            ->select('genres.nama_genre', DB::raw('count(*) as total'))
            ->groupBy('genres.nama_genre')
            ->orderBy('total', 'desc')
            ->first();

        $favoriteGenre = $favoriteGenre ? $favoriteGenre->nama_genre : 'Belum ada';

        $watchlists = DB::table('watchlists')
            ->join('media', 'watchlists.media_id', '=', 'media.media_id')
            ->where('watchlists.user_id', $userId)
            ->orderBy('watchlists.waktu_diubah', 'desc')
            ->limit(5)
            ->get();

        $playlists = DB::table('playlists')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('profile.index', compact('user', 'totalReviews', 'watchlists', 'playlists', 'watchTime', 'favoriteGenre'));
    }
}