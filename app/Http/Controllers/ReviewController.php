<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'media_id' => 'required|exists:media,media_id',
            'rating' => 'required|numeric|min:1|max:10',
            'komentar' => 'nullable|string'
        ]);

        Review::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'media_id' => $request->media_id,
            ],
            [
                'rating' => $request->rating,
                'komentar' => $request->komentar,
            ]
        );

        return back()->with('success', 'Ulasan Anda berhasil disimpan!');
    }

    public function destroy($id)
    {
        $review = Review::where('review_id', $id)->where('user_id', Auth::id())->firstOrFail();
        $review->delete();

        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}