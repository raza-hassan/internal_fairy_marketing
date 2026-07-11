<?php

namespace App\Models;
use App\Observers\LeadStatusObserver;
use Illuminate\Database\Eloquent\Model;

class LeadStatus extends Model
{
    protected $fillable = ['type'];
    public $timestamps = false;
    protected $table = 'lead_status';

    public function leads()
    {
        return $this->hasMany(Lead::class, 'status_id')->orderBy('column_priority');
    }

    public function userSetting()
    {
        return $this->hasOne(UserLeadboardSetting::class, 'board_column_id')->where('user_id', user()->id);
    }
}
