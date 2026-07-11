<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Task extends Model {

    use HasFactory;
    protected $table = 'task';
    protected $primaryKey = 'id';

    public function leadsTask() {
        return $this->belongsTo(Leads::class, 'lead_id','id');
    }
    public function client() {
        return $this->belongsTo(Clients::class, 'client_id','id');
    }
    public function item() {
        return $this->belongsTo(Product::class, 'item_id','id');
    }
    public function addedBy() {
        return $this->belongsTo(User::class, 'added_by','id');
    }
    public function assigned() {
        return $this->belongsTo(\App\Models\User::class, 'added_by','id');
    }
    protected $fillable = [
        'type',
        'subtype',
        'completion_date',
        // 'completion_date1',
        'priority',
        'file',
        'note',
        'ntype',
        'nsubtype',
        'deadline',
        // 'deadline1',
        'added_by',
        'lead_id',
        'client_id',
        'item_id',
        'status'
    ];
}
