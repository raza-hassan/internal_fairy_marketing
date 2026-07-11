<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageMeta extends Model
{
    use HasFactory;
    
    protected $table = 'page_meta';
    
    public function metapage(){
        return $this->belongsTo(\App\Models\Page::class);
    }
    
    protected $fillable = [
        'meta_key',
        'meta_value',
        'page_id '
    ];
}
