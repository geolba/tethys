@extends('settings.layouts.app')

@section('content')
<div class="header">
    <h3 class="header-title">
        <i class="fa fa-file"></i>
        <span> File Extensions</span>
    </h3>
</div>

<div class="pure-g box-content">

    <div class="pure-u-1 pure-u-md-2-3">  
            <a class="pure-button button-small is-primary" href="{{ route('access.user.create') }}">
            <i class="fa fa-plus-circle"></i>
            <span>Create New File Extension</span>
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
                    <th>File Extensions</th>                   
                    <th width="280px">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($fileextensions as $key => $fileextension)
                <tr>               
                <td>{{ $fileextension }}</td>                
                <td>                      
                        {{-- <a class="edit" href="{{ route('access.user.edit', $user->id) }}">&nbsp;Edit</a>
                        <span>&nbsp;</span>
                        <a class="delete" href="{{ route('access.user.destroy', $user->id) }}"><span>&nbsp;Delete</span></a>  --}}
                </td>
                </tr>
                @endforeach
            </tbody>
        </table>
            
    </div>

</div>

@stop