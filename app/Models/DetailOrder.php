<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailOrder extends Model
{
    use HasFactory;
    protected $table = 'detail_orders';
    protected $fillable = ['order_id', 'product', 'quantity', 'unit_price', 'subtotal', 'discount', 'total_price'];

    public function Order()
    {
        return $this->belongsTo(Order::class);
    }
}
