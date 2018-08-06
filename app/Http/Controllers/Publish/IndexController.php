<?php
//https://www.5balloons.info/multi-page-step-form-in-laravel-with-validation/
namespace App\Http\Controllers\Publish;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Dataset;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createStep1(Request $request)
    {
        $dataset = $request->session()->get('dataset');
        return view('publish.create-step1', compact('dataset', $dataset));
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
            'rights' => 'required|boolean|in:1'
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
            "TitleAbstract.Language" => 'required|min:3'
        ]);
        $optionalData = $request->all();
        
        // $dataset = $request->except('rights', '_token', 'input_img');

        $dataset = $request->session()->get('dataset');
        
        //update dataset with validated data
        $dataset['Type'] =  $validatedData['Type'];
        $dataset['BelongsToBibliography'] =  $validatedData['BelongsToBibliography'];
        $dataset['TitleMain']['Value'] =  $validatedData['TitleMain']['Value'];
        $dataset['TitleMain']['Language'] =  $validatedData['TitleMain']['Language'];
        $dataset['TitleAbstract']['Value'] =  $validatedData['TitleAbstract']['Value'];
        $dataset['TitleAbstract']['Language'] =  $validatedData['TitleAbstract']['Language'];
        if (isset($optionalData['CreatingCorporation'])) {
            $dataset['CreatingCorporation'] = $optionalData['CreatingCorporation'];
        }
        if (isset($optionalData['EmbargoDate'])) {
            $dataset['EmbargoDate'] = $optionalData['EmbargoDate'];
        }

        if (!isset($dataset['DatasetFile'])) {
            $this->validate($request, [
                'dataset_file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
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
    public function store(Request $request)
    {
        $dataset = $request->session()->get('dataset');
        // $product->save();
        // return redirect('/dataset');
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
