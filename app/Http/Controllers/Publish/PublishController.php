<?php
namespace App\Http\Controllers\Publish;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PublishController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Display a listing of released and accepted datasets.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        $user = Auth::user();
        $userId = $user->id;

        $builder = Dataset::query();
        //"select * from [documents] where [server_state] in (?) or ([server_state] = ? and [editor_id] = ?)"
        $datasets = $builder
            ->where('server_state', 'reviewed')
            ->get();
        return view('workflow.publish.index', [
            'datasets' => $datasets,
        ]);
    }
}
