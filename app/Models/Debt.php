<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Permissions
    public function can_delete()
    {
        return auth()->user()->role == "admin";
    }

    // Filter
    public function scopeFilter($q)
    {
        if (request('supplier_id')) {
            $supplier_id = request('supplier_id');
            $q->where('supplier_id', $supplier_id);
        }
        if (request('client_id')) {
            $client_id = request('client_id');
            $q->where('client_id', $client_id);
        }
        if (request('date_from') || request('date_to')) {
            $date_from = request()->query('date_from') ?? Carbon::today();
            $date_to = request()->query('date_to') ?? Carbon::today()->addYears(100);
            $q->whereBetween('date', [$date_from, $date_to]);
        }
        if (request('note')) {
            $note = request('note');
            $q->where('note', 'LIKE', "%{$note}%");
        }

        return $q;
    }
}
