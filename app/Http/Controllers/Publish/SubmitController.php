<?php
namespace App\Http\Controllers\Publish;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRequest;
use App\Models\Dataset;
use App\Models\DatasetReference;
use App\Models\Description;
use App\Models\File;
use App\Models\License;
// use Illuminate\View\View;
use App\Models\Project;
// for edit actions:
use App\Models\Subject;
use App\Models\Title;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use App\Models\Person;

class SubmitController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function index(): \Illuminate\Contracts\View\View
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
        return View::make('workflow.submitter.index', [
            'datasets' => $myDatasets,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): \Illuminate\Contracts\View\View
    {
        $dataset = Dataset::findOrFail($id);
        $dataset->load('licenses', 'authors', 'contributors', 'titles', 'abstracts', 'files', 'coverage', 'subjects', 'references');

        $titleTypes = ['Main' => 'Main', 'Sub' => 'Sub', 'Alternative' => 'Alternative', 'Translated' => 'Translated', 'Other' => 'Other'];
        $descriptionTypes = ['Abstract' => 'Abstract', 'Methods' => 'Methods', 'Series_information' => 'Series_information', 'Technical_info' => 'Technical_info', 'Translated' => 'Translated', 'Other' => 'Other'];
        $languages = DB::table('languages')
            ->where('active', true)
            ->pluck('part1', 'part1');

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

        // $options = License::all('id', 'name_long');
        $licenses = License::select('id', 'name_long', 'link_licence')
            ->orderBy('sort_order')
            ->get();
        // $checkeds = $dataset->licenses->pluck('id')->toArray();
        $checkeds = $dataset->licenses->first()->id;

        $keywordTypes = ['uncontrolled' => 'uncontrolled', 'swd' => 'swd'];

        $referenceTypes = ["rdr-id", "doi", "handle", "isbn", "issn", "url", "urn"];
        $referenceTypes = array_combine($referenceTypes, $referenceTypes);

        $relationTypes = ["IsCitedBy", "Cites", "IsSupplementTo", "IsSupplementedBy", "IsContinuedBy", "Continues", "HasMetadata", "IsMetadataFor", "IsNewVersionOf", "IsPreviousVersionOf", "IsPartOf", "HasPart", "IsReferencedBy", "References", "IsDocumentedBy", "Documents", "IsCompiledBy", "Compiles", "IsVariantFormOf", "IsOriginalFormOf", "IsIdenticalTo", "IsReviewedBy", "Reviews", "IsDerivedFrom", "IsSourceOf"];
        $relationTypes = array_combine($relationTypes, $relationTypes);

        return View::make(
            'workflow.submitter.edit',
            compact(
                'dataset',
                'titleTypes',
                'descriptionTypes',
                'languages',
                'projects',
                'licenses',
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
                'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/',
            ],
            'coverage.ymin' => [
                'nullable',
                'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/',
            ],
            'coverage.xmax' => [
                'nullable',
                'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/',
            ],
            'coverage.ymax' => [
                'nullable',
                'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/',
            ],
            'keywords.*.value' => 'required|string',
            'keywords.*.type' => 'required|string',
            'files.*.label' => 'required|string',
        ];
        $customMessages = [
            'keywords.*.type.required' => 'The types of all keywords are required.',
        ];
        $validator = Validator::make($request->all(), $rules, $customMessages);
        if (!$validator->fails()) {
            $dataset = Dataset::findOrFail($id);
            $data = $request->all();
            $input = $request->except(
                'abstracts',
                'licenses',
                'titles',
                'coverage',
                'subjects',
                'references',
                'files'
            );

            $licenses = $request->input('licenses');
            //$licenses = $input['licenses'];
            $dataset->licenses()->sync($licenses);

            $dataset->authors()->sync([]);
            //store authors
            if (isset($data['authors'])) {
                // $data_to_sync = [];
                $index = 0;
                foreach ($request->get('authors') as $key => $person) {
                    $pivot_data = ['role' => 'author', 'sort_order' => $index + 1, 'allow_email_contact' => false];
                    // if ($galery_id == $request->get('mainPicture')) $pivot_data = ['main' => 1];
                    if (isset($person['id'])) {
                        // $data_to_sync[$person['id']] = $pivot_data;
                        $dataset->persons()->attach($person['id'], $pivot_data);
                    } else {
                        $dataPerson = new Person($person);
                        $dataPerson->status = true;
                        $dataPerson->name_type = "Organizational";
                        $dataset->persons()->save($dataPerson, $pivot_data);
                    }
                    $index++;
                }
                // $dataset->persons()->sync($data_to_sync);
            }

            $dataset->contributors()->sync([]);
            //store contributors
            if (isset($data['contributors'])) {
                // $data_to_sync = [];
                $index = 0;
                foreach ($request->get('contributors') as $key => $person) {
                    $pivot_data = ['role' => 'contributor', 'sort_order' => $index + 1, 'allow_email_contact' => false];
                    // if ($galery_id == $request->get('mainPicture')) $pivot_data = ['main' => 1];
                    if (isset($person['id'])) {
                        // $data_to_sync[$person['id']] = $pivot_data;
                        $dataset->persons()->attach($person['id'], $pivot_data);
                    } else {
                        $dataPerson = new Person($person);
                        $dataPerson->status = true;
                        $dataPerson->name_type = "Organizational";
                        $dataset->persons()->save($dataPerson, $pivot_data);
                    }
                    $index++;
                }
                // $dataset->persons()->sync($data_to_sync);
            }

            //save the titles:
            $titles = $request->input('titles');
            if (is_array($titles) && count($titles) > 0) {
                foreach ($titles as $key => $formTitle) {
                    if (isset($key) && $key != 'undefined') {
                        $title = Title::findOrFail($key);
                        $title->value = $formTitle['value'];
                        $title->language = $formTitle['language'];
                        $title->type = $formTitle['type'];
                        if ($title->isDirty()) {
                            $title->save();
                        }
                    } else {
                        $title = new Title($formTitle);
                        $dataset->titles()->save($title);
                    }
                }
            }

            //save the abstracts:
            $abstracts = $request->input('abstracts');
            if (is_array($abstracts) && count($abstracts) > 0) {
                foreach ($abstracts as $key => $formAbstract) {
                    if (isset($key) && $key != 'undefined') {
                        $abstract = Description::findOrFail($key);
                        $abstract->value = $formAbstract['value'];
                        $abstract->language = $formAbstract['language'];
                        if ($abstract->isDirty()) {
                            $abstract->save();
                        }
                    } else {
                        $abstract = new Description($formAbstract);
                        $dataset->abstracts()->save($abstract);
                    }
                }
            }

            //save the references:
            $references = $request->input('references');
            if (is_array($references) && count($references) > 0) {
                foreach ($references as $key => $formReference) {
                    if (isset($key) && $key != 'undefined') {
                        $reference = DatasetReference::findOrFail($key);
                        $reference->value = $formReference['value'];
                        $reference->label = $formReference['label'];
                        $reference->type = $formReference['type'];
                        $reference->relation = $formReference['relation'];
                        if ($reference->isDirty()) {
                            $reference->save();
                        }
                    } else {
                        $reference = new DatasetReference($formReference);
                        $dataset->references()->save($reference);
                    }
                }
            }

            //save the keywords:
            $keywords = $request->input('subjects');
            if (is_array($keywords) && count($keywords) > 0) {
                foreach ($keywords as $key => $formKeyword) {
                    if (isset($key) && $key != 'undefined') {
                        $subject = Subject::findOrFail($key);
                        $subject->value = $formKeyword['value'];
                        $subject->type = $formKeyword['type'];
                        if ($subject->isDirty()) {
                            $subject->save();
                        }
                    } else {
                        $subject = new Subject($formKeyword);
                        $dataset->subjects()->save($subject);
                    }
                }
            }

            //save the files:
            $files = $request->input('files');
            if (is_array($files) && count($files) > 0) {
                foreach ($files as $key => $formFile) {
                    $file = File::findOrFail($key);
                    $file->label = $formFile['label'];
                    if ($file->isDirty()) {
                        $file->save();
                    }
                }
            }

            // save coverage
            if (isset($data['coverage']) && !$this->containsOnlyNull($data['coverage'])) {
                $formCoverage = $request->input('coverage');
                $coverage = $dataset->coverage()->updateOrCreate(
                    ['dataset_id' => $dataset->id],
                    $formCoverage
                );
            } elseif (isset($data['coverage']) && $this->containsOnlyNull($data['coverage'])
                && !is_null($dataset->coverage)) {
                $dataset->coverage()->delete();
            }

            $dataset->fill($input);
            // $dataset->creating_corporation = "Peter";

            if (!$dataset->isDirty()) {
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
            return back()->withInput()
                ->withErrors($validator->errors()->all());
        }
        throw new GeneralException(trans('exceptions.backend.dataset.update_error'));
    }

    private function containsOnlyNull($input)
    {
        return empty(array_filter($input, function ($a) {
            return $a !== null;
        }));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function release($id): \Illuminate\Contracts\View\View
    {
        $dataset = Dataset::with('user:id,login')->findOrFail($id);

        $editors = User::whereHas('roles', function ($q) {
            $q->where('name', 'editor');
        })->pluck('login', 'id');
        //$editors = Role::where('name', 'editor')->first()->users()->get();

        return View::make('workflow.submitter.release', [
            'dataset' => $dataset,
            'editors' => $editors,
        ]);
    }

    public function releaseUpdate(Request $request, $id)
    {
        $dataset = Dataset::findOrFail($id);
        if ($dataset->files->count() == 0) {
            return back()
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
    public function delete($id): \Illuminate\Contracts\View\View
    {
        $dataset = Dataset::with('user:id,login')->findOrFail($id);

        return View::make('workflow.submitter.delete', [
            'dataset' => $dataset,
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
