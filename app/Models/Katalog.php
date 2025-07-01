<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Katalog extends Model
{
    use HasFactory;

    protected $fillable = [
        'sub_sektor_id',
        'title',
        'slug',
        'produk',
        'harga',      
        'content',
        'no_hp',      
        'instagram',  
        'shopee',     
        'tokopedia', 
        'lazada',    
    ];

    public function subSektor()
    {
        return $this->belongsTo(SubSektor::class);
    }
}
