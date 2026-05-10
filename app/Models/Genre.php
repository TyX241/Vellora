<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $primaryKey = 'genre_id';
    protected $fillable = ['nama_genre'];

    public function media() { 
        return $this->belongsToMany(Media::class, 'media_genres', 'genre_id', 'media_id'); 
    }
}
