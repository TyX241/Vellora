<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vellora - Secret Area</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background-color: #0f1115; 
            color: #d1d5db; 
            font-family: monospace; 
        }
        .card { background-color: #1f2229 !important; border: 1px solid #374151 !important; }
        .form-control, .form-select { background-color: #111827 !important; border: 1px solid #374151 !important; color: white !important; }
        /* Scrollbar kustom untuk daftar genre agar lebih rapi */
        .genre-list::-webkit-scrollbar { width: 6px; }
        .genre-list::-webkit-scrollbar-thumb { background-color: #374151; border-radius: 4px; }
    </style>
</head>
<body class="py-5">

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-warning fw-bold mb-0">System Control Panel</h2>
            <small class="text-secondary">Akses langsung master data.</small>
        </div>
        <a href="{{ route('home') }}" class="text-secondary text-decoration-none hover-white">< Kembali ke Aplikasi</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success bg-success text-white border-0">{{ session('success') }}</div>
    @endif

    <div class="row mb-4">
        
        <div class="col-md-5 col-lg-4 mb-4 mb-md-0 d-flex flex-column gap-3">
            <div class="card shadow-sm">
                <div class="card-header border-bottom border-secondary">
                    <h6 class="mb-0 text-light fw-bold">Tambah Genre</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.genre.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small">Nama Genre</label>
                            <input type="text" name="nama_genre" class="form-control form-control-sm" required>
                        </div>
                        <button type="submit" class="btn btn-warning btn-sm w-100 fw-bold">Simpan Genre</button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm flex-fill">
                <div class="card-header border-bottom border-secondary d-flex justify-content-between align-items-center flex-wrap gap-2">
    
                    <div class="d-flex align-items-center gap-2">
                        <h6 class="mb-0 text-light fw-bold">Kelola Genre</h6>
                    </div>
                    
                    <form action="{{ route('admin.panel') }}" method="GET" class="d-flex">
                        <input type="text" name="search_genre" class="form-control form-control-sm bg-dark text-light border-secondary me-2" 
                            placeholder="Cari Genre..." value="{{ request('search_genre') }}">
                        <button type="submit" class="btn btn-outline-warning btn-sm">🔎</button>
                        
                        @if(request('search_genre'))
                            <a href="{{ route('admin.panel') }}" class="btn btn-outline-secondary btn-sm ms-2">Reset</a>
                        @endif
                    </form>

                </div>
                <div class="card-body p-0 genre-list" style="max-height: 480px; overflow-y: auto;">
                    <ul class="list-group list-group-flush">
                        @forelse($genres as $g)
                            <li class="list-group-item bg-transparent border-secondary d-flex justify-content-between align-items-center text-light small py-2">
                                {{ $g->nama_genre }}
                                <form action="{{ route('admin.genre.destroy', $g->genre_id) }}" method="POST" onsubmit="return confirm('Hapus genre ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger p-0 text-decoration-none fw-bold" style="font-size: 0.75rem;">Hapus</button>
                                </form>
                            </li>
                        @empty
                            <li class="list-group-item bg-transparent text-secondary small text-center">Genre tidak ditemukan</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-7 col-lg-8">
            <div class="card shadow-sm h-100">
                <div class="card-header border-bottom border-secondary">
                    <h6 class="mb-0 text-light fw-bold">Entry Media Baru</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.media.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small">Judul Tayangan</label>
                                <input type="text" name="judul" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small">URL Poster</label>
                                <input type="url" name="poster_url" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label small">Format</label>
                                <select name="format_tayangan" class="form-select form-select-sm">
                                    <option value="Film">Film</option>
                                    <option value="Series">Series</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label small">Status</label>
                                <select name="status_tayang" class="form-select form-select-sm">
                                    <option value="Ongoing">Ongoing</option>
                                    <option value="Completed">Completed</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label small">Negara Asal</label>
                                <input type="text" name="negara_asal" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label small">Total Episode</label>
                                <input type="number" name="total_episode" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label small">Tanggal Rilis</label>
                                <input type="date" name="tanggal_rilis" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label small">Animasi?</label>
                                <select name="is_animation" class="form-select form-select-sm">
                                    <option value="0">Tidak</option>
                                    <option value="1">Ya</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label small">Deskripsi/Sinopsis</label>
                                <textarea name="deskripsi" rows="8" class="form-control form-control-sm" style="resize: none;" placeholder="Tulis sinopsis lengkap di sini..."></textarea>
                            </div>
                            
                            <div class="col-12 mb-4">
                                <label class="form-label small">Pilih Genre</label>
                                <div class="d-flex flex-wrap gap-2">
                                    @forelse($genres as $genre)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="genres[]" value="{{ $genre->genre_id }}" id="genreMedia{{ $genre->genre_id }}">
                                            <label class="form-check-label small" for="genreMedia{{ $genre->genre_id }}">
                                                {{ $genre->nama_genre }}
                                            </label>
                                        </div>
                                    @empty
                                        <small class="text-danger">Belum ada genre.</small>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-warning w-100 fw-bold">Tambahkan Media</button>
                    </form>
                </div>
            </div>
        </div>
    </div> <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header border-bottom border-secondary d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <h6 class="mb-0 text-light fw-bold">Kelola Media</h6>
                        <span class="badge bg-secondary">{{ $allMedia->count() }} Items</span>
                    </div>
                    
                    <form action="{{ route('admin.panel') }}" method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control form-control-sm bg-dark text-light border-secondary me-2" 
                               placeholder="Cari Judul..." value="{{ $search ?? '' }}">
                        <button type="submit" class="btn btn-outline-warning btn-sm">🔎</button>
                        @if(!empty($search))
                            <a href="{{ route('admin.panel') }}" class="btn btn-outline-secondary btn-sm ms-2">Reset</a>
                        @endif
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0 align-middle text-center" style="font-size: 0.85rem; table-layout: fixed; width: 100%;">
                            <thead class="align-middle border-bottom border-secondary">
                                <tr class="text-secondary">
                                    <th style="width: 10%;" class="py-3">Poster</th>
                                    <th style="width: 35%;" class="text-start py-3">Judul & Format</th>
                                    <th style="width: 15%;" class="py-3">Status</th>
                                    <th style="width: 25%;" class="py-3">Genre</th>
                                    <th style="width: 15%;" class="py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="align-middle">
                                @forelse($allMedia as $item)
                                    <tr>
                                        <td>
                                            <img src="{{ $item->poster_url ?? 'https://via.placeholder.com/40x60' }}" 
                                                class="rounded" style="width: 40px; height: 60px; object-fit: contain; background-color: #1a1a1a;">
                                        </td>
                                        <td class="text-start">
                                            <div class="fw-bold text-light">{{ $item->judul }}</div>
                                            <small class="text-secondary">{{ $item->format_tayangan }} | {{ $item->negara_asal }}</small>
                                        </td>
                                        <td>
                                            <span class="badge {{ $item->status_tayang == 'Ongoing' ? 'bg-success' : 'bg-secondary' }} px-2 py-1">
                                                {{ $item->status_tayang }}
                                            </span>
                                        </td>
                                        <td class="text-truncate">
                                            @foreach($item->genres as $g)
                                                <span class="text-info" style="font-size: 0.75rem;">#{{ $g->nama_genre }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column align-items-center gap-1 px-3">
                                                <a href="{{ route('admin.media.edit', $item->media_id) }}" class="btn btn-outline-info btn-sm w-50 py-1" style="font-size: 0.7rem;">
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.media.destroy', $item->media_id) }}" method="POST" class="w-100 m-0"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus media ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm w-50 py-1" style="font-size: 0.7rem;">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center py-4 text-secondary">Belum ada media.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>