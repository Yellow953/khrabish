<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function debts()
    {
        return $this->hasMany(Debt::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    // Permissions
    public function can_delete()
    {
        return $this->users->count() == 0 && $this->debts->count() == 0 && $this->reports->count() == 0 && $this->orders->count() == 0 && auth()->user()->role == "admin";
    }

    // Filter
    public function scopeFilter($q)
    {
        if (request('code')) {
            $code = request('code');
            $q->where('code', 'LIKE', "%{$code}%");
        }
        if (request('name')) {
            $name = request('name');
            $q->where('name', 'LIKE', "%{$name}%");
        }
        if (request('symbol')) {
            $symbol = request('symbol');
            $q->where('symbol', 'LIKE', "%{$symbol}%");
        }

        return $q;
    }
}
