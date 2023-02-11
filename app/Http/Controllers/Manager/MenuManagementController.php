<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\LabelMenuManagement;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function handleLabelMenu(Request $request)
    {
        try {
            $auth = Auth::user();
            $label_id = $request->input('label_id');
            $role_value =  $request->input('role_value');
            if ($role_value == 'both') $role_value = null;

            LabelMenuManagement::where('id', '=', $label_id)
                ->update(['role' => $role_value]);

            $label_menu = LabelMenuManagement::where('role', '=', $auth->roles)
                ->orWhereNull('role')
                ->with(['menus' => function ($q) {
                    $q->orderBy('important', 'asc');
                }, 'menus.submenus' => function ($q) {
                    $q->orderBy('important', 'asc');
                }])
                ->orderBy('important', 'asc')
                ->get();
            return response()->json([
                'status_code' => 200,
                'message' => 'Data has been updated',
                'results' => $label_menu
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status_code' => $e->getCode(),
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function handleMenu(Request $request)
    {
        try {
            $auth = Auth::user();
        } catch (Exception $e) {
            return response()->json([
                'status_code' => $e->getCode(),
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
