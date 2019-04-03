<?php
namespace App\Http\Controllers\Publish;

use App\Http\Controllers\Controller;
// use App\Http\Requests\ProjectRequest;
// use App\Models\Project;
// use Illuminate\Http\RedirectResponse;
// use Illuminate\Http\Request;
use App\Models\Dataset;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class WorkflowController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function review()
    {
        $builder = Dataset::query();
        $datasets = $builder
        //->where('server_state', 'inprogress')
        ->whereIn('server_state', ['unpublished'])
            ->get();
        return view('workflow.review', compact('datasets'));
    }

    public function release()
    {
        $user = Auth::user();
        $user_id = $user->id;

        $builder = Dataset::query();
        $datasets = $builder
        ->where('server_state', 'inprogress')
        ->where('account_id', $user_id)
        ->get();
        return view('workflow.release', compact('datasets'));
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
                $dataset->server_date_published =  $time;
                session()->flash('flash_message', 'You have puplished 1 dataset!');
            }
            $dataset->save();
            return redirect()->back();
            //return redirect()->route('settings.review.index');
        } catch (Exception $e) {
            //return $this->_redirectTo('index', array('failure' => $e->getMessage()), 'documents', 'admin');
        }
    }
}
