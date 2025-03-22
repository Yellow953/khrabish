<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function get_profit()
    {
        return ($this->start_cash && $this->end_cash ? ($this->end_cash - $this->start_cash) * $this->currency->rate : 'N/A');
    }

    // Filter
    public function scopeFilter($q)
    {
        if (request('date_from') || request('date_to')) {
            $date_from = request()->query('date_from') ?? Carbon::today();
            $date_to = request()->query('date_to') ?? Carbon::today()->addYears(100);
            $q->whereBetween('created_at', [$date_from, $date_to]);
        }

        return $q;
    }

    public function can_delete()
    {
        return auth()->user()->role == 'admin';
    }
}
