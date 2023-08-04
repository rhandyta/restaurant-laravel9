<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;
    protected $table = 'banks';
    protected $fillable = ['payment_type_id', 'name', 'status'];

    public function name() : Attribute
    {
        return Attribute::make(
            set: fn (string $value) => strtolower($value),
            get: fn (string $value) => strtoupper($value)
        );
    }
    public function status() : Attribute
    {
        return Attribute::make(
            set: fn (string $value) => strtolower($value),
            get: fn (string $value) => strtoupper($value)
        );
    }

    public function paymenttype()
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id', 'id');
    }
}
