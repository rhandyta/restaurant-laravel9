<?php

namespace App\Http\Middleware;

use App\Models\LabelMenuManagement;
use Closure;
use Illuminate\Http\Request;

class MenuMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $user = $request->user();
        $menus = LabelMenuManagement::where('role', $user->roles)
            ->orWhereNull('role')
            ->with('menus', 'menus.submenus')
            ->get();
        $request->attributes->add(['menus' => $menus]);
        return $next($request);
    }
}
