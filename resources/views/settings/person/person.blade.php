@extends('settings.layouts.app')

@section('content')
<div class="header">
    <h3 class="header-title">
        <i class="fas fa-users"></i> Persons Management
    </h3>
</div>	

<div class="pure-g box-content">

    <div class="pure-u-1 pure-u-md-2-3">
        <a class="pure-button button-small is-primary" href="{{ route('settings.person.add') }}">
            <i class="fa fa-plus-circle"></i>
            <span>ADD NEW Person</span>
        </a>
        <br><br>

        <table class="pure-table pure-table-horizontal">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>ORCID</th>                   
                    <th>Status</th>
                    <th></th>
                    <th>Document Count</th>
                    <th colspan="2"><center>Options</center></th>
                </tr>
            </thead>

            <tbody>
                @foreach($persons as $person)
                <tr>

                    <td>{{ $person->last_name }}</td>
                    <td>{{ $person->email }}</td>                  
                    <td>{{ $person->identifier_orcid }}</td>                  
                    <td>
                        @if($person->status == 1)
                        Active
                        @else
                        Inactive
                        @endif
                    </td>
                    <td>
                        @if($person->status == 1)
                        <a href="{{ route('settings.person.down', $person->id) }}" class="pure-button button-small is-warning">Deactivate</a>
                        @else
                        <a href="{{ route('settings.person.up', $person->id) }}" class="pure-button button-small is-success">Activate</a>
                        @endif
                    </td>
                    <td>{{ $person->documents->count() }}</td>
                    <td>                            
                        <a class="edit" href="{{ route('settings.person.edit', $person->id) }}"><span>&nbsp;</span></a>
                         <a class="delete" href="{{ route('settings.person.delete', $person->id) }}"><span>&nbsp;</span></a> 
                        @if($person->documents->count() == 0)
                       
                        @endif                          
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>       
    </div>

</div>

@stop