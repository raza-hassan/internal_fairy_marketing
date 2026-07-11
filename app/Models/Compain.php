<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compain extends Model
{
    use HasFactory;

    protected $table = 'compains';

    protected $fillable = [
        'compain_name',
        'office_id',
        'project_id',
        'form_id',
        'status',
        'last_synced_at',

    ];


    public function project()
    {
        return $this->belongsTo(\App\Models\Project::class, 'project_id','id');
    }
    public function office()
    {
        return $this->belongsTo(\App\Models\Offices::class, 'office_id','id');
    }



}
