<?php

namespace App\Models;

use App\Observers\LeadCategoryObserver;
use Illuminate\Database\Eloquent\Model;

class LeadCategory extends Model
{
    protected $table = 'lead_category';
    protected $default = ['id','category_name'];
}
