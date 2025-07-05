<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\CloudinaryService;

class Author extends Model
{
    protected $fillable = [
        'name',
        'username',
        'avatar',
        'cloudinary_id',
        'cloudinary_meta',
        'avatar_meta',
        'bio'
    ];

    protected $casts = [
        'avatar_meta' => 'array',
        'cloudinary_meta' => 'array',
    ];

    public function artikel(){
        return $this->hasMany(Artikel::class, 'author_id');
    }

    /**
     * Get avatar URL with fallback
     */
    public function getAvatarUrlAttribute(): string
    {
        // If we have a Cloudinary ID, use it
        if (!empty($this->cloudinary_id)) {
            $cloudinaryService = app(CloudinaryService::class);
            $cloudinaryUrl = $cloudinaryService->getThumbnailUrl($this->cloudinary_id, 200, 200);
            
            if ($cloudinaryUrl) {
                return $cloudinaryUrl;
            }
        }

        // Fallback to local storage if avatar exists
        if (!empty($this->avatar)) {
            $localPath = public_path('storage/' . $this->avatar);
            if (file_exists($localPath)) {
                return asset('storage/' . $this->avatar);
            }
        }

        // Final fallback to default avatar
        return asset('assets/img/default-avatar.svg');
    }

    /**
     * Get optimized avatar for different sizes
     */
    public function getAvatarUrl(int $width = 200, int $height = 200): string
    {
        if (!empty($this->cloudinary_id)) {
            $cloudinaryService = app(CloudinaryService::class);
            $cloudinaryUrl = $cloudinaryService->getThumbnailUrl($this->cloudinary_id, $width, $height);
            
            if ($cloudinaryUrl) {
                return $cloudinaryUrl;
            }
        }

        return $this->avatar_url;
    }
}
