<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App;
use Config;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // if(!Session::has('locale'))
        // {
        //     Session::put('locale', Config::get('app.locale'));
        // }
        $language = Session::get('locale', Config::get('app.locale'));
        // $data = Session::all();
        //  $language =Session::get('locale1');
         

        App::setLocale($language);
        return $next($request);
    }
}
