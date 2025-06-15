<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $guarded = [];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public static function generate_number()
    {
        $last_purchase = Purchase::orderBy('id', 'DESC')->first();

        if ($last_purchase) {
            return (int)$last_purchase->number + 1;
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
        if (request('invoice_number')) {
            $invoice_number = request('invoice_number');
            $q->where('invoice_number', $invoice_number);
        }
        if (request('purchase_date')) {
            $purchase_date = request('purchase_date');
            $q->whereDate('purchase_date', $purchase_date);
        }
        if (request('supplier_id')) {
            $supplier_id = request('supplier_id');
            $q->where('supplier_id', $supplier_id);
        }
        if (request('notes')) {
            $notes = request('notes');
            $q->where('notes', 'LIKE', "%{$notes}%");
        }

        return $q;
    }
}
