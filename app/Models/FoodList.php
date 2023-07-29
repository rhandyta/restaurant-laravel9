<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodList extends Model
{
    use HasFactory;
    protected $table = 'food_lists';
    protected $fillable = ['food_category_id', 'food_name', 'food_description', 'price', 'img_url'];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected function price(): Attribute
    {
        return Attribute::make(get: fn (string $value) => "Rp" . number_format($value, 2, ',', '.'));
    }


    public function foodcategory()
    {
        return $this->belongsTo(FoodCategory::class, 'food_category_id', 'id');
    }

    public function foodimages()
    {
        return $this->hasMany(FoodImage::class, 'food_list_id', 'id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'product_id', 'id');
    }

    public function detailorders()
    {
        return $this->hasMany(DetailOrder::class, 'product_id', 'id');
    }
}
