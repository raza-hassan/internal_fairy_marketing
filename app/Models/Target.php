<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Target extends Model {
   use HasFactory;
   protected $table = 'targets';


   protected $guarded = [];


    public function user_name()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id','id');
    }
}

