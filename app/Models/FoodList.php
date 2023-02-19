<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodList extends Model
{
    use HasFactory;
    protected $table = 'food_lists';
    protected $fillable = ['food_category_id', 'food_name', 'food_description', 'price', 'img_url'];


    public function foodcategory()
    {
        return $this->belongsTo(FoodCategory::class, 'food_category_id', 'id');
    }
}
