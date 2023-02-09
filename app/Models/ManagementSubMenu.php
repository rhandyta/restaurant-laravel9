<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagementSubMenu extends Model
{
    use HasFactory;
    protected $table = 'submenu_managements';
    protected $fillable = ['menu_id', 'label_submenu'];

    public function menu()
    {
        return $this->belongsTo(ManagementMenu::class, 'menu_id', 'id');
    }
}
