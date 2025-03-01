<?php

namespace App\Exports;

use App\Models\Tax;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TaxesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Tax::all()->map(function ($tax) {
            return [
                'name' => $tax->name,
                'rate' => $tax->rate,
                'created_at' => $tax->created_at,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Name',
            'Rate',
            'Created At',
        ];
    }
}
