<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facebook extends Model

{
    use HasFactory;
    protected $table = 'facebook_tokens';
    protected $primaryKey = 'id';
    protected $fillable = [

        'client_id',
        'client_secret',
        'page_id',
        'long_lived_token',
        'token_type',
        'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];


}

