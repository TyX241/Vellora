<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Genre;
use App\Models\Actor;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        try {
            // 1. Ambil keyword dari request (sesuaikan dengan name di HTML)
            $search = $request->input('search');          // Untuk Media
            $genreSearch = $request->input('search_genre'); // SESUAIKAN DENGAN HTML
            $actorSearch = $request->input('actor_search'); // Untuk Aktor

            // 2. Query Media
            $mediaQuery = Media::latest();
            if ($search) {
                $mediaQuery->where('judul', 'like', '%' . $search . '%');
            }
            $allMedia = $mediaQuery->get();

            // 3. Query Genre
            $genreQuery = Genre::query();
            if ($genreSearch) {
                $genreQuery->where('nama_genre', 'like', '%' . $genreSearch . '%');
            }
            $genres = $genreQuery->orderBy('nama_genre', 'asc')->get();

            // 4. Query Aktor
            $actorQuery = Actor::query();
            if ($actorSearch) {
                $actorQuery->where('nama_aktor', 'like', '%' . $actorSearch . '%');
            }
            $actors = $actorQuery->orderBy('nama_aktor', 'asc')->get();

            // 5. Return View
            $genres_all = Genre::orderBy('nama_genre', 'asc')->get();
            $actors_all = Actor::orderBy('nama_aktor', 'asc')->get();

            return view('admin.panel', compact(
                'genres', 'allMedia', 'actors', 
                'search', 'genreSearch', 'actorSearch',
                'genres_all', 'actors_all' // Kirim data lengkapnya
            ));

        } catch (\Exception $e) {
            dd($e->getMessage()); 
        }
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
        $media = Media::create($request->all());

        if($request->has('genres')) {
            $media->genres()->attach($request->genres);
        }
        // Tambahkan ini:
        if($request->has('actors')) {
            $media->actors()->attach($request->actors);
        }
        return redirect()->back()->with('success', 'Media tayangan berhasil ditambahkan!');
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
        $actors = Actor::all(); // Tambahkan ini
    
        return view('admin.edit_media', compact('media', 'genres', 'actors'));
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
        if ($request->has('actors')) {
            $media->actors()->sync($request->actors);
        } else {
            $media->actors()->detach();
        }

        return redirect()->route('admin.panel')->with('success', 'Data Media berhasil diperbarui!');
    }
    public function storeActor(Request $request) {
        $request->validate(['nama_aktor' => 'required']);
        Actor::create($request->all());
        return redirect()->back()->with('success', 'Aktor berhasil ditambah');
    }
    public function destroyActor($id)
    {
        $actor = \App\Models\Actor::findOrFail($id);
        $actor->delete(); 
        
        return back()->with('success', 'Pemeran berhasil dihapus!');
    }

}
