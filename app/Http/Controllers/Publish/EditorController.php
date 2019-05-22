<?php
namespace App\Http\Controllers\Publish;

use App\Exceptions\GeneralException;
use App\Http\Requests\DocumentRequest;
use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Project;
use App\Models\License;
use App\Models\User;
use App\Models\Title;
use App\Models\Description;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class EditorController extends Controller
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
        $user_id = $user->id;

        $builder = Dataset::query();
        //"select * from [documents] where [server_state] in (?) or ([server_state] = ? and [editor_id] = ?)"
        $datasets = $builder
        ->where('server_state', 'released')
        // ->whereIn('server_state', ['released'])
        ->orWhere(function ($query) use ($user_id) {
            $query->whereIn('server_state', ['editor_accepted', 'rejected_reviewer', 'reviewed'])
                  ->where('editor_id', $user_id);
        })
        ->orderBy('server_date_modified', 'desc')
        ->get();
        return view('workflow.editor.index', compact('datasets'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function receive($id): View
    {
        $dataset = Dataset::with('user:id,login')->findOrFail($id);

        return view('workflow.editor.receive', [
            'dataset' => $dataset,
        ]);
    }

    public function receiveUpdate(Request $request, $id)
    {
        $dataset = Dataset::findOrFail($id);
      
        try {
            $dataset->setServerState("editor_accepted");
            $user = Auth::user();
            $dataset->editor()->associate($user)->save();
            $dataset->save();
            // return redirect()->back();
            return redirect()->route('publish.workflow.editor.index');
        } catch (Exception $e) {
            throw new GeneralException(trans('exceptions.publish.accept.update_error'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $dataset = Dataset::findOrFail($id);
        $dataset->load('licenses', 'titles', 'abstracts', 'files');

        $projects = Project::pluck('label', 'id');

        $datum = date('Y-m-d');
        $nowYear = substr($datum, 0, 4);
        $years = array();
        for ($jahr = 1990; $jahr <= $nowYear; $jahr++) {
            $years[$jahr] = $jahr;
        }

        $languages = DB::table('languages')
            ->where('active', true)
            ->pluck('part1', 'part1');

        //$options = License::all();
        $options = License::all('id', 'name_long');
        $checkeds = $dataset->licenses->pluck('id')->toArray();
    
        return view(
            'workflow.editor.edit',
            compact('dataset', 'projects', 'options', 'checkeds', 'years', 'languages')
        );
    }

    //https://stackoverflow.com/questions/17480200/using-laravel-how-do-i-create-a-view-that-will-update-a-one-to-many-relationshi?rq=1
    // https://laravel.io/forum/06-11-2014-how-to-save-eloquent-model-with-relations-in-one-go
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DocumentRequest $request, $id): RedirectResponse
    {
        $dataset = Dataset::findOrFail($id);
        //$input = $request->all();
        $input = $request->except('abstracts', 'licenses', 'titles', '_method', '_token');
        // foreach ($input as $key => $value) {
        //     $dataset[$key] = $value;
        // }
        //$dataset->update($input);
        // $dataset->type = $input['type'];
        // $dataset->thesis_year_accepted = $input['thesis_year_accepted'];
        // $dataset->project_id = $input['project_id'];
        // $dataset->save();

        $licenses = $request->input('licenses');
        //$licenses = $input['licenses'];
        $dataset->licenses()->sync($licenses);

        //save the titles:
        $titles = $request->input('titles');
        if (is_array($titles) && count($titles) > 0) {
            foreach ($titles as $key => $formTitle) {
                $title = Title::findOrFail($key);
                $title->value = $formTitle['value'];
                $title->language = $formTitle['language'];
                $title->save();
            }
        }

        //save the abstracts:
        $abstracts = $request->input('abstracts');
        if (is_array($abstracts) && count($abstracts) > 0) {
            foreach ($abstracts as $key => $formAbstract) {
                $abstract = Description::findOrFail($key);
                $abstract->value = $formAbstract['value'];
                $abstract->language = $formAbstract['language'];
                $abstract->save();
            }
        }

        if (!$dataset->isDirty(dataset::UPDATED_AT)) {
            $time = new \Illuminate\Support\Carbon();
            $dataset->setUpdatedAt($time);
        }
        // $dataset->save();
        if ($dataset->update($input)) {
            //event(new DatasetUpdated($dataset));
            session()->flash('flash_message', 'You have updated 1 dataset!');
            return redirect()->route('publish.workflow.editor.index');
        }
        throw new GeneralException(trans('exceptions.backend.dataset.update_error'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function approve($id): View
    {
        $dataset = Dataset::with('user:id,login')->findOrFail($id);
       
        $reviewers = User::whereHas('roles', function ($q) {
            $q->where('name', 'reviewer');
        })->pluck('login', 'id');

        return view('workflow.editor.approve', [
            'dataset' => $dataset,
            'reviewers' => $reviewers,
        ]);
    }

    public function approveUpdate(Request $request, $id)
    {
        // $dataset = Dataset::findOrFail($id);
        // try {
        //     $dataset->setServerState("approved");
        //     $user = Auth::user();
        //     $dataset->reviewer()->associate($user)->save();
        //     $dataset->save();
        //     // return redirect()->back();
        //     return redirect()->route('publish.workflow.editor.index');
        // } catch (Exception $e) {
        //     throw new GeneralException(trans('exceptions.publish.approve.update_error'));
        // }
        $dataset = Dataset::findOrFail($id);
        $input = $request->all();
        $input['server_state'] = 'approved';
        if ($dataset->reject_reviewer_note != null) {
            $input['[reject_reviewer_note'] = null;
        }

        if ($dataset->update($input)) {
            // event(new PageUpdated($page));
            return redirect()
                ->route('publish.workflow.editor.index')
                ->with('flash_message', 'You have approved one dataset!');
        }
        throw new GeneralException(trans('exceptions.publish.approve.update_error'));
    }

     /**
     * Reject dataset back to editor
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function reject($id): View
    {
        $dataset = Dataset::with('user:id,login')->findOrFail($id);
        return view('workflow.editor.reject', [
            'dataset' => $dataset,
        ]);
    }

    public function rejectUpdate(Request $request, $id)
    {
        $this->validate(request(), [
            'reject_editor_note' => 'required|min:10|max:255',
            'server_state' => 'required'
        ]);
        $dataset = Dataset::findOrFail($id);
        $input = $request->all();
        //$input['server_state'] = 'rejected_editor';

        if ($dataset->update($input)) {
            // event(new PageUpdated($page));
            return redirect()
                ->route('publish.workflow.editor.index')
                ->with('flash_message', 'You have successfully rejected one dataset! The submitter will be informed.');
        }
        throw new GeneralException(trans('exceptions.publish.review.update_error'));
    }
}
