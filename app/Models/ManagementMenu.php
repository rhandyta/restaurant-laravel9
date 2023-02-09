<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagementMenu extends Model
{
    use HasFactory;
    protected $table = 'menu_managements';
    protected $fillable = ['label_menu'];

    public function labelmenu()
    {
        return $this->belongsTo(LabelMenuManagement::class, 'labelmenu_id', 'id');
    }

    public function submenus()
    {
        return $this->hasMany(ManagementSubMenu::class, 'menu_id', 'id');
    }
}
