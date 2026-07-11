<?php

namespace App\Models;

use App\Observers\LeadSourceObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Departments extends Model
{
    protected $table = 'department';

    protected $guarded = ['id'];

    public $timestamps = false;



    public function department()
    {
        return $this->hasOne(User::class, 'department_id');
    }


}
