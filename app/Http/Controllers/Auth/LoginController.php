<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return redirect()->route('login.index')->with('error', 'The provided credentials do not match our records.')->withInput();
        }
        return redirect()->route('manager.index');
    }
}
