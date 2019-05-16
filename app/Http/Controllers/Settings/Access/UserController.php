<?php
namespace App\Http\Controllers\Settings\Access;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('permission:settings');
    // }

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

        $users = User::with('roles')
            ->orderBy('id', 'DESC')
            ->paginate(5);
        return view('settings.access.user.user', compact('users'))
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
        return view('settings.access.user.create', compact('roles'));
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
            'password' => 'required|min:6|confirmed',
            //'roles' => 'required'
        ]);

        //$input = $request->all();
        $input = $request->only(['login', 'email', 'password']); //Retreive the name, email and password fields
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $roles = $request['roles']; //Retrieving roles
        //Checking if a role was selected
        if (isset($roles)) {
            foreach ($roles as $role) {
                $role_r = Role::where('id', '=', $role)->firstOrFail();
                $user->attachRole($role_r); //Assigning role to user
            }
        }

        return redirect()
            ->route('access.user.index')
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
        return view('settings.access.user.show', compact('user'));
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
        if ($user == null) {
            return abort(404, 'User not found.');
        }

        $roles = Role::all('id', 'name');
        //$userRoles = $user->roles->pluck('name','name')->all();
        $checkeds = $user->roles->pluck('id')->toArray();

        return view('settings.access.user.edit', compact('user', 'roles', 'checkeds'));
    }

    private function validateUser($id, $current_password)
    {
        $user = User::findOrFail($id);
        return Hash::check($current_password, $user->password);
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
        // if model state is valid
        $this->validate(request(), [
            'login' => 'required',
            'email' => 'required|email|unique:accounts,email,' . $id,
            'password' => 'nullable|min:6|confirmed',
            //'current_password' => 'required_with:password'
        ]);

        $valid = true;
        $user = User::findOrFail($id);
        $roles = Role::all('id', 'name');
        $input = $request->all();
        $flash_message = '';
        $errors = new \Illuminate\Support\MessageBag();

        if (array_key_exists('current_password', $input)) {
        // if user is not admin he must enter old_password if a new password is defined
            if (!Auth::user()->hasRole('Administrator') && $input['current_password'] == null && $input['password'] != null) {
                //ModelState.AddModelError("OldPassword", Resources.User_Edit_OldPasswordEmpty);
                //$flash_message = 'Current password should not be empty.';
                // add your error messages:
                $errors->add('your_custom_error', 'Current password cannot not be empty, if you define a new password');
                $valid = false;
            }

            
            if ($input['current_password'] != null && $this->validateUser($user->id, $input['current_password']) == false) {
                //$flash_message = 'Password does not match the current password.';
                $errors->add('your_custom_error', 'Password does not match the current password.');
                $valid = false;
            }
        }

        
      
        //$input = $request->only(['login', 'email', 'password']); //Retreive the name, email and password fields
        if ($valid == true) {
            $user->login = $input['login'];
            $user->email = $input['email'];
            if ($input['password']) {
                $user->password = Hash::make($input['password']);
            }
           
            $user->save();

            $roles = $request['roles']; //Retreive all roles

            if (array_key_exists('roles', $input)) {
                if (isset($roles)) {
                    $user->roles()->sync($roles); //If one or more role is selected associate user to roles
                } else {
                    $user->roles()->detach(); //If no role is selected remove exisiting role associated to a user
                }
            }

            return back()->with('flash_message', 'user successfully updated.');
            // return redirect()
            //     ->route('access.user.index')
            //     ->with('flash_message', 'User successfully edited.');
        }
        return back()
        ->withInput($input)
        ->withErrors($errors);
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
            ->route('access.user.index')
            ->with('flash_message', 'User successfully deleted.');
    }
}
