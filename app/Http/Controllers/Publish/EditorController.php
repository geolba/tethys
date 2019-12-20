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
use App\Models\DatasetReference;
use App\Models\Subject;
use App\Models\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\View\View;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
// use App\Models\Coverage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use \Exception;

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
            $query->whereIn('server_state', ['editor_accepted', 'rejected_reviewer', 'reviewed'])
                  ->where('editor_id', $user_id);
        })
        ->orderBy('server_date_modified', 'desc')
        ->get();
        return View::make('workflow.editor.index', compact($datasets));
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
        // $options = License::all('id', 'name_long');
        $licenses = License::select('id', 'name_long', 'link_licence')
        ->orderBy('sort_order')
        ->get();
        //$checkeds = $dataset->licenses->pluck('id')->toArray();
        $checkeds = $dataset->licenses->first()->id;

        $keywordTypes = ['uncontrolled' => 'uncontrolled', 'swd' => 'swd'];

        $referenceTypes = ["rdr-id", "arXiv", "bibcode", "DOI", "EAN13", "EISSN", "Handle", "IGSN", "ISBN", "ISSN", "ISTC", "LISSN", "LSID", "PMID", "PURL", "UPC", "URL", "URN"];
        $referenceTypes = array_combine($referenceTypes, $referenceTypes);

        $relationTypes = ["IsCitedBy", "Cites", "IsSupplementTo", "IsSupplementedBy", "IsContinuedBy", "Continues", "HasMetadata", "IsMetadataFor","IsNewVersionOf", "IsPreviousVersionOf", "IsPartOf", "HasPart", "IsReferencedBy", "References"];
        // "IsDocumentedBy", "Documents", "IsCompiledBy", "Compiles", "IsVariantFormOf", "IsOriginalFormOf", "IsIdenticalTo", "IsReviewedBy", "Reviews", "IsDerivedFrom", "IsSourceOf"];
        $relationTypes = array_combine($relationTypes, $relationTypes);

    
        return View::make(
            'workflow.editor.edit',
            compact(
                'dataset',
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
        if (!$validator->fails()) {
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
                return redirect()->route('publish.workflow.editor.index');
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
            }
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
            $publish_id = $max +1;
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
}
