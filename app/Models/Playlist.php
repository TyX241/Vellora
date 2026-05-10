<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    protected $primaryKey = 'playlist_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'nama_playlist', 'deskripsi'
    ];

    public function user() { return $this->belongsTo(User::class, 'user_id'); }
    public function items() { return $this->hasMany(PlaylistItem::class, 'playlist_id'); }
}
