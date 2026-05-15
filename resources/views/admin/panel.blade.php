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
        .genre-list::-webkit-scrollbar, .actor-list::-webkit-scrollbar { width: 6px; }
        .genre-list::-webkit-scrollbar-thumb, .actor-list::-webkit-scrollbar-thumb { background-color: #374151; border-radius: 4px; }
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
        <div class="col-md-5 col-lg-4 d-flex flex-column gap-3">
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
                <div class="card-header border-bottom border-secondary d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 text-light fw-bold">Kelola Genre</h6>
                    <form id="formSearchGenre" action="{{ route('admin.panel') }}" method="GET" class="d-flex">
                        <input id="inputSearchGenre" type="text" name="search_genre" class="form-control form-control-sm bg-dark text-light border-secondary" placeholder="Cari..." value="{{ request('search_genre') }}">
                    </form>
                </div>
                <div class="card-body p-0 genre-list" style="max-height: 300px; overflow-y: auto;">
                    <div class="row g-0 p-2">
                        @forelse($genres as $g)
                            <div class="col-12 mb-2 px-1"> 
                                <div class="d-flex justify-content-between align-items-center bg-dark p-2 border border-secondary rounded">
                                    <span class="small text-white">{{ $g->nama_genre }}</span>
                                    <form action="{{ route('admin.genre.destroy', $g->genre_id) }}" method="POST" onsubmit="return confirm('Hapus genre ini?')">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger p-0 fw-bold small text-decoration-none" style="line-height: 1;">
                                            ✕
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center text-secondary small py-3">Tidak ada data genre.</div>
                        @endforelse
                    </div>
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
                            <div class="col-md-6 mb-2">
                                <label class="small">Judul Tayangan</label>
                                <input type="text" name="judul" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="small">URL Poster</label>
                                <input type="url" name="poster_url" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="small">Format</label>
                                <select name="format_tayangan" class="form-select form-select-sm">
                                    <option value="Film">Film</option>
                                    <option value="Series">Series</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="small">Status</label>
                                <select name="status_tayang" class="form-select form-select-sm">
                                    <option value="Ongoing">Ongoing</option>
                                    <option value="Completed">Completed</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="small">Negara</label>
                                <input type="text" name="negara_asal" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="small">Sinopsis</label>
                            <textarea name="deskripsi" rows="3" class="form-control form-control-sm" style="resize: none;"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="small">Pilih Genre</label>
                                <input type="text" id="searchCheckboxGenre" class="form-control form-control-sm mb-1 bg-dark text-white border-secondary" placeholder="Filter genre...">
                                
                                <div class="bg-dark p-2 rounded border border-secondary" style="max-height: 100px; overflow-y: auto;" id="genreCheckboxList">
                                    @forelse($genres_all ?? $genres as $genre)
                                        <div class="form-check small genre-item">
                                            <input class="form-check-input" type="checkbox" name="genres[]" value="{{ $genre->genre_id }}" id="g{{ $genre->genre_id }}">
                                            <label class="form-check-label" for="g{{ $genre->genre_id }}">{{ $genre->nama_genre }}</label>
                                        </div>
                                    @empty
                                        <div class="small text-danger">Belum ada data genre.</div>
                                    @endforelse
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="small">Pilih Pemeran</label>
                                <input type="text" id="searchCheckboxActor" class="form-control form-control-sm mb-1 bg-dark text-white border-secondary" placeholder="Filter pemeran...">
                                
                                <div class="bg-dark p-2 rounded border border-secondary" style="max-height: 100px; overflow-y: auto;" id="actorCheckboxList">
                                    @forelse($actors_all ?? $actors as $actor)
                                        <div class="form-check small actor-item">
                                            <input class="form-check-input" type="checkbox" name="actors[]" value="{{ $actor->actor_id }}" id="a{{ $actor->actor_id }}">
                                            <label class="form-check-label" for="a{{ $actor->actor_id }}">{{ $actor->nama_aktor }}</label>
                                        </div>
                                    @empty
                                        <div class="small text-danger">Belum ada data pemeran.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-warning btn-sm w-100 fw-bold">Tambahkan Media</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-header border-bottom border-secondary">
                    <h6 class="mb-0 text-light fw-bold">Tambah Pemeran</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.actors.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small">Nama Pemeran</label>
                            <input type="text" name="nama_aktor" class="form-control form-control-sm" required>
                        </div>
                        <button type="submit" class="btn btn-warning btn-sm w-100 fw-bold">Simpan Pemeran</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow-sm h-100">
                <div class="card-header border-bottom border-secondary d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 text-light fw-bold">Kelola Pemeran (Hapus)</h6>
                    <form id="formSearchPemeran" action="{{ route('admin.panel') }}" method="GET" class="d-flex w-50">
                        <input id="inputSearchPemeran" type="text" name="actor_search" class="form-control form-control-sm bg-dark text-light border-secondary" placeholder="Cari pemeran..." value="{{ request('actor_search') }}">
                    </form>
                </div>
                <div class="card-body p-0 actor-list" style="max-height: 150px; overflow-y: auto;">
                    <div class="row g-0 p-2">
                        @forelse($actors as $a)
                            <div class="col-md-4 p-1">
                                <div class="d-flex justify-content-between align-items-center bg-dark p-2 border border-secondary rounded">
                                    <span class="small text-truncate" style="max-width: 100px;">{{ $a->nama_aktor }}</span>
                                    <form action="{{ route('admin.actors.destroy', $a->actor_id) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger p-0 fw-bold small text-decoration-none">✕</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center text-secondary small py-3">Tidak ada data pemeran.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header border-bottom border-secondary d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <h6 class="mb-0 text-light fw-bold">Kelola Media</h6>
                        <span class="badge bg-secondary">{{ $allMedia->count() }} Items</span>
                    </div>
                    <form id="formSearchMedia" action="{{ route('admin.panel') }}" method="GET" class="d-flex">
                        <input id="inputSearchMedia" type="text" name="search" class="form-control form-control-sm bg-dark text-light border-secondary me-2" placeholder="Cari Judul..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-outline-warning btn-sm d-none">🔎</button>
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0 align-middle text-center" style="font-size: 0.85rem; table-layout: fixed; width: 100%;">
                            <thead class="align-middle border-bottom border-secondary">
                                <tr class="text-secondary">
                                    <th style="width: 10%;" class="py-3 px-2">Poster</th>
                                    <th style="width: 35%;" class="py-3">Judul</th>
                                    <th style="width: 15%;" class="py-3">Status</th>
                                    <th style="width: 25%;" class="py-3">Genre</th>
                                    <th style="width: 15%;" class="py-3 px-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="align-middle">
                                @forelse($allMedia as $item)
                                    <tr>
                                        <td class="align-middle px-2">
                                            <div class="d-flex justify-content-center align-items-center h-100 py-1">
                                                <img src="{{ $item->poster_url ?? 'https://via.placeholder.com/40x60' }}" class="rounded" style="width: 40px; height: 60px; object-fit: contain; background-color: #1a1a1a;">
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div class="fw-bold text-light">{{ $item->judul }}</div>
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge bg-secondary">{{ $item->status_tayang }}</span>
                                        </td>
                                        <td class="text-truncate align-middle" style="max-width: 150px;">
                                            @foreach($item->genres as $g)
                                                <span class="text-info" style="font-size: 0.7rem;">#{{ $g->nama_genre }}</span>
                                            @endforeach
                                        </td>
                                        <td class="align-middle px-2">
                                            <div class="d-flex flex-column gap-1 justify-content-center align-items-center">
                                                <a href="{{ route('admin.media.edit', $item->media_id) }}" class="btn btn-outline-info btn-sm w-100" style="font-size: 0.75rem; py-1;">Edit</a>
                                                <form action="{{ route('admin.media.destroy', $item->media_id) }}" method="POST" class="w-100 m-0">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm w-100" style="font-size: 0.75rem; py-1;">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="py-4 text-secondary align-middle">Tidak ada data media.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fungsi Filter Universal (Client-side) untuk Checkbox Form Input
    function setupFilter(inputId, listId, itemClass) {
        const input = document.getElementById(inputId);
        const list = document.getElementById(listId);
        const items = list.getElementsByClassName(itemClass);

        if(input && list) {
            input.addEventListener('keyup', function() {
                const filter = input.value.toLowerCase();
                Array.from(items).forEach(function(item) {
                    const text = item.textContent || item.innerText;
                    if (text.toLowerCase().indexOf(filter) > -1) {
                        item.style.display = "";
                    } else {
                        item.style.display = "none";
                    }
                });
            });
        }
    }

    setupFilter('searchCheckboxGenre', 'genreCheckboxList', 'genre-item');
    setupFilter('searchCheckboxActor', 'actorCheckboxList', 'actor-item');
    
    // ==========================================
    // Fungsi Live Search Debounce (Server-side)
    // ==========================================
    function setupLiveSearch(inputId, formId) {
        const input = document.getElementById(inputId);
        const form = document.getElementById(formId);
        let timeout = null;
        
        if (input && form) {
            input.addEventListener('input', function () {
                clearTimeout(timeout);
                
                // SIMPAN ID input yang sedang aktif diketik ke memori browser
                sessionStorage.setItem('lastFocusedInput', inputId);

                // Jeda 500ms setelah selesai mengetik sebelum submit
                timeout = setTimeout(function () {
                    form.submit();
                }, 500); 
            });

            // HANYA fokuskan kursor jika input ini adalah yang terakhir kali diketik
            if (sessionStorage.getItem('lastFocusedInput') === inputId) {
                input.focus();
                // Trik memposisikan kursor di akhir teks agar ketikan tidak terganggu
                let val = input.value;
                input.value = '';
                input.value = val;
            }
        }
    }

    // Terapkan Live Search ke masing-masing input tabel
    setupLiveSearch('inputSearchGenre', 'formSearchGenre');
    setupLiveSearch('inputSearchPemeran', 'formSearchPemeran');
    setupLiveSearch('inputSearchMedia', 'formSearchMedia');
});
</script>
</body>
</html>