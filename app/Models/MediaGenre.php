<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class MediaGenre extends Pivot
{
    protected $table = 'media_genres';
    
    public $timestamps = false; 
}