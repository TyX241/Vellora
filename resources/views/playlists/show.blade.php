@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 mb-4 border-bottom border-secondary pb-3 d-flex justify-content-between align-items-end flex-wrap gap-2">
        <div>
            <h2 class="fw-bold text-warning mb-1">📁 {{ $playlist->nama_playlist }}</h2>
            <p class="text-secondary mb-0" style="font-size: 0.95rem;">{{ $playlist->deskripsi ?? 'Koleksi playlist tanpa deskripsi kustom.' }}</p>
        </div>
        <a href="{{ route('playlists.index') }}" class="btn btn-outline-secondary btn-sm fw-bold px-3">
            < Kembali ke Daftar Playlist
        </a>
    </div>

    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success bg-success text-white border-0 py-2 mb-3">{{ session('success') }}</div>
        @endif
    </div>

    <div class="col-12">
        <div class="card bg-dark border-secondary">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0 align-middle text-center" style="table-layout: fixed; width: 100%;">
                        <thead class="align-middle">
                            <tr class="text-secondary border-bottom border-secondary">
                                <th style="width: 15%;" class="py-3 px-2">Poster</th>
                                <th style="width: 40%;" class="text-start py-3">Judul Tayangan</th>
                                <th style="width: 15%;" class="py-3">Format</th>
                                <th style="width: 15%;" class="py-3">Status Tayang</th>
                                <th style="width: 15%;" class="py-3 px-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="align-middle">
                            @forelse($playlist->items as $item)
                                <tr>
                                    <td class="px-2">
                                        <div class="d-flex justify-content-center">
                                            <img src="{{ $item->media->poster_url ?? 'https://via.placeholder.com/40x60' }}" 
                                                 alt="Poster" class="rounded" 
                                                 style="width: 40px; height: 60px; object-fit: contain; background-color: #1a1a1a;">
                                        </div>
                                    </td>
                                    <td class="text-start align-middle fw-bold">
                                        <a href="/tayangan/{{ $item->media_id }}" class="text-light text-decoration-none hover-warning">
                                            {{ $item->media->judul }}
                                        </a>
                                    </td>
                                    <td>{{ $item->media->format_tayangan }}</td>
                                    <td>
                                        <span class="badge {{ $item->media->status_tayang == 'Ongoing' ? 'bg-success' : 'bg-secondary' }} px-2 py-1" style="font-size: 0.75rem;">
                                            {{ $item->media->status_tayang }}
                                        </span>
                                    </td>
                                    <td class="px-2">
                                        <form action="{{ route('playlists.removeMedia', $item->id) }}" method="POST" class="m-0" onsubmit="return confirm('Keluarkan tayangan ini dari playlist?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm px-3" style="font-size: 0.75rem; py-1;">
                                                Keluarkan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-secondary">
                                        Playlist ini masih kosong. Silakan buka halaman beranda atau lakukan pencarian untuk memasukkan tayangan ke daftar koleksi ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection