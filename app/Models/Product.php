<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Profit
    public function get_profit()
    {
        return $this->price - $this->cost;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function barcodes()
    {
        return $this->hasMany(Barcode::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function can_delete()
    {
        return auth()->user()->role == 'admin' && $this->items->count() == 0;
    }

    // Filter
    public function scopeFilter($q)
    {
        if (request('name')) {
            $name = request('name');
            $q->where('name', 'LIKE', "%{$name}%");
        }
        if (request('category_id')) {
            $category_id = request('category_id');
            $q->where('category_id', $category_id);
        }
        if (request('description')) {
            $description = request('description');
            $q->where('description', 'LIKE', "%{$description}%");
        }

        return $q;
    }
}
