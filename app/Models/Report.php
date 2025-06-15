<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function items()
    {
        return $this->hasMany(ReportItem::class);
    }

    public function can_delete()
    {
        return auth()->user()->role == 'admin';
    }

    // Filter
    public function scopeFilter($q)
    {
        if (request('id')) {
            $id = request('id');
            $q->where('id', $id);
        }
        if (request('user_id')) {
            $user_id = request('user_id');
            $q->where('user_id', $user_id);
        }
        if (request('date_from') || request('date_to')) {
            $date_from = request()->query('date_from') ?? Carbon::today();
            $date_to = request()->query('date_to') ?? Carbon::today()->addYears(100);
            $q->whereBetween('created_at', [$date_from, $date_to]);
        }

        return $q;
    }
}
