<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    // Tambahkan baris ini:
    protected $table = 'characters';
    protected $primaryKey = 'character_id';

    // Tambahkan ini jika kamu ingin Laravel tahu ini adalah integer
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['media_id', 'actor_id', 'nama_karakter', 'peran'];

    public function actor()
    {
        return $this->belongsTo(Actor::class, 'actor_id', 'actor_id');
    }

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id', 'media_id');
    }
}