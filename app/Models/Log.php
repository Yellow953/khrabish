<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Filter
    public function scopeFilter($q)
    {
        if (request('text')) {
            $text = request('text');
            $q->where('text', 'LIKE', "%{$text}%");
        }

        if (request('startDate') || request('endDate')) {
            $startDate = request()->query('startDate') ?? Carbon::today();
            $endDate = request()->query('endDate') ?? Carbon::today()->addYears(100);
            $q->whereBetween('created_at', [$startDate, $endDate]);
        }

        if (request('period')) {
            switch (request('period')) {
                case 'today':
                    $start = Carbon::today()->startOfDay();
                    $end = Carbon::today()->endOfDay();
                    $q->whereBetween('created_at', [$start, $end]);
                    break;
                case 'week':
                    $start = Carbon::now()->startOfWeek();
                    $end = Carbon::now()->endOfWeek();
                    $q->whereBetween('created_at', [$start, $end]);
                    break;
                case 'month':
                    $start = Carbon::now()->startOfMonth();
                    $end = Carbon::now()->endOfMonth();
                    $q->whereBetween('created_at', [$start, $end]);
                    break;
                case 'year':
                    $start = Carbon::now()->startOfYear();
                    $end = Carbon::now()->endOfYear();
                    $q->whereBetween('created_at', [$start, $end]);
                    break;
            }
        }

        return $q;
    }
}
