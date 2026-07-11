<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    use HasFactory;
    
//    public function parent(){
//        return $this->belongsTo(\App\Models\Attributes::class, 'attrid');
//    }
    protected $table = 'variation';
    
    public function attribute()
    {
        return $this->belongsTo(\App\Models\Attributes::class, 'attributes_id');
    }
    
    public function product(){
        return $this->belongsTo(\App\Models\Attributes::class);
    }
    
    protected $fillable = [
        'title',
        'attributes_id',
        'sku',
        'stock',
        'price',
    ];
}
