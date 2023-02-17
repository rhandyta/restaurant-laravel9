<?php

namespace App\Http\Controllers;

use App\Http\Requests\InformationTableStoreRequest;
use App\Models\InformationTable;
use App\Models\TableCategory;

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

    public function store(InformationTableStoreRequest $request)
    {
        dd($request->all());
    }
}
