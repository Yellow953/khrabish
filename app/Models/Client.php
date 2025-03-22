<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function debts()
    {
        return $this->hasMany(Debt::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Permissions
    public function can_delete()
    {
        return auth()->user()->role == 'admin' && $this->debts->count() == 0 && $this->orders->count() == 0;
    }

    // Filter
    public function scopeFilter($q)
    {
        if (request('name')) {
            $name = request('name');
            $q->where('name', 'LIKE', "%{$name}%");
        }
        if (request('email')) {
            $email = request('email');
            $q->where('email', 'LIKE', "%{$email}%");
        }
        if (request('phone')) {
            $phone = request('phone');
            $q->where('phone', 'LIKE', "%{$phone}%");
        }
        if (request('country')) {
            $country = request('country');
            $q->where('country', 'LIKE', "%{$country}%");
        }
        if (request('city')) {
            $city = request('city');
            $q->where('city', 'LIKE', "%{$city}%");
        }
        if (request('address')) {
            $address = request('address');
            $q->where('address', 'LIKE', "%{$address}%");
        }

        return $q;
    }
}
