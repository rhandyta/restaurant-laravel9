<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $fillable = ['id', 'order_id', 'user_id', 'transaction_id', 'transaction_code', 'transaction_message', 'gross_amount', 'amount', 'payment_type', 'transaction_status', 'bank', 'va_number', 'signature_key', 'notes', 'discount', 'information_table'];

    public function detailorders()
    {
        return $this->hasMany(DetailOrder::class, 'order_id', 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
