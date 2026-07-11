<?php


namespace App\Models;


use App\Observers\LeadSourceObserver;


use Illuminate\Database\Eloquent\Model;


use Illuminate\Notifications\Notifiable;





class Designations extends Model


{


    protected $table = 'designation';





    public $timestamps = false;











    protected $guarded = ['id'];





    public function desgination()
    {
        return $this->hasOne(\App\Models\User::class, 'role');
    }

}


