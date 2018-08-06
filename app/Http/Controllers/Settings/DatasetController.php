<?php
namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Dataset;
use App\Project;
use App\License;
use App\Title;
use App\Http\Requests\DocumentRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DatasetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) : View
    {
                
        $searchType = $request->input('searchtype');
        $builder = Dataset::query();
        //$registers = array();

        $filter = $request->input('search');
        $state =  (strlen($request->input('state')) > 0) ? $request->input('state') : "published";

        $data =  $request->all();

        if ($searchType == "simple") {
            if (strlen($filter) > 0) {
                //$builder->where('field1', '=', $criteria1);
                $builder->whereHas('titles', function ($query) use ($filter) {
                    $query->where('value', 'LIKE', '%' . $filter . '%');
                });
                // ::with(['titles' => function ($query) use($filter) {
                // $query->where('value', 'LIKE', '%' . $filter . '%');
                // }])
            }
        }
        $builder->where('server_state', $state);
      
        
        //$perPage = $request->get('perPage', 20);
        $documents = $builder
        ->paginate(8);
        return view('settings.document.document', compact('documents'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $document = Dataset::findOrFail($id);
        $document->load('titles');
        $document->load('abstracts');
        return view('settings.document.show', compact('document'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $categories = Category::lists('category', 'id');
        // $shelves = Shelf::lists('shelf', 'id');
        // $tanggal = date('Y-m-d');
        // $ambilTahun = substr($tanggal, 0, 4);
        // for($tahun = 1990; $tahun <= $ambilTahun; $tahun++){
        // $years[] = $tahun;
        // }
        // return view('lms.settings.book.add', compact('categories', 'shelves', 'years'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DocumentRequest $request)
    {
        // $input = $request->all();
        // $book = Book::create($input);
        // session()->flash('flash_message', 'You have been addded 1 dataset!');
        // return redirect()->route('settings.book');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) : View
    {
        $document = Dataset::findOrFail($id);
        $document->load('licenses', 'titles', 'abstracts');

        $projects = Project::pluck('label', 'id');
        
        $datum = date('Y-m-d');
        $nowYear = substr($datum, 0, 4);
        $years = array();
        for ($jahr = 1990; $jahr <= $nowYear; $jahr++) {
            $years[$jahr] = $jahr;
        }

        $languages = DB::table('languages')
        ->where('active', true)
        ->pluck('part2_t', 'part2_t');

        //$options = License::all();
        $options = License::all('id', 'name_long');
        $checkeds = $document->licenses->pluck('id')->toArray();

        //$shelves = Shelf::pluck('shelf', 'id');
        // $datum = date('Y-m-d');
        // $nowYear = substr($datum, 0, 4);
        // $years = array();
        // for($jahr = 1990; $jahr <= $nowYear; $jahr++){
        // $years[$jahr] = $jahr;
        // }
        return view('settings.document.edit', compact('document', 'projects', 'options', 'checkeds', 'years', 'languages'));
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
    public function update(DocumentRequest $request, $id) : RedirectResponse
    {
        $document = Dataset::findOrFail($id);
        //$input = $request->all();
        $input = $request->except('licenses', 'titles');
        $document->update($input);
        // $document->type = $input['type'];
        // $document->thesis_year_accepted = $input['thesis_year_accepted'];
        // $document->project_id = $input['project_id'];
        // $document->save();

        $licenses = $request->input('licenses');
        //$licenses = $input['licenses'];
        $document->licenses()->sync($licenses);

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
                $abstract = Title::findOrFail($key);
                $abstract->value = $formAbstract['value'];
                $abstract->language = $formAbstract['language'];
                $abstract->save();
            }
        }

        session()->flash('flash_message', 'You have updated 1 dataset!');
        return redirect()->route('settings.document');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) : RedirectResponse
    {
        // $document = Document::findOrFail($id);
        // $document->delete();
        // session()->flash('flash_message', 'You have been deleted 1 dataset!');
        return redirect()->route('settings.document');
    }
}
