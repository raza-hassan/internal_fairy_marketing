<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';

    protected $fillable = [
        'name',
        // 'office_id',
        // 'project_id',
        // 'form_id',

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
