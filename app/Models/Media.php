<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $primaryKey = 'media_id';

    protected $fillable = [
        'judul', 'format_tayangan', 'negara_asal', 'is_animation', 
        'deskripsi', 'poster_url', 'tanggal_rilis', 'status_tayang', 'total_episode', 'durasi_per_episode'
    ];

    public function genres() { 
        return $this->belongsToMany(Genre::class, 'media_genres', 'media_id', 'genre_id'); 
    }
    
    public function reviews() { 
        return $this->hasMany(Review::class, 'media_id'); 
    }

    // TAMBAHKAN RELASI INI UNTUK MENGATASI ERROR
    public function watchlists() { 
        return $this->hasMany(Watchlist::class, 'media_id'); 
    }

    public function actors()
    {
        return $this->belongsToMany(Actor::class, 'media_actors', 'media_id', 'actor_id');
    }
    
    public function characters() {
        // Media punya banyak karakter melalui tabel pivot media_characters
        return $this->belongsToMany(Character::class, 'media_characters', 'media_id', 'character_id');
    }
}