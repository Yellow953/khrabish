<?php

namespace App\Exports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Report::all()->map(function ($report) {
            return [
                'date' => $report->date,
                'start_cash' => $report->start_cash,
                'end_cash' => $report->end_cash,
                'currency' => $report->currency->code,
                'created_at' => $report->created_at,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Date',
            'Start Cash',
            'End Cash',
            'Currency',
            'Created At',
        ];
    }
}
