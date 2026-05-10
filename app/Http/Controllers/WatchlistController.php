<?php

namespace App\Http\Controllers;

use App\Models\Watchlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WatchlistController extends Controller
{
    // Menampilkan halaman profil watchlist pengguna
    public function index()
    {
        // Ambil semua watchlist milik user yang sedang login beserta data medianya
        $watchlists = Watchlist::with('media')->where('user_id', Auth::id())->latest('waktu_diubah')->get();
        
        return view('watchlist.index', compact('watchlists'));
    }

    // Memasukkan atau memperbarui status tontonan (Upsert)
    public function store(Request $request)
    {
        $request->validate([
            'media_id' => 'required|exists:media,media_id',
            'status' => 'required|in:Plan to Watch,Watching,Completed,Dropped',
        ]);

        // updateOrCreate akan mengecek: jika user sudah menambahkan media ini, update statusnya. Jika belum, buat baru.
        Watchlist::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'media_id' => $request->media_id,
            ],
            [
                'status' => $request->status,
                // Kolom 'waktu_diubah' otomatis diperbarui oleh database berkat useCurrentOnUpdate() di file migration
            ]
        );

        return back()->with('success', 'Watchlist berhasil diperbarui!');
    }

    // Menghapus dari watchlist
    public function destroy($id)
    {
        $watchlist = Watchlist::where('watchlist_id', $id)->where('user_id', Auth::id())->firstOrFail();
        $watchlist->delete();

        return back()->with('success', 'Tayangan dihapus dari Watchlist.');
    }
}