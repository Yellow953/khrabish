<?php

namespace App\Exports;

use App\Models\Debt;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DebtsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Debt::all()->map(function ($debt) {
            return [
                'currency' => $debt->currency->name,
                'supplier' => $debt->supplier->name,
                'client' => $debt->client->name,
                'amount' => $debt->amount,
                'date' => $debt->date,
                'note' => $debt->note,
                'type' => $debt->type,
                'created_at' => $debt->created_at,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Currency ',
            'Supplier ',
            'Client ',
            'Amount',
            'Date',
            'Note',
            'Type',
            'Created At',
        ];
    }
}
