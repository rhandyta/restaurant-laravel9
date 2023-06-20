<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $fillable = ['id', 'order_id', 'user_id', 'transaction_id', 'gross_amount', 'amount', 'payment_type', 'transaction_status', 'bank', 'va_number', 'notes', 'discount'];

    public function DetailOrders()
    {
        return $this->hasMany(DetailOrder::class);
    }
}
