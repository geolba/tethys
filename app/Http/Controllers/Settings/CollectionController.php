<?php
namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\CollectionRole;
use App\Http\Requests\Collection\CollectionRequest;

class CollectionController extends Controller
{

    public function __construct()
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$collections = Collection::take(10)->get();
        //$collections = Collection::get();
        $collections = Collection::with('documents')
        ->paginate(8); //get();
        return view('settings.collection.collection', compact('collections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  Collection $collection
     * @return \Illuminate\Http\Response
     */
    public function show(Collection $collection)
    {
        $collection = Collection::findOrFail($collection->id);
        //get child collections
        $collections = $collection
        ->children()
        ->paginate(10);
        return view('settings.collection.collection', compact('collections'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $collection = Collection::findOrFail($id);
        $collectionroles = CollectionRole::pluck('name', 'id');
        return view('settings.collection.edit', compact('collection', 'collectionroles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CollectionRequest $request, $id)
    {
        $collection = Collection::findOrFail($id);
        $input = $request->all();
        //$input = $request->except('licenses', 'titles');
        $collection->update($input);
        session()->flash('flash_message', 'You have updated the collection "' . $collection->name . '"!');
        if ($collection->parent()->exists()) {
            return redirect()->route('settings.collection.show', $collection->parent);
        } else {
            $test = $collection->collectionrole;
            return redirect()->route('settings.collectionrole.show', $collection->collectionrole);
        }
        //return redirect()->route('settings.collectionrole.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        //
    }
}
