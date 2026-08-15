<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackLog extends Model
{
    use HasFactory;

    protected $table = 'feedback_logs';

    protected $fillable = [
        'lead_feedback_id',
        'lead_id',
        'event_name',
        'success',
        'http_status',
        'events_received',
        'response',
        'error_message',
    ];

    protected $casts = [
        'success' => 'boolean',
        'response' => 'array',
    ];

    public function feedback()
    {
        return $this->belongsTo(LeadFeedBack::class, 'lead_feedback_id');
    }
}
