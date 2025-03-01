<?php

namespace App\Exports;

use App\Models\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LogsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Log::all()->map(function ($log) {
            return [
                'log' => $log->text,
                'created_at' => $log->created_at,
            ];
        });
    }


    public function headings(): array
    {
        return [
            'Log',
            'Created At',
        ];
    }
}
