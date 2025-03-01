<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankNoteSeeder extends Seeder
{
    public function run(): void
    {
        $bank_notes = [
            ['USD', 'One US Dollar', 1],
            ['USD', 'Twe US Dollar', 2],
            ['USD', 'Five US Dollar', 5],
            ['USD', 'Ten US Dollar', 10],
            ['USD', 'Twenty US Dollar', 20],
            ['USD', 'Fifty US Dollar', 50],
            ['USD', 'One Hunderd US Dollar', 100],
            ['LBP', 'Two Hunderd Fifty LBP', 250],
            ['LBP', 'Five Hunderd LBP', 500],
            ['LBP', 'One Thousand LBP', 1000],
            ['LBP', 'Five Thousand LBP', 5000],
            ['LBP', 'Ten Thousand LBP', 10000],
            ['LBP', 'Twenty Thousand LBP', 20000],
            ['LBP', 'Fifty Thousand LBP', 50000],
            ['LBP', 'One Hundred Thousand LBP', 100000],
            ['LBP', 'Five Hundred Thousand LBP', 500000],
            ['LBP', 'One Million LBP', 1000000],
            ['LBP', 'Five Million LBP', 5000000],
            ['LBP', 'Ten Million LBP', 10000000],
        ];

        foreach ($bank_notes as $bank_note) {
            DB::table('bank_notes')->insert([
                'currency_code' => $bank_note[0],
                'name' => $bank_note[1],
                'value' => $bank_note[2],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
