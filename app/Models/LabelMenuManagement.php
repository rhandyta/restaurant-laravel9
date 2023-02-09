<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabelMenuManagement extends Model
{
    use HasFactory;

    protected $table = 'labelmenu_managements';
    protected $fillable = ['label_title', 'role'];

    public function menus()
    {
        return $this->hasMany(ManagementMenu::class, 'labelmenu_id', 'id');
    }
}
