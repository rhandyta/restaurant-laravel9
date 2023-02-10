<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\LabelMenuManagement;

class MenuManagementController extends Controller
{
    public function index()
    {
        $label_menus = LabelMenuManagement::with(['menus' => function ($q) {
            $q->orderBy('important', 'asc');
        }, 'menus.submenus' => function ($q) {
            $q->orderBy('important', 'asc');
        }])
            ->orderBy('important', 'asc')
            ->get();
        return view('manager.menumanagement.index', compact('label_menus'));
    }
}
