@extends('settings.layouts.app') 
@section('content')
<div class="header">
    <h3 class="header-title">
        <i class="fa fa-file"></i>EDITOR PAGE: Approve released datasets
    </h3>
</div>

<div class="pure-g box-content">
    <div class="pure-u-1">
        <table class="pure-table pure-table-horizontal">

            <thead>
                <th>Dataset Title</th>
                <th>ID</th>
                <th>Server State</th>
                <th>Preferred Editor</th>
                <th></th>
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
                    <td> {{ optional($dataset->editor)->login }} </td>
                    <td>
                        @if ($dataset->server_state == "released")
                        <a href="{{ URL::route('publish.workflow.accept', $dataset->id) }}" class="pure-button">
                            <i class="fa fa-share"></i>
                            <span>Accept editor task</span>
                        </a>  
                        {{-- <a href="{{ URL::route('publish.workflow.delete', $dataset->id) }}" class="pure-button">
                            <i class="fa fa-trash"></i>
                            <span>Reject</span>
                        </a>   --}}
                                                           
                        @endif
                    </td>
                    {{-- <td>
                        @if ($dataset->server_state == "unpublished")
                        <a href="{{ URL::route('publish.review.changestate',['id' => $dataset->id, 'targetState' => 'published']) }}" class="pure-button button-small is-success">Publish</a>                  
                        @endif
                    </td> --}}
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>

@stop