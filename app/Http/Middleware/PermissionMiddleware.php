<?php
namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;

use Closure;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        if (Auth::guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('/login');
            }
        }

        //if (! $request->user()->hasRole($role))
        //{
        //   abort(401);
        //}
        foreach ($request->user()->roles()->get() as $role) {
            if ($role->hasPermissionTo($permission)) {
                return $next($request);
            }
            //break(1);
        }

        //if (! $request->user()->can($permission))
        //{
        //   abort(401);
        //}
        return abort(401);
        //return $next($request);
    }
}
