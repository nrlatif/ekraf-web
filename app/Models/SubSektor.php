<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubSektor extends Model
{
    protected $fillable = [
        'title',
        'slug',
    ];
        public function katalog(){
        return $this->hasMany(Katalog::class);
    }
}
