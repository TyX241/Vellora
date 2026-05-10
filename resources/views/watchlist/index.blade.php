@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <h2 class="fw-bold text-warning">My Watchlist</h2>
        <p class="text-secondary">Kelola daftar tontonan Anda di sini secara terorganisir.</p>

        @if(session('success'))
            <div class="alert alert-success bg-success text-white border-0 py-2">{{ session('success') }}</div>
        @endif
    </div>

    <div class="col-12">
        <div class="card bg-dark border-secondary">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0 align-middle text-center" style="table-layout: fixed; width: 100%;">
                        <thead class="align-middle">
                            <tr class="text-secondary border-bottom border-secondary">
                                <th style="width: 10%;" class="py-3 px-2">Poster</th>
                                <th style="width: 30%;" class="py-3">Judul</th>
                                <th style="width: 12%;" class="py-3">Format</th>
                                <th style="width: 18%;" class="py-3">Status</th>
                                <th style="width: 18%;" class="py-3">Terakhir Diubah</th>
                                <th style="width: 12%;" class="py-3 px-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="align-middle">
                            @forelse($watchlists as $item)
                                <tr>
                                    <td class="px-2">
                                        <div class="d-flex justify-content-center">
                                            <img src="{{ $item->media->poster_url ?? 'https://via.placeholder.com/50x75' }}" 
                                                 alt="Poster" class="rounded" 
                                                 style="width: 45px; height: 65px; object-fit: contain; background-color: #1a1a1a;">
                                        </div>
                                    </td>
                                    
                                    <td class="py-3">
                                        <a href="{{ route('media.show', $item->media_id) }}" class="fw-bold text-light text-decoration-none hover-warning">
                                            {{ $item->media->judul }}
                                        </a>
                                    </td>
                                    
                                    <td>{{ $item->media->format_tayangan }}</td>
                                    
                                    <td>
                                        @php
                                            $badgeClass = 'bg-secondary';
                                            if($item->status == 'Watching') $badgeClass = 'bg-primary';
                                            if($item->status == 'Completed') $badgeClass = 'bg-success';
                                            if($item->status == 'Dropped') $badgeClass = 'bg-danger';
                                        @endphp
                                        <span class="badge {{ $badgeClass }} px-2 py-1" style="font-size: 0.75rem;">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                    
                                    <td>
                                        <small class="text-secondary">
                                            {{ \Carbon\Carbon::parse($item->waktu_diubah)->diffForHumans() }}
                                        </small>
                                    </td>
                                    
                                    <td class="px-2">
                                        <div class="d-flex flex-column align-items-center gap-1">
                                            <button type="button" class="btn btn-outline-info btn-sm px-2 w-50" 
                                                    style="font-size: 0.7rem; py-1;" 
                                                    data-bs-toggle="modal" data-bs-target="#editWatchlist{{ $item->watchlist_id }}">
                                                Edit
                                            </button>
                                            
                                            <form action="{{ route('watchlist.destroy', $item->watchlist_id) }}" method="POST" class="w-100 m-0" onsubmit="return confirm('Hapus dari watchlist?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm px-2 w-50" style="font-size: 0.7rem; py-1;">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>

                                        <div class="modal fade text-start" id="editWatchlist{{ $item->watchlist_id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content bg-dark border-secondary text-light">
                                                    <div class="modal-header border-secondary">
                                                        <h6 class="modal-title fw-bold text-warning">Update Status: {{ $item->media->judul }}</h6>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('watchlist.store') }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <input type="hidden" name="media_id" value="{{ $item->media_id }}">
                                                            <div class="mb-3">
                                                                <label class="form-label small">Status Tontonan</label>
                                                                <select name="status" class="form-select bg-secondary text-light border-0">
                                                                    <option value="Plan to Watch" {{ $item->status == 'Plan to Watch' ? 'selected' : '' }}>Plan to Watch</option>
                                                                    <option value="Watching" {{ $item->status == 'Watching' ? 'selected' : '' }}>Watching</option>
                                                                    <option value="Completed" {{ $item->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                                                    <option value="Dropped" {{ $item->status == 'Dropped' ? 'selected' : '' }}>Dropped</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-secondary">
                                                            <button type="button" class="btn btn-sm btn-outline-light" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-sm btn-warning fw-bold">Simpan Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-secondary">
                                        Watchlist Anda masih kosong. Mulai eksplorasi dan tambahkan tayangan!
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