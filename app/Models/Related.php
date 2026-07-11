<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Related extends Model
{
    use HasFactory;
    protected $table = 'product_related'; 
    
    
    public function relatedproducts() {
        return $this->belongsToMany(Product::class);
    }
    
    protected $fillable = [
        'related_id',
        'product_id'
    ];
}
