<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImportSetting extends Model
{
    protected $table = 'product_import_settings';

    protected $fillable = [
        'down_payment_percent',
        'possession_percent',
        'quarterly_installments_count',
        'monthly_installments_count',
        'corner_amount',
        'corner_percent',
    ];

    /**
     * Hamesha ek row wapis karta hai — agar table khali ho (kabhi na
     * kabhi manually clear ho jaye) to naya blank row bana deta hai.
     */
    public static function current(): self
    {
        return static::query()->first() ?? static::create([]);
    }
}
