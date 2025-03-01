<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CategoriesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Category::all()->map(function ($category) {
            return [
                'name' => $category->name,
                'description' => $category->description,
                'created_at' => $category->created_at,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Name',
            'Description',
            'Created At',
        ];
    }
}
