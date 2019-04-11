@extends('settings.layouts.app') 
@section('content')
<div class="header">
    <h3 class="header-title">
        <i class="fa fa-file"></i> EDITOR PAGE: Approve released datasets
    </h3>
</div>

<div class="pure-g box-content">
    <div class="pure-u-1">
        <table class="pure-table pure-table-horizontal">

            <thead>
                <th>Dataset Title</th>
                <th>ID</th>
                <th>Server State</th>               
                <th>Editor</th>              
                <th></th>
            </thead>

            <tbody>
                @foreach($datasets as $dataset)               
                @php 
                //if userid changed from last iteration, store new userid and change color                
                // $lastid = $detail->payment->userid;
                if ($dataset->server_state == 'editor_accepted') {
                    $rowclass = 'editor_accepted';
                } elseif ($dataset->server_state == 'released') {
                    $rowclass = 'released';   
                }         
                @endphp
                <tr class="{{ $rowclass }}">
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
                    @if ($dataset->server_state == "released") 
                    {{-- <td>Preferred editor: {{ optional($dataset->editor)->login }} </td> --}}
                    <td>Preferred editor: {{ $dataset->preferred_editor }} </td>
                    @elseif ($dataset->server_state == "editor_accepted")
                    <td>in approvement by {{ optional($dataset->editor)->login }} </td>
                    @endif
                   
                    <td>
                        @if ($dataset->server_state == "released")
                        <a href="{{ URL::route('publish.workflow.accept', $dataset->id) }}" class="pure-button">
                            <i class="fa fa-check"></i>
                            <span>Accept editor task</span>
                        </a>                        
                        @elseif ($dataset->server_state == "editor_accepted")
                        <a href="{{ URL::route('publish.workflow.editor.edit', $dataset->id) }}" class="pure-button">
                            <i class="fa fa-edit"></i>
                            <span>Improve/Edit</span>
                        </a> 
                        <a href="{{ URL::route('publish.workflow.editor.approve', $dataset->id) }}" class="pure-button">
                            <i class="fa fa-share"></i>
                            <span>Approve</span>
                        </a>  
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