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
use App\Models\Person;
use App\Models\Project;
use App\Models\Subject;
use App\Models\Title;
use App\Models\User;
use App\Rules\RdrFilesize;
use App\Rules\RdrFiletypes;
// use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use \Exception;
use Illuminate\Support\Facades\Config;

use App\Models\DatasetIdentifier;
use App\Models\Oai\OaiModelError;
use App\Exceptions\OaiModelException;
use App\Interfaces\DoiInterface;

class EditorController extends Controller
{
     /**
     * Holds xml representation of document information to be processed.
     *
     * @var \DomDocument  Defaults to null.
     */
    protected $xml = null;
     /**
     * Holds the stylesheet for the transformation.
     *
     * @var \DomDocument  Defaults to null.
     */
    protected $xslt = null;

    /**
     * Holds the xslt processor.
     *
     * @var \XSLTProcessor  Defaults to null.
     */
    protected $proc = null;

    public function __construct(DoiInterface $DoiClient)
    {
        $this->doiClient = $DoiClient;

        //$this->middleware('auth');
        $this->xml = new \DomDocument();
        $this->proc = new \XSLTProcessor();
    }

    /**
     * Display a listing of released and accepted datasets.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        $user = Auth::user();
        $user_id = $user->id;

        $builder = Dataset::query();
        //"select * from [documents] where [server_state] in (?) or ([server_state] = ? and [editor_id] = ?)"
        $datasets = $builder
            
            ->where('server_state', 'released')
            // ->whereIn('server_state', ['released'])
            ->orWhere(function ($query) use ($user_id) {
                $query->whereIn('server_state', ['editor_accepted', 'rejected_reviewer', 'reviewed', 'published'])
                    ->where('editor_id', $user_id)->doesntHave('identifier', 'and');
            })
            ->orderBy('server_date_modified', 'desc')
            ->get();
        // return View::make('workflow.editor.index', compact('datasets'));
        return View::make('workflow.editor.index', [
            'datasets' => $datasets,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function receive($id): \Illuminate\Contracts\View\View
    {
        $dataset = Dataset::with('user:id,login')->findOrFail($id);

        return View::make('workflow.editor.receive', [
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
    public function edit($id): \Illuminate\Contracts\View\View
    {
        $dataset = Dataset::findOrFail($id);
        $dataset->load('licenses', 'authors', 'contributors', 'titles', 'abstracts', 'files', 'coverage', 'subjects', 'references');

        $titleTypes = ['Main' => 'Main', 'Sub' => 'Sub', 'Alternative' => 'Alternative', 'Translated' => 'Translated', 'Other' => 'Other'];
        $descriptionTypes = [
            'Abstract' => 'Abstract', 'Methods' => 'Methods', 'Series_information' => 'Series_information',
            'Technical_info' => 'Technical_info', 'Translated' => 'Translated', 'Other' => 'Other'
        ];
        $languages = DB::table('languages')
            ->where('active', true)
            ->pluck('part1', 'part1');

        $messages = DB::table('messages')
            ->pluck('help_text', 'metadata_element');

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

        $contributorTypes = Config::get('enums.contributor_types');
        $nameTypes = Config::get('enums.name_types');

        // $options = License::all('id', 'name_long');
        $licenses = License::select('id', 'name_long', 'link_licence')
            ->orderBy('sort_order')
            ->get();
        // $checkeds = $dataset->licenses->pluck('id')->toArray();
        $checkeds = $dataset->licenses->first()->id;

        $keywordTypes = ['uncontrolled' => 'uncontrolled', 'swd' => 'swd'];

        $referenceTypes = ["DOI", "Handle", "ISBN", "ISSN", "URL", "URN"];
        $referenceTypes = array_combine($referenceTypes, $referenceTypes);

        $relationTypes =  [
            "IsSupplementTo", "IsSupplementedBy", "IsContinuedBy", "Continues",
            "IsNewVersionOf", "IsPartOf", "HasPart", "Compiles", "IsVariantFormOf"
        ];
        $relationTypes = array_combine($relationTypes, $relationTypes);

        return View::make(
            'workflow.editor.edit',
            compact(
                'dataset',
                'titleTypes',
                'descriptionTypes',
                'contributorTypes',
                'nameTypes',
                'languages',
                'messages',
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
            'type' => 'required|min:3',
            'titles.*.value' => 'required|min:4|max:255',
            'titles.*.language' => 'required',
            'descriptions.*.value' => 'required|min:4|max:2500',
            'descriptions.*.language' => 'required',
            'coverage.x_min' => [
                'required',
                'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/',
            ],
            'coverage.y_min' => [
                'required',
                'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/',
            ],
            'coverage.x_max' => [
                'required',
                'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/',
            ],
            'coverage.y_max' => [
                'required',
                'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/',
            ],
            'subjects' => 'required|array|min:3',
            'subjects.*.value' => 'required|string',
            'subjects.*.type' => 'required|string',
            'files' => 'required|array|min:1',
            'files.*.label' => 'required|string',
        ];
        $customMessages = [
            'subjects.min' => 'Minimal three keywords are required.',
            'subjects.*.type.required' => 'The types of all keywords are required.',
            'files.min' => 'Minimal one file is required.',
        ];
        if (null != $request->file('files')) {
            $data = $request->all();
            $files = count($request->input('files')) - 1;
            foreach (range(0, $files) as $index) {
                // $rules['files.' . $index] = 'image|max:2048';
                $rules['files.' . $index . '.file'] = [new RdrFilesize($index + 1), 'file', new RdrFiletypes()];
            }
        }
        $validator = Validator::make($request->all(), $rules, $customMessages);
        if (!$validator->fails()) {
            $dataset = Dataset::findOrFail($id);
            $data = $request->all();
            $input = $request->except(
                'abstracts',
                'licenses',
                'authors',
                'contributors',
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
                        // $dataPerson->name_type = "Organizational";
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
                    $pivot_data = array_merge($pivot_data, $person['pivot']);
                    // if ($galery_id == $request->get('mainPicture')) $pivot_data = ['main' => 1];
                    if (isset($person['id'])) {
                        // $data_to_sync[$person['id']] = $pivot_data;
                        $dataset->persons()->attach($person['id'], $pivot_data);
                    } else {
                        $dataPerson = new Person($person);
                        $dataPerson->status = true;
                        // $dataPerson->name_type = "Organizational";
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
                    // if (isset($key) && $key != 'undefined') {
                    if (isset($formTitle['id'])) {
                        $title = Title::findOrFail($formTitle['id']);
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
                    // if (isset($key) && $key != 'undefined') {
                    if (isset($formAbstract['id'])) {
                        $abstract = Description::findOrFail($formAbstract['id']);
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
                    // if (isset($key) && $key != 'undefined') {
                    if (isset($formReference['id'])) {
                        $reference = DatasetReference::findOrFail($formReference['id']);
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
                    if (isset($formKeyword['id'])) {
                        $subject = Subject::findOrFail($formKeyword['id']);
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
            $files = $request->get('files');
            // $files = $request->file('files');
            if (is_array($files) && count($files) > 0) {
                $index = 1;
                foreach ($files as $key => $formFile) {
                    // if (isset($key) && $key != 'undefined') {
                    if (isset($formFile['id'])) {
                        $file = File::findOrFail($formFile['id']);
                        $file->label = $formFile['label'];
                        if ($file->isDirty()) {
                            $file->save();
                        }
                    } else {
                        $uploads = $request->file('uploads');
                        $fileIndex = $formFile['file'];
                        $file = $uploads[$fileIndex];

                        // $file = new \Illuminate\Http\UploadedFile($file);
                        $label = urldecode($formFile['label']);
                        $sort_order = $index; //$formFile['sort_order'];
                        $fileName = "file-" . time() . '.' . $file->getClientOriginalExtension();
                        $mimeType = $file->getMimeType();
                        $datasetFolder = 'files/' . $dataset->id;
                        $path = $file->storeAs($datasetFolder, $fileName);
                        $size = Storage::size($path);
                        //$path = Storage::putFile('files', $image, $fileName);
                        $fileDb = new File([
                            'path_name' => $path,
                            'file_size' => $size,
                            'mime_type' => $mimeType,
                            'label' => $label,
                            'sort_order' => $sort_order,
                            'visible_in_frontdoor' => 1,
                            'visible_in_oai' => 1,
                        ]);
                        //$test = $file->path_name;
                        $dataset->files()->save($fileDb);
                        $fileDb->createHashValues();
                    }
                    $index++;
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
                && !is_null($dataset->coverage)
            ) {
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
                return redirect()->route('publish.workflow.editor.index');
            }
        } else {
            //TODO Handle validation error
            //pass validator errors as errors object for ajax response
            // return response()->json([
            //     'success' => false,
            //     'errors' => $validator->errors()->all(),
            // ], 422);
            // $errors = $validator->errors();
            return back()->withErrors($validator);
            // return redirect()->route('publish.workflow.submit.edit', ['id' => $id])->withInput()
            // ->withErrors($validator);
        }
        throw new GeneralException(trans('exceptions.backend.dataset.update_error'));
    }

    private function containsOnlyNull($input)
    {
        return empty(array_filter($input, function ($a) {
            return $a !== null;
        }));
    }

    public function download($id)
    {
        //$report = $this->report->find($id);
        $file = File::findOrFail($id);
        $file_path = public_path('storage/' . $file->path_name);
        $ext = \Illuminate\Support\Facades\File::extension($file_path);
        return response()->download($file_path, $file->label . "." . $ext, ['Content-Type:' . $file->mime_type]);
        // return response()->download($file_path, $file->label, ['Content-Type:' . $file->mime_type]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function approve($id): \Illuminate\Contracts\View\View
    {
        $dataset = Dataset::with('user:id,login')->findOrFail($id);

        $reviewers = User::whereHas('roles', function ($q) {
            $q->where('name', 'reviewer');
        })->pluck('login', 'id');

        return View::make('workflow.editor.approve', [
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
            $input['reject_reviewer_note'] = null;
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
    public function reject($id): \Illuminate\Contracts\View\View
    {
        $dataset = Dataset::with('user:id,login')->findOrFail($id);
        return View::make('workflow.editor.reject', [
            'dataset' => $dataset,
        ]);
    }

    public function rejectUpdate(Request $request, $id)
    {
        $this->validate($request, [
            'reject_editor_note' => 'required|min:10|max:500',
            'server_state' => 'required',
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

    /**
     * Display the specified dataset for publishing.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function publish($id): \Illuminate\Contracts\View\View
    {
        $dataset = Dataset::query()
            ->with([
                'titles',
                'persons' => function ($query) {
                    $query->wherePivot('role', 'author');
                },
            ])->findOrFail($id);

        return View::make('workflow.editor.publish', [
            'dataset' => $dataset,
        ]);
    }

    public function publishUpdate(Request $request, $id)
    {
        $dataset = Dataset::findOrFail($id);

        $max = Dataset::max('publish_id');
        $publish_id = 0;
        if ($max != null) {
            $publish_id = $max + 1;
        } else {
            $publish_id = $publish_id + 1;
        }

        $input = $request->all();
        $input['server_state'] = 'published';
        $input['server_date_published'] = Carbon::now()->toDateTimeString(); // Produces something like "2019-03-11 12:25:00"
        $input['publish_id'] = $publish_id;

        if ($dataset->update($input)) {
            // event(new PageUpdated($page));
            return redirect()
                ->route('publish.workflow.editor.index')
                ->with('flash_message', 'You have successfully published the dataset!');
        }
        throw new GeneralException(trans('exceptions.publish.publish.update_error'));
    }

    /**
     * Display the specified dataset for publishing.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function doiCreate($id): \Illuminate\Contracts\View\View
    {
        $dataset = Dataset::query()
            ->with([
                'titles',
                'persons' => function ($query) {
                    $query->wherePivot('role', 'author');
                },
            ])->findOrFail($id);

        return View::make('workflow.editor.doi', [
            'dataset' => $dataset,
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function doiStore(Request $request, $publish_id)
    {
        $dataId = $publish_id; //$request->input('publish_id');
        // Setup stylesheet
        $this->loadStyleSheet(public_path() . '/prefixes/doi_datacite.xslt');

        // set timestamp
        $date = new \DateTime();
        $unixTimestamp = $date->getTimestamp();
        $this->proc->setParameter('', 'unixTimestamp', $unixTimestamp);

        $prefix = "";
        $base_domain = "";
        $datacite_environment = config('tethys.datacite_environment');
        if ($datacite_environment == "debug") {
            $prefix =  config('tethys.datacite_test_prefix');
            $base_domain = config('tethys.test_base_domain');
        } elseif ($datacite_environment == "production") {
            $prefix = config('tethys.datacite_prefix');
            $base_domain = config('tethys.base_domain');
        }
        // $prefix = config('tethys.datacite_prefix');
        $this->proc->setParameter('', 'prefix', $prefix);

        $repIdentifier = "tethys";
        $this->proc->setParameter('', 'repIdentifier', $repIdentifier);

        $this->xml->appendChild($this->xml->createElement('Datasets'));
        $dataset = Dataset::where('publish_id', '=', $dataId)->firstOrFail();
        if (is_null($dataset)) {
            throw new OaiModelException('Dataset is not available for registering DOI!', OaiModelError::NORECORDSMATCH);
        }
        $dataset->fetchValues();
        $xmlModel = new \App\Library\Xml\XmlModel();
        $xmlModel->setModel($dataset);
        $xmlModel->excludeEmptyFields();
        $cache = ($dataset->xmlCache) ? $dataset->xmlCache : new \App\Models\XmlCache();
        $xmlModel->setXmlCache($cache);
        $domNode = $xmlModel->getDomDocument()->getElementsByTagName('Rdr_Dataset')->item(0);
        $node = $this->xml->importNode($domNode, true);
        $this->addSpecInformation($node, 'data-type:' . $dataset->type);

        $this->xml->documentElement->appendChild($node);
        $xmlMeta = $this->proc->transformToXML($this->xml);
        // Log::alert($xmlMeta);
        //create doiValue and correspunfing landingpage of tehtys
        $doiValue = $prefix . '/tethys.' . $dataset->publish_id;
        // $appUrl = config('app.url');
        // $landingPageUrl = $base_domain . "/dataset/" . $dataset->publish_id;
        $landingPageUrl = 'https://doi.' . get_domain($base_domain) . "/" . $prefix . "/tethys." . $dataset->publish_id;
        $response = $this->doiClient->registerDoi($doiValue, $xmlMeta, $landingPageUrl);
        // if operation successful, store dataste identifier
        if ($response->getStatusCode() == 201) {
            $doi = new DatasetIdentifier();
            $doi['value'] = $doiValue; //$landingPageUrl;
            $doi['dataset_id'] = $dataset->id;
            $doi['type'] = "doi";
            $doi['status'] = "findable";
            if ($doi->save()) {
                // touch doi (and dataset) for solr
                $doi->touch();
                // update server_date_modified for triggering nex xml cache (doi interface)
                // $time = new \Illuminate\Support\Carbon();
                // $dataset->server_date_modified = $time;
                // $dataset->save();
                // dataset gets automatically updated because of touches array
                return redirect()
                    ->route('publish.workflow.editor.index')
                    ->with('flash_message', 'You have successfully created a DOI for the dataset!');
            }
        } else {
            $message = 'unexpected DataCite MDS response code ' . $response->getStatusCode();
            // $this->log($message, 'err');
            throw new GeneralException($message);
        }
    }

    /**
     * Load an xslt stylesheet.
     *
     * @return void
     */
    private function loadStyleSheet($stylesheet)
    {
        $this->xslt = new \DomDocument;
        $this->xslt->load($stylesheet);
        $this->proc->importStyleSheet($this->xslt);
        if (isset($_SERVER['HTTP_HOST'])) {
            $this->proc->setParameter('', 'host', $_SERVER['HTTP_HOST']);
        }
        //$this->proc->setParameter('', 'server', $this->getRequest()->getBaseUrl());
    }

    private function addSpecInformation(\DOMNode $document, $information)
    {
        $setSpecAttribute = $this->xml->createAttribute('Value');
        $setSpecAttributeValue = $this->xml->createTextNode($information);
        $setSpecAttribute->appendChild($setSpecAttributeValue);

        $setSpecElement = $this->xml->createElement('SetSpec');
        //$setSpecElement =new \DOMElement("SetSpec");
        $setSpecElement->appendChild($setSpecAttribute);
        $document->appendChild($setSpecElement);
    }
}
