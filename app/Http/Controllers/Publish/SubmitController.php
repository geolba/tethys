<?php
namespace App\Http\Controllers\Publish;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SubmitController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function index(): View
    {
        $user = Auth::user();
        $user_id = $user->id;

        $builder = Dataset::query();
        $myDatasets = $builder
            ->orderBy('server_state')
            ->whereIn('server_state', ['inprogress', 'released', 'editor_accepted', 'approved', 'reviewed'])
            ->where('account_id', $user_id)
            ->with('user:id,login')
            ->get();
        return view('workflow.submitter.index', [
            'datasets' => $myDatasets,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function release($id): View
    {
        $dataset = Dataset::with('user:id,login')->findOrFail($id);
        
        $editors = User::whereHas('roles', function ($q) {
            $q->where('name', 'editor');
        })->pluck('login', 'id');
        //$editors = Role::where('name', 'editor')->first()->users()->get();

        return view('workflow.submitter.release', [
            'dataset' => $dataset,
            'editors' => $editors,
        ]);
    }

    public function releaseUpdate(Request $request, $id)
    {
        $dataset = Dataset::findOrFail($id);

        $input = $request->all();
        $input['server_state'] = 'released';

        if ($dataset->update($input)) {
            // event(new PageUpdated($page));
            return redirect()
                ->route('publish.workflow.index')
                ->with('flash_message', 'You have released your dataset!');
        }
        throw new GeneralException(trans('exceptions.publish.release.update_error'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id): RedirectResponse
    {
        $dataset = Dataset::with('files')->findOrFail($id);
        if ($dataset->server_state != "inprogress") {
            session()->flash(
                'flash_message',
                'You cannot delete this datastet!'
                . ' There status of  this dataset is '
                . $dataset->server_state
                . ' !'
            );
            return redirect()->route('settings.project');
        } else {
            if ($dataset->files->count() > 0) {
                foreach ($dataset->files as $file) {
                    if (isset($file->path_name)) {
                        Storage::delete($file->path_name);
                    }
                }
            }
            $dataset->delete();
            session()->flash('flash_message', 'You have deleted 1 dataset!');
            return redirect()->route('publish.workflow.index');
        }
    }

    public function changestate($id, $targetState)
    {
        // $docId = $this->getRequest()->getParam('docId');
        // $targetState = $this->getRequest()->getParam('targetState');

        //$document = $this->_documentsHelper->getDocumentForId($docId);
        $dataset = Dataset::findOrFail($id);

        // Check if valid target state
        // if (!$this->_workflowHelper->isValidState($targetState)) {

        // }
        try {
            //$this->_workflowHelper->changeState($document, $targetState);
            $dataset->setServerState($targetState);

            if ($targetState == 'published') {
                //$this->_sendNotification($document, $form);
                $time = new \Illuminate\Support\Carbon();
                $dataset->server_date_published = $time;
                session()->flash('flash_message', 'You have puplished 1 dataset!');
            }
            $dataset->save();
            //return redirect()->back();
            //return redirect()->route('settings.review.index');
        } catch (Exception $e) {
            //return $this->_redirectTo('index', array('failure' => $e->getMessage()), 'documents', 'admin');
        }
    }
}
