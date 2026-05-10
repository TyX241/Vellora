<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Watchlist extends Model
{
    protected $primaryKey = 'watchlist_id';
    public $timestamps = false; 

    protected $fillable = [
        'user_id', 'media_id', 'status', 'progres_episode', 'progres_waktu'
    ];

    public function user() { return $this->belongsTo(User::class, 'user_id'); }
    public function media() { return $this->belongsTo(Media::class, 'media_id'); }
}
