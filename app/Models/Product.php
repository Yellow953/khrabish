<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'tags' => 'array',
    ];

    // Profit
    public function get_profit()
    {
        return $this->price - $this->cost;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function secondary_images()
    {
        return $this->hasMany(SecondaryImage::class);
    }

    public function barcodes()
    {
        return $this->hasMany(Barcode::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function variants()
    {
        return $this->hasMany(Variant::class);
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

    public function getSalePercentage()
    {
        if (!is_array($this->tags)) {
            return null;
        }

        foreach ($this->tags as $tag) {
            if (preg_match('/^sale_(\d+)/', $tag, $matches)) {
                return (int) $matches[1];
            }
        }

        return null;
    }

    public function isOnSale()
    {
        return $this->getSalePercentage() !== null;
    }

    public function getSalePrice()
    {
        $sale = $this->getSalePercentage();
        return $sale ? round($this->price * (1 - $sale / 100), 2) : $this->price;
    }
}
