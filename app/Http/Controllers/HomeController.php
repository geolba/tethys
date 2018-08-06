<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        // $tglSekarang = time();

        // $students = Student::get();

        // foreach ($students as $student) {

        // $dateDiff = $tglSekarang - $student['registered_at'];
        // $durasi = floor($dateDiff/(60 * 60 * 24));
        // $periode = Periode::first();
        // if($durasi > $periode['days']){
        // $student->update(['status' => 0]);
        // }
        // else{
        // $student->update(['status' => 1]);
        // }

        // }
        return view('rdr.home.index');
    }

     /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function contact(): View
    {
        return view('rdr.home.contact');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function imprint(): View
    {
        return view('rdr.home.imprint');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function about(): View
    {
        return view('rdr.home.about');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function news(): View
    {
        return view('rdr.home.news');
    }
}
