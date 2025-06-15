<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportItem extends Model
{
    protected $guarded = [];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
