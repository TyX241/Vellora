<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\PlaylistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlaylistController extends Controller
{
    // Menampilkan semua playlist milik pengguna
    public function index()
    {
        $playlists = Playlist::withCount('items')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
            
        return view('playlists.index', compact('playlists'));
    }

    // Menyimpan playlist baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama_playlist' => 'required|string|max:255',
            'deskripsi' => 'nullable|string'
        ]);

        Playlist::create([
            'user_id' => Auth::id(),
            'nama_playlist' => $request->nama_playlist,
            'deskripsi' => $request->deskripsi
        ]);

        return back()->with('success', 'Playlist baru berhasil dibuat!');
    }

    // Melihat isi spesifik dari suatu playlist beserta item medianya
    public function show($id)
    {
        $playlist = Playlist::with('items.media')
            ->where('playlist_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        return view('playlists.show', compact('playlist'));
    }

    // Menghapus sebuah playlist
    public function destroy($id)
    {
        $playlist = Playlist::where('playlist_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        $playlist->delete();

        return redirect()->route('playlists.index')->with('success', 'Playlist berhasil dihapus.');
    }

    // Menambahkan tayangan film/series/anime ke dalam playlist kustom
    public function addMedia(Request $request)
    {
        $request->validate([
            'playlist_id' => 'required|exists:playlists,playlist_id',
            'media_id' => 'required|exists:media,media_id'
        ]);

        // Proteksi kepemilikan playlist
        Playlist::where('playlist_id', $request->playlist_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Validasi duplikasi item di playlist yang sama
        $exists = PlaylistItem::where('playlist_id', $request->playlist_id)
            ->where('media_id', $request->media_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Tayangan ini sudah ada di dalam playlist tersebut.');
        }

        PlaylistItem::create([
            'playlist_id' => $request->playlist_id,
            'media_id' => $request->media_id
        ]);

        return back()->with('success', 'Berhasil menambahkan tayangan ke playlist!');
    }

    // Mengeluarkan tayangan dari playlist kustom
    public function removeMedia($id)
    {
        $item = PlaylistItem::findOrFail($id);
        
        // Proteksi kepemilikan playlist sebelum menghapus item di dalamnya
        Playlist::where('playlist_id', $item->playlist_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $item->delete();

        return back()->with('success', 'Tayangan berhasil dihapus dari playlist.');
    }
}