<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model
{
    use HasFactory;
    protected $table = 'payment_types';
    protected $fillable = ['payment_type', 'status'];

    public function paymentType() : Attribute
    {
        return Attribute::make(
            get: fn (string $value) => \Str::replace('_', ' ', $value),
            set: fn (string $value) => strtolower($value)
        );
    }

    public function banks()
    {
        return $this->hasMany(Bank::class, 'payment_type_id', 'id');
    }
}
