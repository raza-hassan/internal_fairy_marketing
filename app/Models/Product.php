<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'product';
    protected $primaryKey = 'id';

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
        'hold_expiary',
        'held_at',
        'hold_warning_sent_at',
        'hold_status',
        'sold_by',
        'discount',
        'corner',
        'corner_amt'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
    public function projectname()
    {
        return $this->hasOne(Project::class, 'id', 'project_id');
    }
    public function lead()
    {
        return $this->hasOne(Leads::class, 'id', 'lead_id');
    }
    public function holded_by()
    {
        return $this->hasOne(User::class, 'id', 'hold_by');
    }
    public function statusHistory()
    {
        return $this->hasMany(ProductStatusHistory::class, 'product_id', 'id')->latest('created_at');
    }

    /**
     * Single entry point for changing a unit's status so hold-tracking columns
     * (held_at, hold_warning_sent_at) and product_status_history stay correct
     * no matter which controller/command triggers the change.
     */
    public function changeStatus(string $newStatus, array $attributes = [], $changedBy = null, ?string $note = null): self
    {
        $previousStatus = $this->status;

        $this->status = $newStatus;
        foreach ($attributes as $key => $value) {
            $this->{$key} = $value;
        }

        if ($newStatus === 'Hold') {
            $this->held_at = now();
            $this->hold_warning_sent_at = null;
        } elseif ($previousStatus === 'Hold') {
            $this->held_at = null;
            $this->hold_warning_sent_at = null;
        }

        $this->save();

        ProductStatusHistory::create([
            'product_id' => $this->id,
            'from_status' => $previousStatus,
            'to_status' => $newStatus,
            'changed_by' => $changedBy,
            'note' => $note,
            'created_at' => now(),
        ]);

        return $this;
    }
    public function leads()
    {
        return $this->belongsTo(Leads::class, 'lead_id', 'id');
    }
    public function varify()
    {
        return $this->hasOne(User::class, 'id', 'approved_by');
    }
}
