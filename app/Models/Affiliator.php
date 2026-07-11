<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;       // =========================
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;      // =========================
use Illuminate\Notifications\Notifiable;                     // =========================

class Affiliator extends Model //Authenticatable                     // =========================
{

    use HasFactory, Notifiable;                              // =========================
    // protected $guard = 'affiliator';                         // =========================

    protected $table = 'affiliators';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'email',
        'business',
        'phone',
        // 'password',                                         // =========================
        // 'is_login',                                         //=====================
        'telephone',
        'telephone1',
        'gender',
        'address',
        'user_id',
        'cnicb',
        'cnicf',
        'cnic',
        'offinspic',
        'offoutspic',
        'offboardpic',
        'offvisitpic',
        'type',
        'status'
    ];
    public function assigned() {
        return $this->belongsTo(\App\Models\User::class, 'user_id','id');
    }
    public function leads_count() {
        return $this->hasMany(\App\Models\Leads::class, 'afflilate_id','id');
    }
    public function last_lead()
    {
        return $this->hasOne(Leads::class, 'afflilate_id','id')->latest();
    }
    public function lastlead() {
        return $this->hasOne(Leads::class, 'afflilate_id')->latest();
    }
    public function taskStatus() {
        return $this->hasOne(AffiliatorTask::class, 'affliator_id')->latest();
    }
    public function affiliatorNumbers() {
        return $this->hasMany(Number::class, 'client_id');
    }
    public function leadtrash()
    {
        return $this->hasMany(Leads::class, 'afflilate_id'); // Ensure this matches your foreign key
    }
}
