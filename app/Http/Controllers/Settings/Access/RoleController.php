<?php
namespace App\Http\Controllers\Settings\Access;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        return view('settings.access.role.role', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all('id', 'name');
        return view('settings.access.role.create', compact('permissions'));
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
            'name' => 'required',
            'display_name' => 'max:255',
            'description' => 'max:255'
        ]);
        //$input = $request->all();
        $input = $request->except('permissions');
        //$input = $request->only(['name']); //Retreive the name field
        $role = Role::create($input);

        $permissions = $request['permissions']; //Retrieving permissions
        //Checking if a role was selected
        if (isset($permissions)) {
            foreach ($permissions as $permission) {
                $permission_r = Permission::where('id', '=', $permission)->firstOrFail();
                $role->attachPermission($permission_r); //Assigning permission to role
            }
        }

        return redirect()
            ->route('access.role.index')
            ->with('success', 'Role has been created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        $permissions = Permission::all('id', 'name');

        //$userRoles = $user->roles->pluck('name','name')->all();
        $checkeds = $role->perms->pluck('id')->toArray();
        return view('settings.access.role.edit', compact('role', 'permissions', 'checkeds'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(request(), [
            'name' => 'required',
            'display_name' => 'max:255',
            'description' => 'max:255'
        ]);
        $role = Role::findOrFail($id);
        $role->update($request->except('permissions'));

        $permissions = $request->input('permissions') ? $request->input('permissions') : [];
        //$role->syncPermissions($permissions);
        if (isset($permissions)) {
            $role->perms()->sync($permissions);//If one or more role is selected associate user to roles
        } else {
            $role->perms()->detach(); //If no role is selected remove exisiting role associated to a user
        }

        return redirect()->route('access.role.index')
            ->with('flash_message', 'Role successfully edited.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        //
    }
}
