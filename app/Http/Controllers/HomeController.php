<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Jika database masih kosong, collection ini akan kosong dan view tidak akan error
        // Mengambil data berdasarkan kriteria section
        
        // 1. Hot (Misal: 10 tayangan terbaru berdasarkan tanggal rilis)
        $hotMedia = Media::orderBy('tanggal_rilis', 'desc')->limit(10)->get();
        
        // 2. Completed (Tayangan yang sudah tamat)
        $completedMedia = Media::where('status_tayang', 'Completed')->latest()->limit(10)->get();
        
        // 3. Ongoing (Sedang tayang mingguan)
        $ongoingMedia = Media::where('status_tayang', 'Ongoing')->latest()->limit(10)->get();
        
        // 4. Top Rated (Untuk sementara kita ambil acak/random sebelum fitur rating review selesai)
        $topRatedMedia = Media::inRandomOrder()->limit(10)->get();

        return view('home', compact('hotMedia', 'completedMedia', 'ongoingMedia', 'topRatedMedia'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $results = collect(); // Koleksi kosong sebagai default jika tidak ada pencarian

        if ($query) {
            // Mencari media yang judulnya mengandung kata kunci pencarian
            $results = Media::where('judul', 'like', '%' . $query . '%')
                            ->latest()
                            ->get();
        }

        return view('search', compact('results', 'query'));
    }
}
