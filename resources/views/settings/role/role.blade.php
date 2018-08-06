@extends('layouts.app')

@section('content')
<div class="pure-g">
<div class="pure-u-1 pure-u-md-2-3">

<div class="content">
    <div class="title">
        <h2><i class="fa fa-key"></i> Roles Management   
        </h2>
    </div>

    <a class="pure-button button-small is-primary" href="{{ route('role.create') }}">
        <i class="fa fa-plus-circle"></i>
        <span>Create New Role</span>
    </a>
    

    <div class="table-responsive">
        <table class="pure-table roles">
            <thead>
                <tr>
                    <th>Role</th>       
                    <th>Permissions</th>
                    <th width="280px">Action</th>           
                  
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                <tr>
                    <td>{{ $role->name }}</td>  
                    <td>
                        @foreach ($role->permissions()->pluck('name') as $permission)                           
                            <label class="badge badge-success">{{ $permission }}</label>
                        @endforeach
                    </td>  
                    <td>                      
                         <a class="edit" href="{{ route('role.edit', $role->id) }}">&nbsp;Edit Role</a>
                    </td>                
                   
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

</div>
</div>
@stop