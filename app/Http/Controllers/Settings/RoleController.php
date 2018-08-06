<?php
namespace App\Http\Controllers\Settings;

use App\Role;
use App\Permission;
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
        return view('settings.role.role', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all('id', 'name');
        return view('settings.role.create', compact('permissions'));
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
        $checkeds = $role->permissions->pluck('id')->toArray();
        return view('settings.role.edit', compact('role', 'permissions', 'checkeds'));
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
            'name' => 'required'
        ]);
        $role = Role::findOrFail($id);
        $role->update($request->except('permissions'));

        $permissions = $request->input('permissions') ? $request->input('permissions') : [];
        //$role->syncPermissions($permissions);
        if (isset($permissions)) {
            $role->permissions()->sync($permissions);//If one or more role is selected associate user to roles
        } else {
            $role->permissions()->detach(); //If no role is selected remove exisiting role associated to a user
        }

        return redirect()->route('role.index')
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
