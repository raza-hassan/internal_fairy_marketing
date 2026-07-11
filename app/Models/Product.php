<?php







namespace App\Models;







use Illuminate\Database\Eloquent\Factories\HasFactory;



use Illuminate\Database\Eloquent\Model;







class Product extends Model {







    use HasFactory;







    protected $table = 'product';



    protected $primaryKey = 'id';







    public function categories() {



        return $this->belongsToMany(Category::class);



    }







    public function category() {



        return $this->hasOne(Category::class, 'id','category_id');



    }







    public function projectname() {



        return $this->hasOne(Project::class, 'id','project_id');



    }







    public function lead() {



        return $this->hasOne(Leads::class, 'id','lead_id');



    }



    public function holded_by() {



        return $this->hasOne(User::class, 'id','hold_by');



    }



    public function leads() {



        return $this->belongsTo(Leads::class,'lead_id', 'id');



    }



    public function varify() {
        return $this->hasOne(User::class, 'id','approved_by');
    }



    protected $fillable = [



        'name',



        'unitid',



        'carea',



        'garea',



        'floor',



        'sbm',



        'sku',



        'type',



        'subtype',



        'building',



        'dpayment',



        'dpaymentper',



        'posamount',



        'posperamount',



        'rpayment',



        'qtrinstallment',



        'project_id',



        'category_id',



        'price',



        'stock',



        'hold_by',



        'psft',



        'numinstallment',



        'nummoninstallment',



        'moninstallment',



        'description',



        'status',



        'hodl_expiary',



        'hold_status',



        'sold_by',
        'discount',




    ];







}



