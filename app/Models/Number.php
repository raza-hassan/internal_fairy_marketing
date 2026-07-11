<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Number extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;


    public function client()
    {
        return $this->belongsTo(\App\Models\Clients::class, 'client_id','id');
    }

    public function affiliator()
    {
        return $this->belongsTo(\App\Models\Affiliator::class, 'client_id','id');
    }
}
