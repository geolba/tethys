<?php
namespace App\Http\Controllers\Frontend;

use App\Models\Dataset;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SitelinkController extends Controller
{

    public function index()
    {
        //get server state published
        $serverState = 'published';
        $select = DB::table('documents')
            ->where('server_state', 'LIKE', "%" . $serverState . "%");

        $select
            ->select(DB::raw('EXTRACT(YEAR FROM server_date_published) as published_date'))
            // ->select(DB::raw("DATE_PART('year', server_date_published) as published_date"))
            // ->select(DB::raw("YEAR(server_date_published) AS published_date"))
            ->distinct(true);
            $this->years  = $select->pluck('published_date')->toArray();
        
        // $years = $select->pluck('server_date_published')->toArray();
        // $this->years  = array_map(function ($pdate) {
        //     $dateValue = strtotime($pdate);
        //     if ($dateValue != false) {
        //         $year =  date("Y", $dateValue);
        //         return $year;
        //     }
        // }, $years);
        $this->ids = array();
        return view('frontend.sitelink.index')->with(['years' => $this->years, 'documents' => $this->ids]);
    }

    public function listDocs($year)
    {
        $this->index();
        if (preg_match('/^\d{4}$/', $year) > 0) {
            $serverState = 'published';
            //$select = DB::table('documents')
            //->where('server_state','LIKE', "%".$serverState."%");
            $select = Dataset::with('titles', 'authors')
                ->where('server_state', 'LIKE', "%" . $serverState . "%");

            $from = (int) $year;
            $until = $year + 1;
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
            return view('frontend.sitelink.index')
                ->with(['years' => $this->years, 'documents' => $documents]);
        }
    }
}
