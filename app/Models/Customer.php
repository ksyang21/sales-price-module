<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $fillable = [
        'name',
        'address',
    ];

    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class);
    }
    public function prices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Price::class);
    }

    public function driverCustomer(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(DriverCustomer::class);
    }
}
