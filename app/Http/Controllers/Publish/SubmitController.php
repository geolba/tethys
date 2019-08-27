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
// for edit actions:
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use App\Models\License;
use App\Http\Requests\DocumentRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\Title;
use App\Models\Description;
use App\Models\File;

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
            //->orderBy('server_state')
            ->whereIn('server_state', ['inprogress',
            'released', 'editor_accepted', 'approved', 'reviewed', 'rejected_editor', 'rejected_reviewer'])
            ->where('account_id', $user_id)
            ->with('user:id,login')
            ->orderBy('server_date_modified', 'desc')
            ->get();
        return view('workflow.submitter.index', [
            'datasets' => $myDatasets,
        ]);
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
        $dataset->load('licenses', 'titles', 'abstracts', 'files', 'coverage', 'subjects', 'references');

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
        $keywordTypes = ['uncontrolled' => 'uncontrolled', 'swd' => 'swd'];

        $referenceTypes = ["rdr-id", "arXiv", "bibcode", "DOI", "EAN13", "EISSN", "Handle", "IGSN", "ISBN", "ISSN", "ISTC", "LISSN", "LSID", "PMID", "PURL", "UPC", "URL", "URN"];
        $referenceTypes = array_combine($referenceTypes, $referenceTypes);

        $relationTypes = ["IsCitedBy", "Cites", "IsSupplementTo", "IsSupplementedBy", "IsContinuedBy", "Continues", "HasMetadata", "IsMetadataFor","IsNewVersionOf", "IsPreviousVersionOf", "IsPartOf", "HasPart", "IsReferencedBy", "References"]; 
        // "IsDocumentedBy", "Documents", "IsCompiledBy", "Compiles", "IsVariantFormOf", "IsOriginalFormOf", "IsIdenticalTo", "IsReviewedBy", "Reviews", "IsDerivedFrom", "IsSourceOf"];
        $relationTypes = array_combine($relationTypes, $relationTypes);

    
        return view(
            'workflow.submitter.edit',
            compact(
                'dataset',
                'projects',
                'options',
                'checkeds',
                'years',
                'languages',
                'keywordTypes',
                'referenceTypes',
                'relationTypes'
            )
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
        $rules = [
            'type' => 'required|min:5',
            'coverage.xmin' => [
                'nullable',
                'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'
            ],
            'coverage.ymin' => [
                'nullable',
                'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'
            ],
            'coverage.xmax' => [
                'nullable',
                'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'
            ],
            'coverage.ymax' => [
                'nullable',
                'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'
            ],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $dataset = Dataset::findOrFail($id);
            $data = $request->all();
            $input = $request->except('abstracts', 'licenses', 'titles', 'coverage', '_method', '_token');
       
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

            //save the references:
            $references = $request->input('references');
            if (is_array($references) && count($references) > 0) {
                foreach ($references as $key => $formReference) {
                    $reference = DatasetReference::findOrFail($key);
                    $reference->value = $formReference['value'];
                    $reference->label = $formReference['label'];
                    $reference->type = $formReference['type'];
                    $reference->relation = $formReference['relation'];
                    $reference->save();
                }
            }

            //save the keywords:
            $keywords = $request->input('keywords');
            if (is_array($keywords) && count($keywords) > 0) {
                foreach ($keywords as $key => $formKeyword) {
                    $subject = Subject::findOrFail($key);
                    $subject->value = $formKeyword['value'];
                    $subject->type = $formKeyword['type'];
                    $subject->save();
                }
            }

            //save the files:
            $files = $request->input('files');
            if (is_array($files) && count($files) > 0) {
                foreach ($files as $key => $formFile) {
                    $file = File::findOrFail($key);
                    $file->label = $formFile['label'];
                    $file->save();
                }
            }

            // save coverage
            if (isset($data['coverage'])) {
                $formCoverage = $request->input('coverage');
                $coverage = $dataset->coverage()->updateOrCreate(
                    ['dataset_id' => $dataset->id],
                    $formCoverage
                );
            }

            if (!$dataset->isDirty(dataset::UPDATED_AT)) {
                $time = new \Illuminate\Support\Carbon();
                $dataset->setUpdatedAt($time);
            }
            // $dataset->save();
            if ($dataset->update($input)) {
                //event(new DatasetUpdated($dataset));
                session()->flash('flash_message', 'You have updated 1 dataset!');
                return redirect()->route('publish.workflow.submit.index');
            }
        } else {
            //TODO Handle validation error
            //pass validator errors as errors object for ajax response
            // return response()->json([
            //     'success' => false,
            //     'errors' => $validator->errors()->all(),
            // ], 422);
            return  back()
            ->withErrors($validator->errors()->all());
        }
        throw new GeneralException(trans('exceptions.backend.dataset.update_error'));
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
        if ($dataset->files->count() == 0) {
            return  back()
            ->withErrors(['datasets_count' => ['At least one dataset is required.']]);
        }

        $input = $request->all();
        //immer released setzen
        $input['server_state'] = 'released';
        //editor wieder löschen falls rejected
        if ($dataset->editor_id !== null) {
            $input['editor_id'] = null;
        }

        if ($dataset->reject_editor_note != null) {
            $input['reject_editor_note'] = null;
        }
        if ($dataset->reject_reviewer_note != null) {
            $input['reject_reviewer_note'] = null;
        }
        
        if ($dataset->update($input)) {
            // event(new PageUpdated($page));
            return redirect()
                ->route('publish.workflow.submit.index')
                ->with('flash_message', 'You have released your dataset!');
        }
        throw new GeneralException(trans('exceptions.publish.release.update_error'));
    }

     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function delete($id): View
    {
        $dataset = Dataset::with('user:id,login')->findOrFail($id);
        
        return view('workflow.submitter.delete', [
            'dataset' => $dataset
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteUpdate($id): RedirectResponse
    {
        $dataset = Dataset::with('files')->findOrFail($id);
        if ($dataset->server_state == "inprogress" || $dataset->server_state == "rejected_editor") {
            if ($dataset->files->count() > 0) {
                foreach ($dataset->files as $file) {
                    if (isset($file->path_name)) {
                        Storage::delete($file->path_name);
                    }
                }
            }
            $dataset->delete();
            session()->flash('flash_message', 'You have deleted 1 dataset!');
            return redirect()->route('publish.workflow.submit.index');
        } else {
            session()->flash(
                'flash_message',
                'You cannot delete this datastet!'
                . ' There status of  this dataset is '
                . $dataset->server_state
                . ' !'
            );
            return redirect()->route('publish.workflow.submit.index');
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
