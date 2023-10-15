<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'customer_id',
        'price',
        'max_stock',
    ];

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
