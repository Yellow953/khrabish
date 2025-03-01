<?php

namespace App\Exports;

use App\Models\Currency;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CurrenciesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Currency::all()->map(function ($currency) {
            return [
                'code' => $currency->code,
                'name' => $currency->name,
                'symbol' => $currency->symbol,
                'rate' => $currency->rate,
                'created_at' => $currency->created_at,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Code',
            'Name',
            'Symbol',
            'Rate',
            'Created At',
        ];
    }
}
