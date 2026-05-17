<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\ReviewController;  
use App\Http\Controllers\PlaylistController;

// Halaman Utama
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [App\Http\Controllers\HomeController::class, 'search'])->name('search');
Route::get('/browse/{type}', [App\Http\Controllers\HomeController::class, 'browse'])->name('browse');
Route::get('/tayangan/{id}', [MediaController::class, 'show'])->name('media.show');

// Route untuk Guest (Belum Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Route untuk Auth (Sudah Login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // Nantinya route profile bisa ditaruh di sini
    // --- TAMBAH ROUTE PROFILE DI SINI ---
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    // ------------------------------------
    Route::get('/watchlist', [WatchlistController::class, 'index'])->name('watchlist.index');
    Route::post('/watchlist', [WatchlistController::class, 'store'])->name('watchlist.store');
    Route::delete('/watchlist/{id}', [WatchlistController::class, 'destroy'])->name('watchlist.destroy');
    Route::post('/review', [ReviewController::class, 'store'])->name('review.store');
    Route::delete('/review/{id}', [ReviewController::class, 'destroy'])->name('review.destroy');

    Route::get('/playlists', [PlaylistController::class, 'index'])->name('playlists.index');
    Route::post('/playlists', [PlaylistController::class, 'store'])->name('playlists.store');
    Route::get('/playlists/{id}', [PlaylistController::class, 'show'])->name('playlists.show');
    Route::delete('/playlists/{id}', [PlaylistController::class, 'destroy'])->name('playlists.destroy');

    Route::post('/playlists/add', [PlaylistController::class, 'addMedia'])->name('playlists.addMedia');
    Route::delete('/playlists/item/{id}', [PlaylistController::class, 'removeMedia'])->name('playlists.removeMedia');
});

 // Panel Admin Sementara
Route::get('/admin/panel', [AdminController::class, 'index'])->name('admin.panel');
Route::post('/admin/genre', [AdminController::class, 'storeGenre'])->name('admin.genre.store');
Route::post('/admin/media', [AdminController::class, 'storeMedia'])->name('admin.media.store');
Route::delete('/admin/media/{id}', [AdminController::class, 'destroyMedia'])->name('admin.media.destroy');
Route::delete('/admin/genre/{id}', [AdminController::class, 'destroyGenre'])->name('admin.genre.destroy');
Route::get('/admin/media/{id}/edit', [AdminController::class, 'editMedia'])->name('admin.media.edit');
Route::put('/admin/media/{id}', [AdminController::class, 'updateMedia'])->name('admin.media.update');
Route::post('/admin/actors', [AdminController::class, 'storeActor'])->name('admin.actors.store');
Route::delete('/admin/actors/{id}', [AdminController::class, 'destroyActor'])->name('admin.actors.destroy');
Route::post('/admin/characters', [AdminController::class, 'storeCharacter'])->name('admin.characters.store');
Route::delete('/admin/characters/{id}', [AdminController::class, 'destroyCharacter'])->name('admin.characters.destroy');