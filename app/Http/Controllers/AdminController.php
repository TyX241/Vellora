<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Genre;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        // Menangkap input pencarian
        $search = $request->input('search');
        $genreSearch = $request->input('genre_search');

        // Query untuk Media
        $mediaQuery = Media::latest();
        if ($search) {
            $mediaQuery->where('judul', 'like', '%' . $search . '%');
        }
        $allMedia = $mediaQuery->get();

        // Query untuk Genre
        $genreQuery = Genre::query();
        if ($genreSearch) {
            $genreQuery->where('nama_genre', 'like', '%' . $genreSearch . '%');
        }
        $genres = $genreQuery->orderBy('nama_genre', 'asc')->get();
        
        return view('admin.panel', compact('genres', 'allMedia', 'search', 'genreSearch'));
    }

    public function storeGenre(Request $request)
    {
        $request->validate(['nama_genre' => 'required|string|max:255']);
        Genre::create(['nama_genre' => $request->nama_genre]);
        
        return back()->with('success', 'Genre berhasil ditambahkan!');
    }

    public function storeMedia(Request $request)
    {
        // Validasi input dasar
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'format_tayangan' => 'required|in:Film,Series',
            'status_tayang' => 'required|in:Ongoing,Completed',
            'poster_url' => 'nullable|url',
        ]);

        // Simpan data media
        $media = Media::create($request->except('genres'));

        // Jika ada genre yang dipilih, simpan ke tabel pivot (media_genres)
        if ($request->has('genres')) {
            $media->genres()->sync($request->genres);
        }

        return back()->with('success', 'Media tayangan berhasil ditambahkan!');
    }

    public function destroyMedia($id)
    {
        $media = Media::findOrFail($id);
        $media->delete(); // Ini akan menghapus data media dan relasi di tabel pivot karena kita menggunakan cascade di migration

        return back()->with('success', 'Media berhasil dihapus!');
    }

    public function destroyGenre($id)
    {
        $genre = Genre::findOrFail($id);
        $genre->delete(); 

        return back()->with('success', 'Genre berhasil dihapus!');
    }

    public function editMedia($id)
    {
        $media = Media::with('genres')->findOrFail($id);
        $genres = Genre::all();
        
        return view('admin.edit_media', compact('media', 'genres'));
    }

    public function updateMedia(Request $request, $id)
    {
        $media = Media::findOrFail($id);
        
        $request->validate([
            'judul' => 'required|string|max:255',
            'format_tayangan' => 'required|in:Film,Series',
            'status_tayang' => 'required|in:Ongoing,Completed',
            'poster_url' => 'nullable|url',
        ]);

        $media->update($request->except('genres'));

        if ($request->has('genres')) {
            $media->genres()->sync($request->genres);
        } else {
            $media->genres()->detach(); // Kosongkan genre jika tidak ada yang dicentang
        }

        return redirect()->route('admin.panel')->with('success', 'Data Media berhasil diperbarui!');
    }
}
