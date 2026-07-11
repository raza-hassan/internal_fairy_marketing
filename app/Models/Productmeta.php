<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productmeta extends Model
{
    use HasFactory;
    
    protected $table = 'product_meta';
    
    public function metaproduct(){
        return $this->belongsTo(\App\Models\Product::class);
    }
    
    protected $fillable = [
        'meta_key',
        'meta_value',
        'frontend',
        'product_id '
    ];
}
