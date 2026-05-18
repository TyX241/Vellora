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
            'media_id'         => 'required|exists:media,media_id',
            'status'           => 'required|in:Plan to Watch,Watching,Completed,Dropped',
            'progres_episode'  => 'nullable|integer|min:0',
            'waktu_jam'        => 'nullable|integer|min:0|max:99',
            'waktu_menit'      => 'nullable|integer|min:0|max:59',
            'waktu_detik'      => 'nullable|integer|min:0|max:59',
        ]);

        $progresWaktu = $this->buildWaktu(
            $request->waktu_jam,
            $request->waktu_menit,
            $request->waktu_detik
        );

        Watchlist::updateOrCreate(
            [
                'user_id'  => Auth::id(),
                'media_id' => $request->media_id,
            ],
            [
                'status'          => $request->status,
                'progres_episode' => $request->progres_episode,
                'progres_waktu'   => $progresWaktu,
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
            'status'          => 'required|in:Plan to Watch,Watching,Completed,Dropped',
            'progres_episode' => 'nullable|integer|min:0',
            'waktu_jam'       => 'nullable|integer|min:0|max:99',
            'waktu_menit'     => 'nullable|integer|min:0|max:59',
            'waktu_detik'     => 'nullable|integer|min:0|max:59',
        ]);

        $progresWaktu = $this->buildWaktu(
            $request->waktu_jam,
            $request->waktu_menit,
            $request->waktu_detik
        );

        $watchlist = Watchlist::where('watchlist_id', $id)->where('user_id', Auth::id())->firstOrFail();
        $watchlist->update([
            'status'          => $request->status,
            'progres_episode' => $request->progres_episode,
            'progres_waktu'   => $progresWaktu,
        ]);

        return back()->with('success', 'Watchlist berhasil diperbarui!');
    }

    /**
     * Mengubah jam/menit/detik menjadi string HH:MM:SS, atau null jika semua kosong.
     */
    private function buildWaktu($jam, $menit, $detik): ?string
    {
        $jam    = (int) ($jam ?? 0);
        $menit  = (int) ($menit ?? 0);
        $detik  = (int) ($detik ?? 0);

        if ($jam === 0 && $menit === 0 && $detik === 0) {
            return null;
        }

        return sprintf('%02d:%02d:%02d', $jam, $menit, $detik);
    }
}
