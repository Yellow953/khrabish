<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function cashier()
    {
        return $this->belongsTo(User::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generate_number()
    {
        $last_order = Order::orderBy('id', 'DESC')->first();

        if ($last_order) {
            return (int)$last_order->order_number + 1;
        } else {
            return 1;
        }
    }

    public function can_delete()
    {
        return auth()->user()->role == 'admin';
    }

    // Filter
    public function scopeFilter($q)
    {
        if (request('order_number')) {
            $order_number = request('order_number');
            $q->where('order_number', $order_number);
        }
        if (request('cashier_id')) {
            $cashier_id = request('cashier_id');
            $q->where('cashier_id', $cashier_id);
        }
        if (request('currency_id')) {
            $currency_id = request('currency_id');
            $q->where('currency_id', $currency_id);
        }
        if (request('note')) {
            $note = request('note');
            $q->where('note', 'LIKE', "%{$note}%");
        }

        return $q;
    }
}
