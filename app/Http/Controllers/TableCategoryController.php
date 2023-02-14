<?php

namespace App\Http\Controllers;

use App\Models\TableCategory;
use Illuminate\Http\Request;

class TableCategoryController extends Controller
{
    public function index()
    {
        $categorytables = TableCategory::orderBy('id', 'desc')->paginate(25);
        return view('tables.index', compact('categorytables'));
    }

    public function store()
    {
        // 
    }

    public function destroy()
    {
        // 
    }
}
