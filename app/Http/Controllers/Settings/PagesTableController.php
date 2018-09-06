<?php

namespace App\Http\Controllers\Settings;

use App\Models\Page;
use App\Http\Controllers\Controller;
// use App\Http\Requests\Backend\Pages\ManagePageRequest;
use App\Http\Requests\Pages\IndexPageRequest;
// use App\Repositories\Backend\Pages\PagesRepository;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class PagesTableController.
 */
class PagesTableController extends Controller
{
    protected $pages;

    
    public function __construct() //(PagesRepository $pages)
    {
        //$this->pages = factory(Page::class, 2)->make();
        $this->pages = Page::get();
    }

  
    public function get()
    {
        $test = Datatables::of($this->pages)
            ->escapeColumns(['title'])
            ->addColumn('status', function ($page) {
                return $page->status;
            })
            ->addColumn('created_at', function ($page) {
                return $page->created_at->toDateString();
            })
            ->addColumn('created_by', function ($page) {
                return $page->created_by;
            })
            ->addColumn('actions', function ($page) {
                return $page->action_buttons;
            })
            ->make(true);
            return $test;
    }
}
