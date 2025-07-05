<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\CloudinaryService;

class Product extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'owner_name',
        'name',
        'description',
        'price',
        'stock',
        'image',
        'cloudinary_id',
        'cloudinary_meta',
        'image_meta',
        'phone_number',
        'uploaded_at',
        'user_id',
        'business_category_id',
        'status'
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'price' => 'decimal:2',
        'image_meta' => 'array',
        'cloudinary_meta' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function businessCategory()
    {
        return $this->belongsTo(BusinessCategory::class, 'business_category_id');
    }

    public function onlineStoreLinks()
    {
        return $this->hasMany(OnlineStoreLink::class, 'product_id');
    }

    /**
     * Many-to-Many relationship with Katalog
     * Satu produk bisa ditampilkan di banyak katalog,
     * dan satu katalog bisa memiliki banyak produk
     */
    public function katalogs()
    {
        return $this->belongsToMany(Katalog::class, 'catalog_product', 'product_id', 'catalog_id')
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
            $cloudinaryUrl = $cloudinaryService->getThumbnailUrl($this->cloudinary_id, 500, 500);
            
            if ($cloudinaryUrl) {
                return $cloudinaryUrl;
            }
        }

        // Fallback to local storage if image exists
        if (!empty($this->image) && file_exists(public_path('storage/' . $this->image))) {
            return asset('storage/' . $this->image);
        }

        // Final fallback to placeholder
        return asset('assets/img/placeholder-product.svg');
    }

    /**
     * Get optimized image for different sizes
     */
    public function getImageUrl(int $width = 300, int $height = 300): string
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
