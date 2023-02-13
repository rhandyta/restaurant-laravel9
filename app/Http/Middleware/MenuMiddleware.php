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

        $auth = $request->user();
        $menus = LabelMenuManagement::where('role', '=', $auth->roles)
            ->orWhere('role', 'both')
            ->with(['menus' => function ($q) use ($auth) {
                $q->where('role', '=', $auth->roles)
                    ->orWhere('role', 'both')
                    ->orderBy('important', 'asc');
            }, 'menus.submenus' => function ($q) use ($auth) {
                $q->where('role', '=', $auth->roles)
                    ->orWhere('role', 'both')
                    ->orderBy('important', 'asc');
            }])
            ->orderBy('important', 'asc')
            ->get();
        $request->attributes->add(['menus' => $menus]);
        return $next($request);
    }
}
