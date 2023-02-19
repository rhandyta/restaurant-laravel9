<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodCategory extends Model
{
    use HasFactory;
    protected $table = 'food_categories';
    protected $fillable = ['category_name', 'category_description'];

    public function foodlists()
    {
        return $this->hasMany(FoodList::class, 'food_category_id', 'id');
    }
}
