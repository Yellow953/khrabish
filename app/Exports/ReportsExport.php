<?php

namespace App\Exports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReportsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        return Report::with('currency', 'user')->filter()->get();
    }

    public function headings(): array
    {
        return [
            'User',
            'Start Datetime',
            'End Datetime',
            'Total Sales',
            'Total Tax',
            'Total Discounts',
            'Cash Amount',
            'Transaction Count',
            'Currency',
            'Created At',
        ];
    }

    public function map($row): array
    {
        return [
            $row->user->name,
            $row->start_datetime,
            $row->end_datetime,
            $row->total_sales,
            $row->total_tax,
            $row->total_discounts,
            $row->cash_amount,
            $row->transaction_count,
            $row->currency->code,
            $row->created_at,
        ];
    }
}
