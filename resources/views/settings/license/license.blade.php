@extends('layouts.settings.layout')

@section('content')
<div class="header">
    <h3 class="header-title">
        <i class="fa fa-file"></i> Licenses
    </h3>
</div>	

<div class="pure-g box-content">
    
    <div class="pure-u-1">
        <table class="pure-table pure-table-horizontal">

            <thead>				
                <th>Licence</th>
                <th></th>
            </thead>

            <tbody>
                
                @foreach($licenses as $license)				
                    <tr>											
                        <td>{{ $license->name_long }}</td>						
                                
                        <td>
                            <a class="edit" href="{{ route('settings.license.edit', $license->id) }}">
                                <span>edit</span>
                            </a>
                        </td>
                    </tr>
                @endforeach	

            </tbody>
            
        </table>
    </div>	

</div>
@stop