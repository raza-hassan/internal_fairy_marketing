<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model {

    use HasFactory;

    protected $table = 'user_meta';

    public function users() {
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'bname',
        'tname',
        'eyestablish',
        'bnature',
        'lentity',
        'selling',
        'bowner',
        'website',
        'vatnumber',
        'regnumber',
        'address1',
        'address2',
        'bcity',
        'bzipcode',
        'bstate',
        'bcountry',
        'saddress1',
        'saddress2',
        'scity',
        'szipcode',
        'sstate',
        'scountry',
        'comment',
        'user_id',
    ];

}
