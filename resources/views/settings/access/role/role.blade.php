@extends('settings.layouts.app')

@section('content')
<div class="header">
    <h3 class="header-title">
            <i class="fa fa-key"></i>
            <span>Roles Management <span>
    </h3>
</div>	

<div class="pure-g box-content">
    <div class="pure-u-1 pure-u-md-2-3">  
        <a class="pure-button button-small is-primary" href="{{ route('access.role.create') }}">
            <i class="fa fa-plus-circle"></i>
            <span>Create New Role</span>
        </a> 
        <br>
        <table class="pure-table pure-table-horizontal roles">
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
                        @foreach ($role->perms()->pluck('name') as $permission)                           
                            <label class="badge badge-success">{{ $permission }}</label>
                        @endforeach
                    </td>  
                    <td>                      
                        <a class="edit" href="{{ route('access.role.edit', $role->id) }}">&nbsp;Edit Role</a>
                    </td>                
                
                </tr>
                @endforeach
            </tbody>
        </table>
    
    </div>

</div>
@stop