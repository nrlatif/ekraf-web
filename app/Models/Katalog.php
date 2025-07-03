<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Katalog extends Model
{
    use HasFactory;

    protected $table = 'catalogs';

    protected $fillable = [
        'sub_sector_id',
        'title',
        'slug',
        'image',
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
        'price' => 'decimal:2'
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
}
