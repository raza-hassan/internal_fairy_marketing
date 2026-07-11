<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    use HasFactory;
    protected $table = 'orderaddress'; 
    
    protected $fillable = [
        'order_id',
        'address1',
        'address2',
        'city',
        'country',
        'zipcode',
        'state',
        'telephone1',
        'message',
    ];
}
