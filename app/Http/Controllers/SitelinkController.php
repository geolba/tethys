<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Dataset;
use Illuminate\Http\Request;

class SitelinkController extends Controller
{

    public function index()
    {
        //get server state published
        $serverState = 'published';
        $select = DB::table('documents')
        ->where('server_state', 'LIKE', "%".$serverState."%");

        $select
        ->select(DB::raw('YEAR(published_date) as published_date'))
        ->distinct(true);

        $this->years = $select->pluck('published_date');
        $this->ids = array();
        return view('rdr.sitelink.index')->with(['years'=> $this->years,'documents'=> $this->ids]);
    }

    public function list($year)
    {
        $this->index();
        if (preg_match('/^\d{4}$/', $year) > 0) {
            $serverState = 'published';
            //$select = DB::table('documents')
            //->where('server_state','LIKE', "%".$serverState."%");
            $select = Dataset::with('titles', 'authors')
                ->where('server_state', 'LIKE', "%".$serverState."%");

            $from = (int)$year;
            $until =  $year + 1;
            $select
            ->whereYear('server_date_published', '>=', $from)
            ->whereYear('server_date_published', '<', $until);


            $documents = $select
                ->get();

            //$this->years = Dataset::select(DB::raw('YEAR(server_date_modified) as server_date_modified'))
            //->distinct(true)
            //->pluck('server_date_modified');
            //->filter(function ($item) use ($year){
            //    return $item->year !== $year;
            //});


            //$select->select('id');
            //$this->ids = $select->pluck('id');
            //return view('rdr.sitelink.index')->with(['years'=> $this->years,'ids'=> $this->ids]);
            return view('rdr.sitelink.index')->with(['years'=> $this->years,'documents'=> $documents]);
        }
    }
}
