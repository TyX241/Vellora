<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id'; 

    protected $fillable = [
        'username', 'email', 'password', 'foto_profile', 'role',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function watchlists() { return $this->hasMany(Watchlist::class, 'user_id'); }
    public function reviews() { return $this->hasMany(Review::class, 'user_id'); }
    public function playlists() { return $this->hasMany(Playlist::class, 'user_id'); }
}
