<?php

namespace App\Models;

use App\Observers\LeadSourceObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class LeadSource extends Model
{
    protected $table = 'lead_sources';

    protected $guarded = ['id'];

    public function leads()
    {
        return $this->hasMany(Lead::class, 'source_id')->orderBy('column_priority');
    }

}
