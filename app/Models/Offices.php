<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offices extends Model
{
    use HasFactory;


    protected $table = 'office';

    public $timestamps = false;


    protected $guarded = ['id'];

    public function office()
    {
        return $this->hasOne(\App\Models\User::class, 'office_id');
    }

}
