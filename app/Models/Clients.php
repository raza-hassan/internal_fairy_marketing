<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Clients extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'telephone',
        'telephone1',
        'gender',
        'address',
        'user_id',
        'cnicb',
        'cnicf',
        'cnic',
        'status',
        'source_id',
        'afflilate_id',
        'office_id' ,
        'is_delete' ,
        'note',

    ];
    public function assigned() {
        return $this->belongsTo(\App\Models\User::class, 'user_id','id');
    }
    public function leads_count() {
        return $this->hasMany(\App\Models\Leads::class, 'client_id','id');
    }
    public function leadSource() {
        return $this->belongsTo(LeadSource::class, 'source_id');
    }
    public function leadAffiliator() {
        return $this->belongsTo(Affiliator::class, 'afflilate_id');
    }
    public function clientNumbers() {
        return $this->hasMany(Number::class, 'client_id');
    }
    public function leadtrash()
    {
        return $this->hasMany(Leads::class, 'client_id'); // Ensure this matches your foreign key
    }
}
