<?php

namespace App\Models;

use App\Observers\LeadStatusObserver;
use Illuminate\Database\Eloquent\Model;

class LeadNotes extends Model {
protected $table = 'lead_notes';
public $timestamps = false;
    protected $fillable = [
        'lead_id',
        'item_id',
        'notes',
        'status',
    ];
    
    

    public function leads() {
        return $this->hasMany(Lead::class, 'lead_id');
    }
    public function products() {
        return $this->hasOne(Product::class, 'id','item_id');
    }

}
