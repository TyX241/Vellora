<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Media - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #0f1115; color: #d1d5db; font-family: monospace; }
        .card { background-color: #1f2229 !important; border: 1px solid #374151 !important; }
        .form-control, .form-select { background-color: #111827 !important; border: 1px solid #374151 !important; color: white !important; }
    </style>
</head>
<body class="py-5">
<div class="container" style="max-width: 800px;">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-warning fw-bold mb-0">Edit Data Media</h2>
        <a href="{{ route('admin.panel') }}" class="text-secondary text-decoration-none">< Kembali</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.media.update', $media->media_id) }}" method="POST">
                @csrf
                @method('PUT') <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label small">Judul Tayangan</label>
                        <input type="text" name="judul" class="form-control form-control-sm" value="{{ $media->judul }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label small">URL Poster</label>
                        <input type="url" name="poster_url" class="form-control form-control-sm" value="{{ $media->poster_url }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label small">Format</label>
                        <select name="format_tayangan" class="form-select form-select-sm">
                            <option value="Film" {{ $media->format_tayangan == 'Film' ? 'selected' : '' }}>Film</option>
                            <option value="Series" {{ $media->format_tayangan == 'Series' ? 'selected' : '' }}>Series</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label small">Status</label>
                        <select name="status_tayang" class="form-select form-select-sm">
                            <option value="Ongoing" {{ $media->status_tayang == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                            <option value="Completed" {{ $media->status_tayang == 'Completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label small">Negara Asal</label>
                        <input type="text" name="negara_asal" class="form-control form-control-sm" value="{{ $media->negara_asal }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label small">Total Episode</label>
                        <input type="number" name="total_episode" class="form-control form-control-sm" value="{{ $media->total_episode }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label small">Tanggal Rilis</label>
                        <input type="date" name="tanggal_rilis" class="form-control form-control-sm" value="{{ $media->tanggal_rilis }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label small">Animasi?</label>
                        <select name="is_animation" class="form-select form-select-sm">
                            <option value="0" {{ $media->is_animation == 0 ? 'selected' : '' }}>Tidak</option>
                            <option value="1" {{ $media->is_animation == 1 ? 'selected' : '' }}>Ya</option>
                        </select>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label small">Deskripsi/Sinopsis</label>
                        <textarea name="deskripsi" rows="6" class="form-control form-control-sm" style="resize: none;">{{ $media->deskripsi }}</textarea>
                    </div>
                    
                    <div class="col-12 mb-4">
                        <label class="form-label small">Pilih Genre</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($genres as $genre)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="genres[]" value="{{ $genre->genre_id }}" id="genre{{ $genre->genre_id }}" 
                                           {{ $media->genres->contains('genre_id', $genre->genre_id) ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="genre{{ $genre->genre_id }}">
                                        {{ $genre->nama_genre }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label text-white small">Pilih Pemeran</label>
                    <div class="bg-dark p-3 rounded border border-secondary" style="max-height: 150px; overflow-y: auto;">
                        @foreach($actors as $actor)
                            <div class="form-check">
                                <!-- Logika 'contains' ini yang membuat checkbox tercentang otomatis -->
                                <input class="form-check-input" type="checkbox" name="actors[]" 
                                    value="{{ $actor->actor_id }}" 
                                    id="actor{{ $actor->actor_id }}"
                                    {{ ($media->actors && $media->actors->contains('actor_id', $actor->actor_id)) ? 'checked' : '' }}>
                                <label class="form-check-label text-light small" for="actor{{ $actor->actor_id }}">
                                    {{ $actor->nama_aktor }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <button type="submit" class="btn btn-info w-100 fw-bold text-dark">Simpan Perubahan Data</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>