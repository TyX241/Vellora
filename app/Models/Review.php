<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $primaryKey = 'review_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'media_id', 'rating', 'komentar'
    ];

    public function user() { return $this->belongsTo(User::class, 'user_id'); }
    public function media() { return $this->belongsTo(Media::class, 'media_id'); }

}
