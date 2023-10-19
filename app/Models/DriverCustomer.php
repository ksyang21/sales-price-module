<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverCustomer extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'driver_id',
        'customer_id',
    ];

    public function driver() : \Illuminate\Database\Eloquent\Relations\BelongsTo {
        return $this->belongsTo(User::class, 'driver_id', 'id');
    }

    public function customer() : \Illuminate\Database\Eloquent\Relations\BelongsTo {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
}
