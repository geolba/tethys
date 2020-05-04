<?php
//https://www.5balloons.info/multi-page-step-form-in-laravel-with-validation/
namespace App\Http\Controllers\Publish;

use App\Http\Controllers\Controller;
use App\Models\Coverage;
use App\Models\Dataset;
use App\Models\DatasetReference;
use App\Models\Description;
use App\Models\File;
use App\Models\License;
use App\Models\Page;
use App\Models\Person;
use App\Models\Project;
use App\Models\Subject;
use App\Models\Title;
use App\Rules\RdrFilesize;
use App\Rules\RdrFiletypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;

class IndexController extends Controller
{
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
        ->whereIn('server_state', ['inprogress', 'unpublished'])
            ->get();
        return view('publish.index', compact('datasets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createStep1(Request $request)
    {
        #$dataset = $request->session()->get('dataset');
        $licenses = License::select('id', 'name_long', 'link_licence')
            ->orderBy('sort_order')
            ->get();
        $languages = DB::table('languages')
            ->where('active', true)
            ->pluck('part1', 'part1');
        // ->toArray();

        $messages = DB::table('messages')
            ->pluck('help_text', 'metadata_element');

        $projects = Project::pluck('label', 'id');
        $relatedIdentifierTypes = ["doi", "handle", "isbn", "issn", "url", "urn"];
        $relatedIdentifierTypes = array_combine($relatedIdentifierTypes, $relatedIdentifierTypes);

        $relationTypes = ["IsSupplementTo", "IsSupplementedBy", "IsContinuedBy", "Continues",
            "IsNewVersionOf", "IsPartOf", "HasPart", "Compiles", "IsVariantFormOf"];
        $relationTypes = array_combine($relationTypes, $relationTypes);

        $titleTypes = ['Sub' => 'Sub', 'Alternative' => 'Alternative', 'Translated' => 'Translated', 'Other' => 'Other'];

        $keywordTypes = ['uncontrolled' => 'uncontrolled'];

        $descriptionTypes = ['Methods' => 'Methods', 'Series_information' => 'Series_information',
            'Technical_info' => 'Technical_info', 'Translated' => 'Translated', 'Other' => 'Other'];

        $page = Page::query()->where('page_slug', 'terms-and-conditions')->firstOrFail();

        $contributorTypes = Config::get('enums.contributor_types');

        //$relationTypes = array('updates' => 'updates', 'updated-by' => 'updated-by', 'other' => 'other');
        return view(
            'publish.create-step1',
            compact(
                'licenses',
                'languages',
                'messages',
                'projects',
                'relatedIdentifierTypes',
                'relationTypes',
                'titleTypes',
                'keywordTypes',
                'descriptionTypes',
                'contributorTypes',
                'page'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeStep1(Request $request)
    {
        $validatedData = $this->validate($request, [
            'Type' => 'required|min:4',
            'rights' => 'required|boolean|in:1',
        ]);
        // $validatedData = $request->validate([
        //     'name' => 'required|unique:products',
        //     'amount' => 'required|numeric',
        //     'company' => 'required',
        //     'available' => 'required',
        //     'description' => 'required',
        // ]);
        if (empty($request->session()->get('dataset'))) {
            // $dataset = new Dataset();
            //$dataset->fill($validatedData);
            // $dataset->type = $request->input('type');
            $dataset = $request->except('rights', '_token');
            $request->session()->put('dataset', $dataset);
        } else {
            $dataset = $request->session()->get('dataset');
            //$dataset->fill($validatedData);
            $dataset['Type'] = $request->input('Type');
            $request->session()->put('dataset', $dataset);
        }
        return redirect()->route('dataset.create2');
    }

    /**
     * Show the step 2 Form for creating a new dataset.
     *
     * @return \Illuminate\Http\Response
     */
    public function createStep2(Request $request)
    {
        //if no dataset is'nt in session variable return to step1
        if (empty($request->session()->get('dataset'))) {
            return redirect()->route('dataset.create1');
        }

        $dataset = $request->session()->get('dataset');

        //fill select variable
        $languages = DB::table('languages')
            ->where('active', true)
            ->pluck('part2_t', 'part2_t');

        return view('publish.create-step2', compact('dataset', 'languages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeStep2(Request $request)
    {
        $validatedData = $this->validate($request, [
            'Type' => 'required|min:4',
            'BelongsToBibliography' => 'required|boolean',
            "TitleMain.Value" => 'required|min:5|max:255',
            "TitleMain.Language" => 'required|min:3',
            "TitleAbstract.Value" => 'required|min:5|max:255',
            "TitleAbstract.Language" => 'required|min:3',
        ]);
        $optionalData = $request->all();

        // $dataset = $request->except('rights', '_token', 'input_img');

        $dataset = $request->session()->get('dataset');

        //update dataset with validated data
        $dataset['Type'] = $validatedData['Type'];
        $dataset['BelongsToBibliography'] = $validatedData['BelongsToBibliography'];
        $dataset['TitleMain']['Value'] = $validatedData['TitleMain']['Value'];
        $dataset['TitleMain']['Language'] = $validatedData['TitleMain']['Language'];
        $dataset['TitleAbstract']['Value'] = $validatedData['TitleAbstract']['Value'];
        $dataset['TitleAbstract']['Language'] = $validatedData['TitleAbstract']['Language'];
        if (isset($optionalData['CreatingCorporation'])) {
            $dataset['CreatingCorporation'] = $optionalData['CreatingCorporation'];
        }
        if (isset($optionalData['EmbargoDate'])) {
            $dataset['EmbargoDate'] = $optionalData['EmbargoDate'];
        }

        if (!isset($dataset['DatasetFile'])) {
            $this->validate($request, [
                'dataset_file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            //update session variable
            // $dataset = $request->session()->get('dataset');
            $image = $request->file('dataset_file');
            $fileName = "productImage-" . time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs(
                'files',
                $fileName
            );
            // $path = Storage::putFile('files', $image, $fileName);

            //$dataset = $request->session()->get('dataset');
            $dataset['DatasetFile'] = $fileName;
        }
        $request->session()->put('dataset', $dataset);
        return redirect()->route('dataset.create3');
    }

    /**
     * Show the Product Review page
     *
     * @return \Illuminate\Http\Response
     */
    public function createStep3(Request $request)
    {
        //if no dataset is'nt in session variable return to step1
        if (empty($request->session()->get('dataset'))) {
            return redirect()->route('dataset.create1');
        }
        $dataset = $request->session()->get('dataset');
        return view('publish.create-step3', compact('dataset'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeTest(Request $request)
    {
        //$data = $request->all();
        $data = json_decode($request->getContent(), true);

        $validator = Validator::make($data, [
            'type' => 'required|min:4',
            'belongs_to_bibliography' => 'required|boolean',
        ]);
        if (!$validator->fails()) {
            //TODO Handle your data
            return response()->json(array(
                'response' => 'success'));
        } else {
            //TODO Handle your error
            //pass validator errors as errors object for ajax response
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }
    }

    //https://laravel.io/forum/06-11-2014-how-to-save-eloquent-model-with-relations-in-one-go
    //attach vs save https://stackoverflow.com/questions/35756469/laravel-5-many-to-many-attach-versus-save
    public function store(Request $request)
    {
        $data = $request->all();
        // $validatedData = $this->validate($request, [
        //     'type' => 'required|min:4',
        //     'rights' => 'required|boolean|in:1',
        // ]);
        $rules = [
            // 'server_state' => 'required',
            'type' => 'required|min:3',
            'rights' => 'required|boolean|in:1',
            'belongs_to_bibliography' => 'required|boolean',
            'title_main.value' => 'required|min:4|max:255',
            'title_main.language' => 'required',
            'titles.*.value' => 'required|min:4|max:255',
            'abstract_main.value' => 'required|min:4|max:2500',
            'abstract_main.language' => 'required',
            'descriptions.*.value' => 'required|min:4|max:2500',
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
            'keywords' => 'required|array|min:3',
            'keywords.*.value' => 'required|string',
            'keywords.*.type' => 'required|string',
            'files' => 'required|array|min:1',
            'files.*.label' => 'required|string',
            // 'authors.*.email' => 'required|email|max:50|unique:persons,email',
        ];
        $customMessages = [
            'keywords.required' => 'Minimal three keywords are required.',
            'keywords.*.type.required' => 'The types of all keywords are required.',
            'files.min' => 'Minimal one file is required.',
            'authors.*.email.unique' => 'email of new author is not unique in database.',
        ];
        if (isset($data['authors'])) {
            foreach ($request->get('authors') as $index => $person) {
                // echo "Item at index {$index} has sm_id value {$item->sm_id}";
                if ($person['status'] == false) {
                    $rules['authors.' . $index . '.email'] = ['email', 'required', 'unique:persons,email'];
                }
            }
        }
        if (null != $request->file('files')) {
            $files = count($request->file('files')) - 1;
            foreach (range(0, $files) as $index) {
                // $rules['files.' . $index] = 'image|max:2048';
                $rules['files.' . $index . '.file'] = [new RdrFilesize($index + 1), 'file', 'required', new RdrFiletypes()];
            }
        }
        $validator = Validator::make($request->all(), $rules, $customMessages);
        if (!$validator->fails()) {
            //store dataset todo
            //$data = $request->all();
            $input = $request->except('files', 'licenses', 'abstract_main', 'title_main', 'references', 'titles');
            // array_push($input, "Himbeere");
            // $input += ['server_state' => 'created' ];
            if (isset($data['server_state'])) {
                $input['server_state'] = $data['server_state'];
            } else {
                $input['server_state'] = 'inprogress';
            }
            $dataset = new Dataset($input);

            DB::beginTransaction(); //Start transaction!
            try {
                $dataset->save();

                //store related files
                if (isset($data['files'])) {
                    foreach ($data['files'] as $uploadedFile) {
                        $file = $uploadedFile['file'];
                        $label = urldecode($uploadedFile['label']);
                        $sorting = $uploadedFile['sorting'];
                        $fileName = "file-" . time() . '.' . $file->getClientOriginalExtension();
                        $mimeType = $file->getMimeType();
                        $datasetFolder = 'files/' . $dataset->id;
                        $path = $file->storeAs($datasetFolder, $fileName);
                        $size = Storage::size($path);
                        //$path = Storage::putFile('files', $image, $fileName);
                        $file = new File([
                            'path_name' => $path,
                            'file_size' => $size,
                            'mime_type' => $mimeType,
                            'label' => $label,
                            'sort_order' => $sorting,
                            'visible_in_frontdoor' => 1,
                            'visible_in_oai' => 1,
                        ]);
                        //$test = $file->path_name;
                        $dataset->files()->save($file);
                        $file->createHashValues();
                    }
                }

                //store licenses:
                $licenses = $request->input('licenses');
                $dataset->licenses()->sync($licenses);

                $data_to_sync = [];
                //store authors
                if (isset($data['authors'])) {
                    //$data_to_sync = [];
                    foreach ($request->get('authors') as $key => $person) {
                        $pivot_data = ['role' => 'author', 'sort_order' => $key + 1];
                        // if ($galery_id == $request->get('mainPicture')) $pivot_data = ['main' => 1];
                        if (isset($person['id'])) {
                            //$data_to_sync[$person['id']] = $pivot_data;
                            $dataset->persons()->attach($person['id'], $pivot_data);
                        } else {
                            $dataPerson = new Person($person);
                            $dataPerson->status = true;
                            $dataPerson->name_type = "Organizational";
                            $dataset->persons()->save($dataPerson, $pivot_data);
                        }
                    }
                    //$dataset->persons()->sync($data_to_sync);
                }

                //store contributors
                // if (isset($data['contributors'])) {
                //     //$data_to_sync = [];
                //     foreach ($request->get('contributors') as $key => $contributor_id) {
                //         $pivot_data = ['role' => 'contributor', 'sort_order' => $key + 1];
                //         //$data_to_sync[$contributor_id] = $pivot_data;
                //         $dataset->persons()->attach($contributor_id, $pivot_data);
                //     }
                //     //$dataset->persons()->sync($data_to_sync);
                // }
                if (isset($data['contributors'])) {
                    //$data_to_sync = [];
                    foreach ($request->get('contributors') as $key => $contributor) {
                        $pivot_data = [
                            'role' => 'contributor',
                            'sort_order' => $key + 1,
                            'contributor_type' => $contributor['contributor_type']
                        ];
                        if (isset($contributor['id'])) {
                            //$data_to_sync[$person['id']] = $pivot_data;
                            $dataset->persons()->attach($contributor['id'], $pivot_data);
                        } else {
                            $dataContributor = new Person($contributor);
                            $dataContributor->status = true;
                            $dataContributor->name_type = "Organizational";
                            $dataset->persons()->save($dataContributor, $pivot_data);
                        }
                    }
                }

                //store submitters
                // if (isset($data['submitters'])) {
                //     //$data_to_sync = [];
                //     foreach ($request->get('submitters') as $key => $submitter_id) {
                //         $pivot_data = ['role' => 'submitter', 'sort_order' => $key + 1];
                //         $data_to_sync[$submitter_id] = $pivot_data;
                //     }
                //     //$dataset->persons()->sync($data_to_sync);
                // }
                //$dataset->persons()->sync($data_to_sync);

                //save main title:
                if (isset($data['title_main'])) {
                    $formTitle = $request->input('title_main');
                    $title = new Title();
                    $title->value = $formTitle['value'];
                    $title->language = $formTitle['language'];
                    $title->type = 'Main';
                    //$dataset->addMainTitle($title);
                    $dataset->titles()->save($title);
                }

                //save additional titles
                if (isset($data['titles'])) {
                    foreach ($request->get('titles') as $key => $title) {
                        $titleReference = new Title($title);
                        $dataset->titles()->save($titleReference);
                    }
                }

                //save main abstract:
                if (isset($data['abstract_main'])) {
                    $formAbstract = $request->input('abstract_main');
                    $abstract = new Description();
                    $abstract->value = $formAbstract['value'];
                    $abstract->language = $formAbstract['language'];
                    $abstract->type = 'Abstract';
                    //$dataset->addMainAbstract($abstract);
                    $dataset->abstracts()->save($abstract);
                }

                //save additional descriptions
                if (isset($data['descriptions'])) {
                    foreach ($request->get('descriptions') as $key => $description) {
                        $descriptionReference = new Description($description);
                        $dataset->abstracts()->save($descriptionReference);
                    }
                }

                //save references
                if (isset($data['references'])) {
                    foreach ($request->get('references') as $key => $reference) {
                        $dataReference = new DatasetReference($reference);
                        $dataset->references()->save($dataReference);
                    }
                }

                //save keywords
                if (isset($data['keywords'])) {
                    foreach ($request->get('keywords') as $key => $keyword) {
                        $dataKeyword = new Subject($keyword);
                        $dataset->subjects()->save($dataKeyword);
                    }
                }

                // if (isset($data['geolocation'])) {
                //     $formGeolocation = $request->input('geolocation');
                //     if ($formGeolocation['xmin'] !== null && $formGeolocation['ymin'] !== null &&
                //         $formGeolocation['xmax'] !== null && $formGeolocation['ymax'] !== null) {
                //         $geolocation = new GeolocationBox($formGeolocation);
                //         $dataset->geolocation()->save($geolocation);
                //         //$geolocation->dataset()->associate($dataset)->save();
                //     }
                // }

                if (isset($data['coverage'])) {
                    $formCoverage = (array) $request->input('coverage');
                    $coverage = new Coverage($formCoverage);
                    $dataset->coverage()->save($coverage);
                    //$coverage->dataset()->associate($dataset)->save();
                }

                // Create relation between Dataset and actual User.
                $user = Auth::user();
                $dataset->user()->associate($user)->save();

                // $error = 'Always throw this error';
                // throw new \Exception($error);

                // all good//commit everything
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                if (isset($datasetFolder)) {
                    Storage::deleteDirectory($datasetFolder);
                }
                return response()->json([
                    'success' => false,
                    'error' => [
                        'code' => $e->getCode(),
                        'message' => $e->getMessage(),
                    ],
                ], 422);
                //throw $e;
            } catch (\Throwable $e) {
                DB::rollback();
                if (isset($datasetFolder)) {
                    Storage::deleteDirectory($datasetFolder);
                }
                return response()->json([
                    'success' => false,
                    'error' => [
                        'code' => $e->getCode(),
                        'message' => $e->getMessage(),
                    ],
                ], 422);
                //throw $e;
            }

            return response()->json(array(
                'success' => true,
                'edit' => route('publish.workflow.submit.edit', ['id' => $dataset->id]),
                'release' => route('publish.workflow.submit.release', ['id' => $dataset->id]),
                'delete' => route('publish.workflow.submit.delete', ['id' => $dataset->id]),
            ));
        } else {
            //TODO Handle validation error
            //pass validator errors as errors object for ajax response
            $errors = $validator->errors();
            return response()->json([
                'success' => false,
                'errors' => $errors,
            ], 422);
        }
    }

    public function storeTest1(Request $request)
    {
        $data = $request->all();
        // $validatedData = $this->validate($request, [
        //     'type' => 'required|min:4',
        //     'rights' => 'required|boolean|in:1',
        // ]);
        $rules = [
            'server_state' => 'required',
            'type' => 'required|min:5',
            'rights' => 'required|boolean|in:1',
            'belongs_to_bibliography' => 'required|boolean',
            'title_main.value' => 'required|min:5',
            'title_main.language' => 'required',
            'abstract_main.value' => 'required|min:5',
            'abstract_main.language' => 'required',
        ];
        if (null != $request->file('files')) {
            $files = count($request->file('files')) - 1;
            foreach (range(0, $files) as $index) {
                // $rules['files.' . $index] = 'image|max:2048';
                $rules['files.' . $index . '.file'] = ['required', 'file', new RdrFiletypes(), new RdrFilesize($index)];
            }
        }
        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            //store dataset todo
            //$data = $request->all();
            $input = $request->except('files', 'licenses', 'abstract_main', 'title_main', 'references');
            // array_push($input, "Himbeere");
            $dataset = new Dataset($input);

            DB::beginTransaction(); //Start transaction!
            try {
                // $dataset->save();

                //store related files
                if (isset($data['files'])) {
                    foreach ($data['files'] as $uploadedFile) {
                        $file = $uploadedFile['file'];
                        $label = urldecode($uploadedFile['label']);
                        $sorting = $uploadedFile['sorting'];
                        $fileName = "file-" . time() . '.' . $file->getClientOriginalExtension();
                        $mimeType = $file->getMimeType();
                        $datasetFolder = 'files/' . $dataset->id;
                        // $path = $file->storeAs($datasetFolder, $fileName);
                        // $size = Storage::size($path);
                        //$path = Storage::putFile('files', $image, $fileName);
                        // $file = new File([
                        //     'path_name' => $path,
                        //     'file_size' => $size,
                        //     'mime_type' => $mimeType,
                        //     'label' => $label,
                        //     'sort_order' => $sorting,
                        //     'visible_in_frontdoor' => 1,
                        //     'visible_in_oai' => 1
                        // ]);
                        //$test = $file->path_name;
                        // $dataset->files()->save($file);
                        // $file->createHashValues();
                    }
                }

                //store licenses:
                $licenses = $request->input('licenses');
                //  $dataset->licenses()->sync($licenses);

                //store authors
                if (isset($data['authors'])) {
                    $data_to_sync = [];
                    foreach ($request->get('authors') as $key => $person_id) {
                        $pivot_data = ['role' => 'author', 'sort_order' => $key + 1];
                        // if ($galery_id == $request->get('mainPicture')) $pivot_data = ['main' => 1];
                        $data_to_sync[$person_id] = $pivot_data;
                    }
                    // $dataset->persons()->sync($data_to_sync);
                }

                //store contributors
                if (isset($data['contributors'])) {
                    $data_to_sync = [];
                    foreach ($request->get('contributors') as $key => $contributor_id) {
                        $pivot_data = ['role' => 'contributor', 'sort_order' => $key + 1];
                        $data_to_sync[$contributor_id] = $pivot_data;
                    }
                    // $dataset->persons()->sync($data_to_sync);
                }

                //store submitters
                if (isset($data['submitters'])) {
                    $data_to_sync = [];
                    foreach ($request->get('submitters') as $key => $submitter_id) {
                        $pivot_data = ['role' => 'submitter', 'sort_order' => $key + 1];
                        $data_to_sync[$submitter_id] = $pivot_data;
                    }
                    // $dataset->persons()->sync($data_to_sync);
                }

                //save main title:
                if (isset($data['title_main'])) {
                    $formTitle = $request->input('title_main');
                    $title = new Title();
                    $title->value = $formTitle['value'];
                    $title->language = $formTitle['language'];
                    // $dataset->addMainTitle($title);
                }

                //save main abstract:
                if (isset($data['abstract_main'])) {
                    $formAbstract = $request->input('abstract_main');
                    $abstract = new Title();
                    $abstract->value = $formAbstract['value'];
                    $abstract->language = $formAbstract['language'];
                    // $dataset->addMainAbstract($abstract);
                }

                //save references
                if (isset($data['references'])) {
                    foreach ($request->get('references') as $key => $reference) {
                        $dataReference = new DatasetReference($reference);
                        // $dataset->references()->save($dataReference);
                    }
                }

                // $error = 'Always throw this error';
                // throw new \Exception($error);

                // all good//commit everything
                // DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                if (isset($datasetFolder)) {
                    Storage::deleteDirectory($datasetFolder);
                }
                return response()->json([
                    'success' => false,
                    'error' => [
                        'code' => $e->getCode(),
                        'message' => $e->getMessage(),
                    ],
                ], 422);
                //throw $e;
            } catch (\Throwable $e) {
                DB::rollback();
                if (isset($datasetFolder)) {
                    Storage::deleteDirectory($datasetFolder);
                }
                return response()->json([
                    'success' => false,
                    'error' => [
                        'code' => $e->getCode(),
                        'message' => $e->getMessage(),
                    ],
                ], 422);
                //throw $e;
            }

            return response()->json(array(
                'success' => true,
                'redirect' => route('settings.document.edit', ['id' => $dataset->server_state]),
            ));
        } else {
            //TODO Handle validation error
            //pass validator errors as errors object for ajax response
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all(),
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
