<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodImage extends Model
{
    use HasFactory;
    protected $table = 'food_images';
    protected $fillable = ['food_list_id', 'image_url', 'public_id'];

    public function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => preg_replace('/^(https:\/\/res.cloudinary.com\/[\w\/]+\/image\/upload)\/(\w+)\/(.+)$/', '$1/w_300,h_300,c_fill/$2/$3', $value),
        );
    }

    public function food()
    {
        return $this->belongsTo(FoodList::class, 'food_list_id', 'id');
    }
}
