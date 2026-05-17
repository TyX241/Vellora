<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Tambahkan ini wajib!

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Cek ID user (jaga-jaga kalau kalian pakai primary key 'user_id' atau 'id')
        $userId = $user->user_id ?? $user->id; 

        // 1. STATISTIK: Hitung total review asli dari database
        $totalReviews = DB::table('reviews')->where('user_id', $userId)->count();

        // 2. WATCHLIST: Ambil 5 aktivitas terbaru milik user, gabung (join) dengan tabel media biar dapat judulnya
        $watchlists = DB::table('watchlists')
            ->join('media', 'watchlists.media_id', '=', 'media.media_id')
            ->where('watchlists.user_id', $userId)
            ->orderBy('watchlists.waktu_diubah', 'desc')
            ->limit(5)
            ->get();

        // 3. PLAYLIST: Ambil playlist asli buatan user
        $playlists = DB::table('playlists')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('profile.index', compact('user', 'totalReviews', 'watchlists', 'playlists'));
    }
}