<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class AffiliatorTask extends Model {
    use HasFactory;
    protected $table = 'affiliatortask';
    protected $primaryKey = 'id';
    public function addedBy() {
        return $this->belongsTo(User::class, 'user_id','id');
    }
    public function assigned() {
        return $this->belongsTo(User::class, 'user_by','id');
    }
    public function affiliator() {
        return $this->belongsTo(Affiliator::class, 'affliator_id','id');
    }
    protected $fillable = [
        'type',
        'subtype',
        'completion_date',
        'location',
        'file',
        'note',
        'ntype',
        'deadline',
        'user_id',
        'status',
        'affliator_id',
    ];
}
