<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InventoryExport implements FromCollection, WithHeadings

{
    use Exportable;
    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }


    public function collection()
    {
        // return $this->products; // //  It Return All Data

        return $this->products->map(function ($product) {
            return
            [
                $product->id,
                $product->unitid,
                $product->name,
                $product->carea,
                $product->garea,
                $product->dpayment,
                $product->rpayment,
                $product->qtrinstallment,
                $product->numinstallment,
                $product->posamount,
                $product->moninstallment,
                $product->nummoninstallment,
                $product->posperamount,
                $product->dpaymentper,
                $product->corner,
                $product->corner_amt,
                $product->type,
                $product->subtype,
                $product->building,
                $product->description,
                $product->price,
                $product->psft,
                $product->stock,
                $product->status,
                $product->project_id,
                $product->category_id,
                $product->hold_by,
                $product->hold_expiary,
                $product->hold_status,
                $product->sold_by,
                $product->sold_at,
                $product->is_approved,
                $product->approved_by,
                $product->discount,
                $product->created_at,
                $product->updated_at,
                // $product->created_at->format('Y-m-d h:m:s'),
                // $product->updated_at->format('Y-m-d h:m:s'),
            ];
        });
    }


    public function headings(): array
    {
        return
        [
            'ID' ,
            'Unit ID',
            'Name' ,
            'C Area',
            'Gross Area',
            'Down Payment',
            'Remain Payment',
            'Quarterly Installment',
            'No of Installments',
            'Possession Amount',
            'Monthly Installment',
            'No of Monthly Installments',
            'Pos Per Amount',
            'D Payment Per',
            'corner',
            'corner Amount',
            'Type',
            'Sub Type',
            'Building',
            'Description',
            'Price',
            'Per sqft',
            'Stock',
            'Status',
            'Project ID',
            'Category ID',
            'Hold By',
            'Hold Expiary',
            'Hold Status',
            'Sold By',
            'Sold At',
            'Is Approved',
            'Approved By',
            'Discount',
            'Created At',
            'Updated At',
        ];
    }

}

