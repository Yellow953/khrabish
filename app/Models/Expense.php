<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $guarded = [];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public static function generate_number()
    {
        $last_expense = Expense::orderBy('id', 'DESC')->first();

        if ($last_expense) {
            return (int)$last_expense->number + 1;
        } else {
            return 1;
        }
    }

    // Permissions
    public function can_delete()
    {
        return auth()->user()->role == 'admin';
    }

    // Filter
    public function scopeFilter($q)
    {
        if (request('number')) {
            $number = request('number');
            $q->where('number', $number);
        }
        if (request('date')) {
            $date = request('date');
            $q->whereDate('date', $date);
        }
        if (request('category')) {
            $category = request('category');
            $q->where('category', $category);
        }
        if (request('description')) {
            $description = request('description');
            $q->where('description', 'LIKE', "%{$description}%");
        }

        return $q;
    }
}
