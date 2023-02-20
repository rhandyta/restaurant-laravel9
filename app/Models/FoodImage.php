<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodImage extends Model
{
    use HasFactory;
    protected $table = 'food_images';
    protected $fillable = ['food_list_id', 'image_url', 'public_id'];

    public function food()
    {
        return $this->belongsTo(FoodList::class, 'food_list_id', 'id');
    }
}
