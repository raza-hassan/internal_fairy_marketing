<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferLead extends Model
{

    protected $table = 'transfer_leads';

    public $timestamps = false;


    protected $fillable =
    [
        'lead_id',
        'from_user',
        'to_user',
        'transfer_by',
        'status',
        'note',

    ];


    use HasFactory;
}
