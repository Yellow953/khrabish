<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Product::all()->map(function ($product) {
            return [
                'category' => $product->category->name,
                'name' => $product->name,
                'quantity' => $product->quantity,
                'cost' => $product->cost,
                'price' => $product->price,
                'description' => $product->description,
                'created_at' => $product->created_at,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Category',
            'Name',
            'Quantity',
            'Cost',
            'Price',
            'Description',
            'Created At',
        ];
    }
}
