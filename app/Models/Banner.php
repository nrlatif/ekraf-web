<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\CloudinaryService;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'image',
        'cloudinary_id',
        'cloudinary_meta',
        'image_meta',
        'link_url',
        'is_active',
        'sort_order',
        'description',
        'artikel_id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'image_meta' => 'array',
        'cloudinary_meta' => 'array',
    ];

    // Relasi ke artikel
    public function artikel()
    {
        return $this->belongsTo(Artikel::class);
    }

    // Scope untuk banner aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk urutan
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    /**
     * Get image URL with fallback
     */
    public function getImageUrlAttribute(): string
    {
        // If we have a Cloudinary ID, use it
        if (!empty($this->cloudinary_id)) {
            $cloudinaryService = app(CloudinaryService::class);
            $cloudinaryUrl = $cloudinaryService->getThumbnailUrl($this->cloudinary_id, 1200, 675);
            
            if ($cloudinaryUrl) {
                return $cloudinaryUrl;
            }
        }

        // Fallback to local storage if image exists
        if (!empty($this->image) && file_exists(public_path('storage/' . $this->image))) {
            return asset('storage/' . $this->image);
        }

        // Final fallback to placeholder
        return asset('assets/img/placeholder-banner.svg');
    }

    /**
     * Get optimized image for different sizes
     */
    public function getImageUrl(int $width = 800, int $height = 400): string
    {
        if (!empty($this->cloudinary_id)) {
            $cloudinaryService = app(CloudinaryService::class);
            $cloudinaryUrl = $cloudinaryService->getThumbnailUrl($this->cloudinary_id, $width, $height);
            
            if ($cloudinaryUrl) {
                return $cloudinaryUrl;
            }
        }

        return $this->image_url;
    }
}
