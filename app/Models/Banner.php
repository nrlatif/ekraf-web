<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'image',
        'artikel_id',
        'link_url',
        'is_active',
        'sort_order'
    ];

    public function artikel(){
        return $this->belongsTo(Artikel::class);
    }
}
