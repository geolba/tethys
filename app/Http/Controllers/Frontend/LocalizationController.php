<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Session;
use App;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;

class LocalizationController extends Controller
{

//     public function index(Request $request,$locale){
//       //set’s application’s locale
//       app()->setLocale($locale);
      
//       //Gets the translated message and displays it
//       echo trans('lang.msg');
//    }

    public function index()
    {
        return view('localization.index');
    }

    public function setLocale($lang)
    {
        //if action is post method:
        //$lang = Input::get('language');

         
        //Session::put('locale', $lang);
        Session::put(['locale' =>  $lang]);
        // Session::save();
                 
        //return redirect(url(URL::previous()));
        return Redirect::back();
        //  echo trans('document.msg');
    }
}
