<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'driver_id',
        'customer_id',
        'status'
    ];
    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
    public function driver(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id', 'id');
    }
    public function details(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderDetails::class);
    }
}
