<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'artikel_id'
    ];

    public function artikel(){
        return $this->belongsTo(Artikel::class);
    }
}
