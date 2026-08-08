<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliatorShare extends Model
{
    protected $fillable = [
        'affiliator_id',
        'user_id',
        'shared_by',
    ];

    public function affiliator()
    {
        return $this->belongsTo(Affiliator::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sharedByUser()
    {
        return $this->belongsTo(User::class, 'shared_by');
    }
}
