<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user(); 

        // NANTI KITA UBAH INI JADI QUERY DATABASE ASLI DI TAHAP 2
        // Data Dummy Statistik
        $stats = [
            'total_reviews' => 24,
            'total_watch_time' => '142 Jam',
            'top_genre' => 'Sci-Fi & Thriller'
        ];

        // Data Dummy Watchlist (Sedang ditonton, Selesai, dll)
        $watchlists = [
            ['title' => 'Oppenheimer', 'status' => 'Selesai', 'type' => 'Movie'],
            ['title' => 'Shogun', 'status' => 'Sedang Menonton', 'type' => 'Series'],
            ['title' => 'Dune: Part Two', 'status' => 'Rencana', 'type' => 'Movie'],
        ];

        // Data Dummy Playlist
        $playlists = [
            ['name' => 'Sci-Fi Masterpieces', 'count' => 12, 'visibility' => 'Public'],
            ['name' => 'Weekend Chill', 'count' => 5, 'visibility' => 'Private'],
        ];

        return view('profile.index', compact('user', 'stats', 'watchlists', 'playlists'));
    }
}