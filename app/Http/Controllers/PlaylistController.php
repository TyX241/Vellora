<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\PlaylistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlaylistController extends Controller
{
    public function index()
    {
        $playlists = Playlist::withCount('items')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('playlists.index', compact('playlists'));
    }

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

    public function show($id)
    {
        $playlist = Playlist::with('items.media')
            ->where('playlist_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('playlists.show', compact('playlist'));
    }

    public function destroy($id)
    {
        $playlist = Playlist::where('playlist_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $playlist->delete();

        return redirect()->route('playlists.index')->with('success', 'Playlist berhasil dihapus.');
    }

    public function addMedia(Request $request)
    {
        $request->validate([
            'playlist_id' => 'required|exists:playlists,playlist_id',
            'media_id' => 'required|exists:media,media_id'
        ]);

        Playlist::where('playlist_id', $request->playlist_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

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

    public function removeMedia($id)
    {
        $item = PlaylistItem::findOrFail($id);

        Playlist::where('playlist_id', $item->playlist_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $item->delete();

        return back()->with('success', 'Tayangan berhasil dihapus dari playlist.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_playlist' => 'required|string|max:255',
            'deskripsi' => 'nullable|string'
        ]);

        $playlist = Playlist::where('playlist_id', $id)->where('user_id', auth()->id())->firstOrFail();

        $playlist->update([
            'nama_playlist' => $request->nama_playlist,
            'deskripsi' => $request->deskripsi
        ]);

        return back()->with('success', 'Playlist berhasil diperbarui!');
    }
}