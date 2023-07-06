<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformationTable extends Model
{
    use HasFactory;
    protected $table = 'information_tables';
    protected $fillable = ['category_table_id', 'seating_capacity', 'available', 'location'];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];


    public function tablecategory()
    {
        return $this->belongsTo(TableCategory::class, 'category_table_id', 'id');
    }
}
