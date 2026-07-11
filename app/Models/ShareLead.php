<?php

namespace App\Models;

use App\Observers\LeadSourceObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ShareLead extends Model
{
    protected $table = 'share_leads';
    


    public $timestamps = false;


    protected $fillable =
    [
        'lead_id',
        'from_user',
        'to_user',
        'shared_by',
        'status',
        'note',

    ];


    protected $guarded = ['id'];

    public function leads()
    {
        return $this->hasMany(Lead::class, 'lead_id');
    }

}
