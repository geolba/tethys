<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
// use Illuminate\Support\Facades\Config;
// use Illuminate\Http\RedirectResponse;
use Mcamara\LaravelLocalization\LanguageNegotiator;

class LocaleSessionRedirect extends LaravelLocalizationMiddlewareBase
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
         // If the URL of the request is in exceptions.
        if ($this->shouldIgnore($request)) {
            return $next($request);
        }
        $params = explode('/', $request->path());//only on refresh 0:"pages"; 1: "imprint"
        //$langParam = $request->input('lang', false);
        $locale = Session::get('locale', false);

        //old
        //$locale = Session::get('locale', Config::get('app.locale'));

        //$test =  app('laravellocalization');
        // if (\count($params) > 0 && app('laravellocalization')->checkLocaleInSupportedLocales(langParam)) {
        if (\count($params) > 1 && app('laravellocalization')->checkLocaleInSupportedLocales($params[1])) {
            //session(['locale' => $params[0]]);
            Session::put('locale', $params[1]);
           
            return $next($request);
        } elseif (empty($locale) && app('laravellocalization')->hideUrlAndAcceptHeader()) {
            // When default locale is hidden and accept language header is true,
            // then compute browser language when no session has been set.
            // Once the session has been set, there is no need
            // to negotiate language from browser again.
            $negotiator = new LanguageNegotiator(app('laravellocalization')->getDefaultLocale(), app('laravellocalization')->getSupportedLocales(), $request);
            $locale = $negotiator->negotiateLanguage();
            //session(['locale' => $locale]);
            Session::put('locale', $params[0]);
        }

        if ($locale === false) {
            $locale = app('laravellocalization')->getCurrentLocale();
        }
        App::setLocale($locale);
        return $next($request);
    }
}
