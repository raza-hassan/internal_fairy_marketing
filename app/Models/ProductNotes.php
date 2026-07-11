<?php

namespace App\Models;
use App\Observers\LeadStatusObserver;
use Illuminate\Database\Eloquent\Model;

class ProductNotes extends Model {

protected $table = 'product_notes';

public $timestamps = false;

    protected $fillable = [

        'note',
        'status',
        'item_id',
        'added_by',
    ];











    public function leads() {


        return $this->hasMany(Lead::class, 'lead_id');


    }


    public function products() {


        return $this->hasOne(Product::class, 'id','item_id');


    }





}


