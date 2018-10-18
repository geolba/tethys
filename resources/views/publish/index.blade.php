@extends('settings.layouts.app')

@section('content')
<div class="header">
    <h3 class="header-title">
        <i class="fa fa-file"></i> Datasets to be processed
    </h3>
</div>	

<div class="pure-g box-content">
    
    <div class="pure-u-1">
        <table class="pure-table pure-table-horizontal">

            <thead>				
                <th>Dataset Title</th>
                <th>ID</th>
                <th>Server State</th>
            </thead>

            <tbody>
                
                @foreach($datasets as $dataset)				
                    <tr>											
                        <td>                           
                            @if ($dataset->titles()->first())
                            {{ $dataset->titles()->first()->value }}                           
                            @else
                               no title
                            @endif
                        </td>
                        <td>  
                            {{ $dataset->id }}
                        </td>
                        <td>  
                                {{ $dataset->server_state }}
                            </td>	                     			
                                
                        <td>
                            {{-- <a class="edit" href="{{ route('settings.document.edit', $dataset->id) }}">
                                <span>publish</span>
                            </a> --}}
                            <a href="" class="pure-button button-small">Publish</a>
                            <a href="" class="pure-button button-small">Restrict</a>
                        </td>
                    </tr>
                @endforeach	

            </tbody>
            
        </table>
    </div>	

</div>
@stop