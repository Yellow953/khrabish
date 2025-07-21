<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $guarded = [];

    // Permissions
    public function can_delete()
    {
        return auth()->user()->role == "admin";
    }

    // Filter
    public function scopeFilter($q)
    {
        if (request('type')) {
            $type = request('type');
            $q->where('type', $type);
        }
        if (request('code')) {
            $code = request('code');
            $q->where('code', 'LIKE', "%{$code}%");
        }
        if (request('description')) {
            $description = request('description');
            $q->where('description', 'LIKE', "%{$description}%");
        }

        return $q;
    }
}
