<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    protected $fillable = [
        'author_id',
        'artikel_kategori_id',
        'title',
        'slug',
        'thumbnail',
        'content',
        'is_featured'
    ];
    public function author(){
        return $this->belongsTo(Author::class);
    }
    public function artikelkategori()
    {
    return $this->belongsTo(ArtikelKategori::class, 'artikel_kategori_id');
    }

    public function banner(){
        return $this->hasOne(Banner::class);
    }
    
}
