<?php

namespace App\Exports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClientsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Client::all()->map(function ($client) {
            return [
                'name' => $client->name,
                'email' => $client->email,
                'phone' => $client->phone,
                'country' => $client->country,
                'city' => $client->city,
                'address' => $client->address,
                'created_at' => $client->created_at,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Phone',
            'Country',
            'City',
            'Address',
            'Created At',
        ];
    }
}
