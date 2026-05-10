<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlaylistItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'playlist_id', 'media_id'
    ];

    public function playlist() { return $this->belongsTo(Playlist::class, 'playlist_id'); }
    public function media() { return $this->belongsTo(Media::class, 'media_id'); }
}
