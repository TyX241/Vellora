<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $primaryKey = 'media_id';

    protected $fillable = [
        'judul', 'format_tayangan', 'negara_asal', 'is_animation', 
        'deskripsi', 'poster_url', 'tanggal_rilis', 'status_tayang', 'total_episode'
    ];

    public function genres() { 
        return $this->belongsToMany(Genre::class, 'media_genres', 'media_id', 'genre_id'); 
    }
    public function reviews() { return $this->hasMany(Review::class, 'media_id'); }
}
