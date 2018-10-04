<?php
namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CollectionRole;
use App\Models\Collection;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CollectionRoleController extends Controller
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
        $collectionroles  = CollectionRole::query() //with('collections')
        ->with(['collections' => function ($query) {
            $query->whereNull('parent_id');
        }])
        ->get();
        return view('settings.collectionrole.index', compact('collectionroles'));
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
     * @param  CollectionRole  $collectionrole
     * @return \Illuminate\Http\Response
     */
    public function show(CollectionRole $collectionrole)
    {
        $collectionrole = CollectionRole::findOrFail($collectionrole->id);
        //$collections = Collection::query()
        $collections = $collectionrole
        ->collections()
        ->whereNull('parent_id')
        // ->where('role_id', '=', $id)
        ->paginate(10);
        return view('settings.collectionrole.show', compact('collections'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $collectionrole = CollectionRole::findOrFail($id);
        return view('settings.collectionrole.edit', compact('collectionrole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $project = CollectionRole::findOrFail($id);
        $input = $request->all();
        $project->update($input);
        session()->flash('flash_message', 'You have updated the collection role!');
        return redirect()->route('settings.collectionrole.index');
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

     /**
     * deactivate author, submitter etc....
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function hide($id): RedirectResponse
    {
        $collectionrole = CollectionRole::findOrFail($id);
        $collectionrole->update(['visible' => 0]);
        session()->flash(
            'flash_message',
            'Visibility of collection role "' .  $collectionrole->name . '" has changed successfully.'
        );
        return redirect()->route('settings.collectionrole.index');
    }

       /**
     * activatew author, submitter etc....
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function up($id): RedirectResponse
    {
        // $dateNow = time();
        $collectionrole = CollectionRole::findOrFail($id);
        $collectionrole->update(['visible' => 1]);
        session()->flash(
            'flash_message',
            'Visibility of collection role "' .  $collectionrole->name . '" has changed successfully.'
        );
        return redirect()->route('settings.collectionrole.index');
    }
}
