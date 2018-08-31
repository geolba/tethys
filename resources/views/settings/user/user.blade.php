@extends('settings.layouts.app')

@section('content')
<div class="header">
    <h3 class="header-title">
        <i class="fa fa-users"></i>
        <span> Users Management</span>
    </h3>
</div>

<div class="pure-g box-content">

    <div class="pure-u-1 pure-u-md-2-3">  
            <a class="pure-button button-small is-primary" href="{{ route('settings.user.create') }}">
            <i class="fa fa-plus-circle"></i>
            <span>Create New User</span>
        </a>
        <br><br>

        @if ($message = Session::get('success'))
        <div class="alert  summary-success">
            <p>{{ $message }}</p>
        </div>
        @endif

        <table class="pure-table users">
            <thead>
                <tr>
                    <th>No</th>
                        <th>Name</th>
                        <th>Email</th>
                    <th>Roles</th>
                        <th width="280px">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $key => $user)
                <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $user->login }}</td>
                <td>{{ $user->email }}</td>
                <td>                    
                    @if(!empty($user->roles))
                        @foreach($user->roles as $role)
                        <label class="badge badge-success">{{ $role->name }}</label>
                        @endforeach
                    @endif
                </td>
                <td>                      
                        <a class="edit" href="{{ route('settings.user.edit', $user->id) }}">&nbsp;Edit</a>
                        <span>&nbsp;</span>
                        <a class="delete" href="{{ route('settings.user.destroy', $user->id) }}"><span>&nbsp;Delete</span></a> 
                </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {!! $users->render() !!}       
    </div>

</div>

@stop