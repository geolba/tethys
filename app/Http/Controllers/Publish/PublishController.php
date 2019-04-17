<?php
namespace App\Http\Controllers\Publish;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\Request;

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

    /**
     * Display the specified dataset for publishing.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function publish($id): View
    {
        $dataset = Dataset::query()
        ->with([
            'titles',
            'persons' => function ($query) {
                $query->wherePivot('role', 'author');
            }
        ])->findOrFail($id);

        return view('workflow.publish.publish', [
            'dataset' => $dataset,
        ]);
    }

    public function publishUpdate(Request $request, $id)
    {
        $dataset = Dataset::findOrFail($id);
        $input = $request->all();
        $input['server_state'] = 'published';
        $time = new \Illuminate\Support\Carbon();
        $input['server_date_published'] = $time;

        if ($dataset->update($input)) {
            // event(new PageUpdated($page));
            return redirect()
                ->route('publish.workflow.publish.index')
                ->with('flash_message', 'You have successfully published the dataset!');
        }
        throw new GeneralException(trans('exceptions.publish.publish.update_error'));
    }
}
