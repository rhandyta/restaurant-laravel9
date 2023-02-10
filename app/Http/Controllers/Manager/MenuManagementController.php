<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\LabelMenuManagement;

class MenuManagementController extends Controller
{
    public function index()
    {
        $label_menus = LabelMenuManagement::with('menus', 'menus.submenus')->get();
        return view('manager.menumanagement.index', compact('label_menus'));
    }
}
