<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Match Column
    |--------------------------------------------------------------------------
    */
    'match' => [
        'file_column' => 'unitno',   // "Unit No" normalized
        'db_column'   => 'unitid',
    ],

    'status_column'           => 'status',
    'status_value_for_update' => 'Available',

    /*
    |--------------------------------------------------------------------------
    | Required File Columns
    |--------------------------------------------------------------------------
    | Bas yehi 4 chahiye — baqi sab (Amount, Total, Down, Remaining,
    | Quarterly, Possession) hamesha calculate hoti hain, file se nahi uthate.
    |
    | Value = file header normalize hone k baad ka naam
    |         (lowercase + sirf a-z0-9 rakha jata hai)
    |         e.g. "Price P/Sft" -> "pricepsft"
    */
    'columns' => [
        'unitid'    => 'unit_no',
        'carea'     => 'covered_area',
        'psft'      => 'psft',
        'corner'    => 'corner',   // raw amount jo file me likha hai, khali = non-corner
    ],

    /*
    |--------------------------------------------------------------------------
    | Import Mode
    |--------------------------------------------------------------------------
    | 'calculate' => sirf upar wale 4 columns chahiye, baqi calculate hoti hain
    | 'file'      => neeche di gayi extra columns bhi file me hona zaroori hai,
    |                sab values seedha unhi se uthai jati hain (koi calculation nahi)
    |
    | Form pe user har import k waqt dono me se ek choose karta hai — yeh sirf
    | tab use hoti hai jab form kisi wajah se value_source na bheje.
    */
    'default_value_source' => 'calculate',

    /*
    | "File se exact values" mode k liye extra file columns — inka koi
    | calculation nahi hoti, seedha DB me chali jati hain.
    */
    'file_value_columns' => [
        'amount'                => 'amount',
        'price'                 => 'total_amount',
        'dpayment'              => 'down_payment',
        'rpayment'              => 'remaining_amount',
        'qtrinstallment'        => 'quarterly_installment',
        'posamount'             => 'possession_amount',
    ],

    /*
    |--------------------------------------------------------------------------
    | Hardcoded Fallback Defaults
    |--------------------------------------------------------------------------
    | Resolution order (har setting k liye alag se):
    |   1) Import form ka "Advanced Settings" field (is import k liye hi)
    |   2) product_import_settings table (DB-wide default)
    |   3) Yeh hardcoded defaults
    |
    | corner_amount / corner_percent dono null hi rehne dein — jab tak koi
    | override na ho, har row k Corner premium k liye file ki apni raw
    | value use hogi (kyunke corner amount project-to-project alag ho sakta hai).
    */
    'defaults' => [
        'down_payment_percent'         => 0.25, // 25%
        'possession_percent'           => 0.10, // 10%
        'monthly_installments_count' => 60,
        'quarterly_installments_count' => 20,   // 
        'corner_amount'                => null,
        'corner_percent'               => null,
    ],

];
