<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function show($id)
    {
        // Load relasi genres dan reviews (beserta data user pembuat review)
        $media = \App\Models\Media::with(['genres', 'actors', 'characters.actor', 'reviews.user'])->findOrFail($id);
        
        $userWatchlist = null;
        $userReview = null; // Menyimpan data ulasan user saat ini (jika ada)
        
        if (auth()->check()) {
            $userWatchlist = \App\Models\Watchlist::where('user_id', auth()->id())
                                ->where('media_id', $id)
                                ->first();
                                
            $userReview = \App\Models\Review::where('user_id', auth()->id())
                                ->where('media_id', $id)
                                ->first();
        }
        
        // Hitung rata-rata rating
        $averageRating = $media->reviews->avg('rating');
        
        return view('media.show', compact('media', 'userWatchlist', 'userReview', 'averageRating'));
    }
}
