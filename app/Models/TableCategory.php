<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableCategory extends Model
{
    use HasFactory;

    protected $table = 'table_categories';
    protected $fillable = ['category', 'status'];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];


    public function category(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => strtolower($value),
            get: fn ($value) => ucwords($value),
        );
    }

    public function status(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucfirst($value)
        );
    }

    public function informationtables()
    {
        return $this->hasMany(InformationTable::class, 'category_table_id', 'id');
    }
}
