<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function show($id)
    {
        $media = \App\Models\Media::with(['genres', 'reviews.user', 'characters.actor'])->findOrFail($id);

        $userWatchlist = null;
        $userReview = null;
        $userPlaylists = collect();

        if (auth()->check()) {
            $userWatchlist = \App\Models\Watchlist::where('user_id', auth()->id())
                                ->where('media_id', $id)
                                ->first();

            $userReview = \App\Models\Review::where('user_id', auth()->id())
                                ->where('media_id', $id)
                                ->first();

            $userPlaylists = \App\Models\Playlist::where('user_id', auth()->id())->latest()->get();
        }

        $averageRating = $media->reviews->avg('rating');

        return view('media.show', compact('media', 'userWatchlist', 'userReview', 'averageRating', 'userPlaylists'));
    }
}
