<?php
namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Http\Requests\Person\CreatePersonRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PersonController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('role:reviewer');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        //$persons = Person::get();
        $persons = Person::with('documents')->get();
        return view('settings.person.person', compact('persons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add(): View
    {
        return view('settings.person.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePersonRequest $request)
    {
        $input = $request->all();
        $input['registered_at'] = time();
        Person::create($input);
        session()->flash('flash_message', 'You have added 1 person!');
        return redirect()->route('settings.person');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $person = Person::with('documents')->findOrFail($id);
        return view('settings.person.edit', compact('person'));
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
        $this->validate(request(), [
            'academic_title' => 'nullable|min:2|max:255',
            'last_name' => 'required|min:3|max:255|unique_with:persons,first_name,date_of_birth,' . $id,
            'first_name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:persons,email,' . $id,
            'identifier_orcid' => 'nullable|min:19|max:50',
            'status' => 'required|boolean',
            'date_of_birth' => 'nullable|date'
        ]);
        $person = Person::findOrFail($id);
        $input = $request->all();
        $person->update($input);
        session()->flash('flash_message', 'You have updated 1 person!');
        return redirect()->route('settings.person');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $person = Person::with('documents')->findOrFail($id);
        if ($person->documents->count() > 0) {
            // $person->documents()->detach();
            // $person->delete();
            session()->flash('flash_message', 'You cannot delete this person!');
        } else {
            $person->delete();
            session()->flash('flash_message', 'You have deleted 1 person!');
        }
        return redirect()->route('settings.person');
    }

     /**
     * deactivate author, submitter etc....
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function down($id): RedirectResponse
    {
        $person = Person::findOrFail($id);
        $person->update(['status' => 0, 'registered_at' => 00000000]);
        session()->flash('flash_message', 'person has been deactivated!');
        return redirect()->route('settings.person');
    }

     /**
     * activate author, submitter etc....
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function up($id): RedirectResponse
    {
        $dateNow = time();
        $person = Person::findOrFail($id);
        $person->update(['status' => 1, 'registered_at' => $dateNow]);
        session()->flash('flash_message', 'person has been activated!');
        return redirect()->route('settings.person');
    }
}
