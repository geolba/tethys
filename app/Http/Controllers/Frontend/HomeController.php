<?php
namespace App\Http\Controllers\Frontend;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Models\Page;
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
        return view('frontend.home.index');
        // return view('welcome');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function contact(): View
    {
        return view('frontend.home.contact');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function imprint(): View
    {
        return view('frontend.home.imprint');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function about(): View
    {
        return view('frontend.home.about');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function news(): View
    {
        return view('frontend.home.news');
    }

    /**
     * show page by $page_slug.
     */
    public function showPage($slug)
    {
        // $result = $pages->findBySlug($slug);
        if (!is_null(Page::query()->wherePage_slug($slug)->firstOrFail())) {
            $result = Page::query()->wherePage_slug($slug)->firstOrFail();
            return view('frontend.pages.index')
                ->withpage($result);
        } else {
            throw new GeneralException(trans('exceptions.backend.access.pages.not_found'));
        }
    }
}
