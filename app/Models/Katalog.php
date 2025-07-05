<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\CloudinaryService;

class Katalog extends Model
{
    use HasFactory;

    protected $table = 'catalogs';

    protected $fillable = [
        'sub_sector_id',
        'title',
        'slug',
        'image',
        'cloudinary_id',
        'cloudinary_meta',
        'image_meta',
        'product_name',
        'price',      
        'content',
        'contact',
        'phone_number',      
        'email',
        'instagram',  
        'shopee',     
        'tokopedia', 
        'lazada',    
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'image_meta' => 'array',
        'cloudinary_meta' => 'array',
    ];

    public function subSektor()
    {
        return $this->belongsTo(SubSektor::class, 'sub_sector_id');
    }

    /**
     * Many-to-Many relationship with Product
     * Satu katalog bisa memiliki banyak produk, 
     * dan satu produk bisa ditampilkan di banyak katalog
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'catalog_product', 'catalog_id', 'product_id')
                    ->withTimestamps()
                    ->withPivot(['sort_order', 'is_featured'])
                    ->orderBy('sort_order');
    }

    /**
     * Get image URL with fallback
     */
    public function getImageUrlAttribute(): string
    {
        // If we have a Cloudinary ID, use it
        if (!empty($this->cloudinary_id)) {
            $cloudinaryService = app(CloudinaryService::class);
            $cloudinaryUrl = $cloudinaryService->getThumbnailUrl($this->cloudinary_id, 800, 600);
            
            if ($cloudinaryUrl) {
                return $cloudinaryUrl;
            }
        }

        // Fallback to local storage if image exists
        if (!empty($this->image) && file_exists(public_path('storage/' . $this->image))) {
            return asset('storage/' . $this->image);
        }

        // Final fallback to placeholder
        return asset('assets/img/placeholder-catalog.svg');
    }

    /**
     * Get optimized image for different sizes
     */
    public function getImageUrl(int $width = 400, int $height = 300): string
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
