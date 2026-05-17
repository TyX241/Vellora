<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Hot: 10 tayangan terbaru berdasarkan tanggal rilis
        $hotMedia = Media::orderBy('tanggal_rilis', 'desc')->limit(10)->get();
        
        // 2. Top Rated: Diurutkan berdasarkan rata-rata rating tertinggi dari tabel reviews
        $topRatedMedia = Media::withAvg('reviews', 'rating')
            ->orderBy('reviews_avg_rating', 'desc')
            ->limit(10)
            ->get();
        
        // 3. Populer: Diurutkan berdasarkan jumlah akumulasi media ini disimpan di watchlist user
        $populerMedia = Media::withCount('watchlists')
            ->orderBy('watchlists_count', 'desc')
            ->limit(10)
            ->get();
        
        // 4. Ongoing: Tayangan dengan status Ongoing terbaru
        $ongoingMedia = Media::where('status_tayang', 'Ongoing')->latest()->limit(10)->get();

        return view('home', compact('hotMedia', 'topRatedMedia', 'populerMedia', 'ongoingMedia'));
    }

    // Fungsi Pengambilan Kriteria Halaman Browse All
    public function browse($type)
    {
        $query = Media::query();
        $title = '';

        switch ($type) {
            case 'hot':
                $query->orderBy('tanggal_rilis', 'desc');
                $title = '🔥 Sedang Hangat (Hot)';
                break;
            case 'top-rated':
                $query->withAvg('reviews', 'rating')->orderBy('reviews_avg_rating', 'desc');
                $title = '⭐ Skor Tertinggi (Top Rated)';
                break;
            case 'populer':
                $query->withCount('watchlists')->orderBy('watchlists_count', 'desc');
                $title = '📈 Paling Populer (Trending)';
                break;
            case 'ongoing':
                $query->where('status_tayang', 'Ongoing')->latest();
                $title = '📺 Sedang Tayang (Ongoing)';
                break;
            default:
                abort(404);
        }

        $results = $query->get();

        return view('browse', compact('results', 'title', 'type'));
    }
}