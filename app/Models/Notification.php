<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;



    protected $table = 'notifications';

    protected $fillable = [

        'type',
        'created_by',
        'msg_body',
        'today',

        // 'read_at',
        // 'read_by_user',
        // 'read_by_manger',

        // 'created_at',
        // 'updated_at',
    ];


    public function showTo()
    {
        return $this->belongsTo(User::class, 'show_to','id');
    }




}
