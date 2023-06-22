<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailOrder extends Model
{
    use HasFactory;
    protected $table = 'detail_orders';
    protected $fillable = ['order_id', 'product', 'product_id', 'quantity', 'unit_price', 'subtotal', 'discount', 'total_price', 'rating'];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function foodlist()
    {
        return $this->belongsTo(FoodList::class, 'product_id', 'id');
    }
}
