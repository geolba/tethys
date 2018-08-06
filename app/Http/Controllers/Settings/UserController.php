<?php
namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
//use Spatie\Permission\Models\Role;
use App\Role;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //if (! Gate::allows('settings'))
        //{
        //    return abort(401, 'Unauthorized action.');
        //}

        $users = User::orderBy('id', 'DESC')->paginate(5);
        return view('settings.user.user', compact('users'))
                ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$roles = Role::pluck('name','name')->all();
         $roles = Role::all('id', 'name');
        return view('settings.user.create', compact('roles'));
    }

    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(Request $request)
    {
        $this->validate($request, [
            'login' => 'required',
            'email' => 'required|email|unique:accounts',
            'password' => 'required|min:6|confirmed'
            //'roles' => 'required'
        ]);

        //$input = $request->all();
        $input = $request->only(['login', 'email', 'password']); //Retreive the name, email and password fields
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
       

        $roles = $request['roles']; //Retrieving the roles field
        //Checking if a role was selected
        if (isset($roles)) {
            foreach ($roles as $role) {
                $role_r = Role::where('id', '=', $role)->firstOrFail();
                $user->assignRole($role_r); //Assigning role to user
            }
        }

        return redirect()
            ->route('user.index')
            ->with('success', 'User has been created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('settings.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::all('id', 'name');

        //$userRoles = $user->roles->pluck('name','name')->all();
        $checkeds = $user->roles->pluck('id')->toArray();

        return view('settings.user.edit', compact('user', 'roles', 'checkeds'));
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
            'login' => 'required',
            'email' => 'required|email|unique:accounts,email,' . $id,
            'password' => 'required|min:6|confirmed'
        ]);
       
         $user = User::findOrFail($id);
         // $input = $request->except('roles');
        // $user->fill($input)->save();

        $input = $request->only(['login', 'email', 'password']); //Retreive the name, email and password fields
        //$input = $request->all();
        $user->login = $input['login'];
        $user->email = $input['email'];
        $user->password = bcrypt($input['password']);
        $user->save();

        $roles = $request['roles']; //Retreive all roles

        if (isset($roles)) {
            $user->roles()->sync($roles);//If one or more role is selected associate user to roles
        } else {
            $user->roles()->detach(); //If no role is selected remove exisiting role associated to a user
        }
             
        //return back()->with('flash_message', 'user successfully updated.');
        return redirect()
            ->route('user.index')
            ->with('flash_message', 'User successfully edited.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Find a user with a given id and delete
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()
            ->route('user.index')
            ->with('flash_message', 'User successfully deleted.');
    }
}
