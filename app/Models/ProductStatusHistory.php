<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStatusHistory extends Model
{
    protected $table = 'product_status_history';

    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'from_status',
        'to_status',
        'changed_by',
        'note',
        'created_at',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function changedBy()
    {
        return $this->hasOne(User::class, 'id', 'changed_by');
    }
}
