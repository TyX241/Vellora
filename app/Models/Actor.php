<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
    protected $primaryKey = 'actor_id';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = ['nama_aktor', 'foto_aktor'];

    public function medias()
    {
        return $this->belongsToMany(Media::class, 'media_actors', 'actor_id', 'media_id');
    }
}
