<?php

namespace App\Http\Controllers;

use App\Models\Watchlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WatchlistController extends Controller
{
    public function index()
    {
        $watchlists = Watchlist::with('media')->where('user_id', Auth::id())->latest('waktu_diubah')->get();

        return view('watchlist.index', compact('watchlists'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'media_id' => 'required|exists:media,media_id',
            'status' => 'required|in:Plan to Watch,Watching,Completed,Dropped',
        ]);

        Watchlist::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'media_id' => $request->media_id,
            ],
            [
                'status' => $request->status,
            ]
        );

        return back()->with('success', 'Watchlist berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $watchlist = Watchlist::where('watchlist_id', $id)->where('user_id', Auth::id())->firstOrFail();
        $watchlist->delete();

        return back()->with('success', 'Tayangan dihapus dari Watchlist.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Plan to Watch,Watching,Completed,Dropped',
        ]);

        $watchlist = Watchlist::where('watchlist_id', $id)->where('user_id', Auth::id())->firstOrFail();
        $watchlist->update(['status' => $request->status]);

        return back()->with('success', 'Watchlist berhasil diperbarui!');
    }
}