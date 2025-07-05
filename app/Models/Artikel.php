<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\CloudinaryService;

class Artikel extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'thumbnail',
        'cloudinary_id',
        'cloudinary_meta',
        'thumbnail_meta',
        'content',
        'is_featured',
        'author_id',
        'artikel_kategori_id'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'thumbnail_meta' => 'array',
        'cloudinary_meta' => 'array',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function artikelKategori()
    {
        return $this->belongsTo(ArtikelKategori::class, 'artikel_kategori_id');
    }

    // Relasi ke banner (one-to-many, satu artikel bisa muncul di beberapa banner)
    public function banners()
    {
        return $this->hasMany(Banner::class);
    }

    // Scope untuk artikel featured
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Scope untuk artikel berdasarkan kategori
    public function scopeByKategori($query, $kategoriId)
    {
        return $query->where('artikel_kategori_id', $kategoriId);
    }

    /**
     * Get thumbnail URL with fallback
     */
    public function getThumbnailUrlAttribute(): string
    {
        // If we have a Cloudinary ID, use it
        if (!empty($this->cloudinary_id)) {
            $cloudinaryService = app(CloudinaryService::class);
            $cloudinaryUrl = $cloudinaryService->getThumbnailUrl($this->cloudinary_id, 800, 450);
            
            if ($cloudinaryUrl) {
                return $cloudinaryUrl;
            }
        }

        // Fallback to local storage if thumbnail exists
        if (!empty($this->thumbnail) && file_exists(public_path('storage/' . $this->thumbnail))) {
            return asset('storage/' . $this->thumbnail);
        }

        // Final fallback to placeholder
        return asset('assets/img/placeholder-article.svg');
    }

    /**
     * Get optimized thumbnail for different sizes
     */
    public function getThumbnailUrl(int $width = 300, int $height = 200): string
    {
        if (!empty($this->cloudinary_id)) {
            $cloudinaryService = app(CloudinaryService::class);
            $cloudinaryUrl = $cloudinaryService->getThumbnailUrl($this->cloudinary_id, $width, $height);
            
            if ($cloudinaryUrl) {
                return $cloudinaryUrl;
            }
        }

        return $this->thumbnail_url;
    }
}
