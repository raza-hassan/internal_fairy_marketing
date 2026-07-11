<?php

namespace App\Models;

use App\Enums\LeadFeedBackStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadFeedBack extends Model
{
    use HasFactory;

    protected $table = 'lead_feedbacks';

    protected $fillable = [
        'lead_id',
        'status',
        'remarks',
        'amount',
        'created_by',
        'facebook_synced',
        'facebook_synced_at'
    ];


    protected $casts = [
        'status' => LeadFeedBackStatus::class,
    ];

    public function lead()
    {
        return $this->belongsTo(Leads::class);
    }
}
