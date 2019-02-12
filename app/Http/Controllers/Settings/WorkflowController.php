<?php
namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
// use App\Http\Requests\ProjectRequest;
// use App\Models\Project;
// use Illuminate\Http\RedirectResponse;
// use Illuminate\Http\Request;
use App\Models\Dataset;
use Illuminate\View\View;

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
    public function index()
    {
        $builder = Dataset::query();
        $datasets = $builder
        //->where('server_state', 'inprogress')
        ->whereIn('server_state', ['unpublished'])
            ->get();
        return view('workflow.index', compact('datasets'));
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

            // if ($targetState == 'published') {
            //     $this->_sendNotification($document, $form);
            // }
            $dataset->save();
            session()->flash('flash_message', 'You have puplished 1 dataset!');
            return redirect()->route('settings.review.index');
        } catch (Exception $e) {
            //return $this->_redirectTo('index', array('failure' => $e->getMessage()), 'documents', 'admin');
        }
    }
}
