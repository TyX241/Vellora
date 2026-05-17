<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Genre;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $hotMedia = Media::orderBy('tanggal_rilis', 'desc')->limit(10)->get();

        $topRatedMedia = Media::withAvg('reviews', 'rating')
            ->orderBy('reviews_avg_rating', 'desc')
            ->limit(10)
            ->get();

        $populerMedia = Media::withCount('watchlists')
            ->orderBy('watchlists_count', 'desc')
            ->limit(10)
            ->get();

        $ongoingMedia = Media::where('status_tayang', 'Ongoing')->latest()->limit(10)->get();

        return view('home', compact('hotMedia', 'topRatedMedia', 'populerMedia', 'ongoingMedia'));
    }

    public function browse(Request $request, $type)
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

        $filterKategori = $request->input('kategori');
        $filterGenre    = $request->input('genre');
        $filterAnimasi  = $request->input('animasi');

        if ($filterKategori) {
            $query->where('format_tayangan', $filterKategori);
        }
        if ($filterGenre) {
            $query->whereHas('genres', function ($q) use ($filterGenre) {
                $q->where('genres.genre_id', $filterGenre);
            });
        }
        if ($filterAnimasi !== null && $filterAnimasi !== '') {
            $query->where('is_animation', (bool) $filterAnimasi);
        }

        $results = $query->get();
        $genres  = Genre::orderBy('nama_genre')->get();

        return view('browse', compact('results', 'title', 'type', 'genres', 'filterKategori', 'filterGenre', 'filterAnimasi'));
    }

    public function search(Request $request)
    {
        $query          = $request->input('q');
        $filterKategori = $request->input('kategori');
        $filterGenre    = $request->input('genre');
        $filterStatus   = $request->input('status');

        $mediaQuery = Media::query();

        if ($query) {
            $mediaQuery->where('judul', 'like', '%' . $query . '%');
        }
        if ($filterKategori) {
            $mediaQuery->where('format_tayangan', $filterKategori);
        }
        if ($filterGenre) {
            $mediaQuery->whereHas('genres', function ($q) use ($filterGenre) {
                $q->where('genres.genre_id', $filterGenre);
            });
        }
        if ($filterStatus) {
            $mediaQuery->where('status_tayang', $filterStatus);
        }

        if ($query || $filterKategori || $filterGenre || $filterStatus) {
            $results = $mediaQuery->latest()->get();
        } else {
            $results = collect();
        }

        $genres = Genre::orderBy('nama_genre')->get();

        return view('search', compact('results', 'query', 'filterKategori', 'filterGenre', 'filterStatus', 'genres'));
    }
}