@extends('layouts.app')

@section('content')
<div class="pure-g">

    <div class="pure-u-1 pure-u-md-2-3">
        <div class="content">

            <div class="title">
             <h2><i class="fa fa-users"></i> Users Management</h2>
            </div>
           
             <a class="pure-button button-small is-primary" href="{{ route('user.create') }}">
                <i class="fa fa-plus-circle"></i>
                <span>Create New User</span>
            </a>
            <br><br>

            @if ($message = Session::get('success'))
            <div class="alert alert-success">
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
                      
                        @if(!empty($user->getRoleNames()))
                            @foreach($user->getRoleNames() as $roleName)
                            <label class="badge badge-success">{{ $roleName }}</label>
                            @endforeach
                        @endif
                    </td>
                    <td>                      
                         <a class="edit" href="{{ route('user.edit',$user->id) }}">&nbsp;Edit</a>
                            <span>&nbsp;</span>
                         <a class="delete" href="{{ route('user.destroy', $user->id) }}"><span>&nbsp;Delete</span></a> 
                    </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
            {!! $users->render() !!}

        </div>
    </div>
</div>

@stop