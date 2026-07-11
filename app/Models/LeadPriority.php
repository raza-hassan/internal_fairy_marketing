<?php

namespace App\Models;

use App\Observers\LeadSourceObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class LeadPriority extends Model
{
    protected $table = 'lead_priority';


    // protected $fillable = ['name'];

    public $timestamps = false;




    protected $guarded = ['id'];

    public function leads()
    {
        return $this->hasMany(Lead::class, 'priority');
    }

}
