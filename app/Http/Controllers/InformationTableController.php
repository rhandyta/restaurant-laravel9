<?php

namespace App\Http\Controllers;

use App\Models\InformationTable;
use App\Models\TableCategory;
use Illuminate\Http\Request;

class InformationTableController extends Controller
{
    public function index()
    {
        $categoriesTables = TableCategory::select('id', 'category', 'status')->get();
        $informationTables = InformationTable::with('tablecategory')
            ->orderBy('id', 'desc')
            ->paginate(25);
        return view('tables.informationtable', compact('informationTables', 'categoriesTables'));
    }
}
