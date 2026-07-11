<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Category extends Model
{
    use HasFactory;
    protected $table = 'category';
    protected $primaryKey = 'id';
    public function modules() {
        return $this->belongsToMany(Module::class);
    }
    public function subcategory() {
        return $this->hasMany(\App\Models\Category::class, 'parent');
    }

     public function children()
    {
        return $this->hasMany('Category','parent');
    }
    public function childs() {
        return $this->hasMany('App\Models\Category', 'parent', 'id');
    }
    public function menuchild() {
        return $this->hasMany('App\Models\Category', 'parent', 'id');
    }
    public function parentname() {
        return $this->belongsTo(\App\Models\Category::class, 'parent');
    }
    public function products() {
        return $this->belongsToMany(Product::class);
    }
    public function asorproducts() {
        return $this->belongsToMany(Product::class)->orderBy('name', 'ASC');
    }
    public function dsorproducts() {
        return $this->belongsToMany(Product::class)->orderBy('name', 'DESC');
    }
    public function pasorproducts() {
        return $this->belongsToMany(Product::class)->orderBy('price', 'ASC');
    }
    public function pdsorproducts() {
        return $this->belongsToMany(Product::class)->orderBy('price', 'DESC');
    }
    public function getproducts() {
        return $this->hasMany(\App\Models\Category::class, 'category_id', 'product_id');
    }
    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent',
        'image'
    ];
}
