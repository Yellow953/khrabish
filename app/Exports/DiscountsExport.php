<?php

namespace App\Exports;

use App\Models\Discount;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DiscountsExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        return Discount::select('code', 'type', 'value', 'description', 'created_at')->filter()->get();
    }

    public function headings(): array
    {
        return [
            'Type',
            'Code',
            'Value',
            'Description',
            'Created At',
        ];
    }
}
