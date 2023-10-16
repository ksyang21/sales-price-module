<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'order_id',
        'product_id',
        'price',
        'quantity'
    ];
    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
