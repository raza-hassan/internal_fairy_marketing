<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taskmeta extends Model {

    use HasFactory;

    protected $table = 'taskmeta';
    protected $primaryKey = 'id';
   public $timestamps = false;
    
    public function assigned() {
        return $this->belongsTo(\App\Models\Task::class, 'task_id','id');
    }

    protected $fillable = [
        'task_value',
        'task_key',
        'task_id'
    ];

}
