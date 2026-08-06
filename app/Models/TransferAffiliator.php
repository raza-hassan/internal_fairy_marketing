<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferAffiliator extends Model
{
    protected $fillable = [
        'affiliator_id',
        'from_user_id',
        'to_user_id',
        'transferred_by',
        'status',
        'note',
    ];

    public function affiliator()
    {
        return $this->belongsTo(Affiliator::class);
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function transferredBy()
    {
        return $this->belongsTo(User::class, 'transferred_by');
    }
}
