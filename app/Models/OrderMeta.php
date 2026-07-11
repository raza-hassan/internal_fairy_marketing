<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderMeta extends Model
{
    use HasFactory;
    protected $table = 'order_meta'; 
    public function order()
    {
        return $this->hasOne('App\Models\Order');
    }
    
    protected $fillable = [
        'order_id',
        'product_id',
        'title',
        'variations',
        'amount',
        'qty',
        'sku',
        'discount',
    ];
}
