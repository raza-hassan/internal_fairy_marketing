<?php


namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Order extends Model
{

    use HasFactory;
    protected $table = 'orders';

    protected $fillable = [
        'unit_id',
        'user_id',
        'lead_id',
        'status',
        'discount',
        'price',
        'token',
        'token_received',
        'client_id',
        'approved_by',
    ];


    public function client()
    {
        return $this->belongsTo(\App\Models\Clients::class, 'client_id','id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id','id');
    }

    public function approved()
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by','id');
    }


}





