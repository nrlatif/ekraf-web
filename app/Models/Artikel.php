<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'thumbnail',
        'content',
        'is_featured',
        'author_id',
        'artikel_kategori_id'
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function artikelKategori()
    {
        return $this->belongsTo(ArtikelKategori::class, 'artikel_kategori_id');
    }

    public function banner()
    {
        return $this->hasOne(Banner::class);
    }
}
