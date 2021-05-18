@extends('settings.layouts.app') 
@section('content')
<div class="header">
    <h3 class="header-title">
        <i class="fas fa-list"></i> EDITOR PAGE: Approve released datasets
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
                <th>Date of last modification</th>             
                <th></th>
            </thead>

            <tbody>
                @foreach($datasets as $dataset)               
                @php 
                //if userid changed from last iteration, store new userid and change color                
                // $lastid = $detail->payment->userid;
                if ($dataset->server_state == 'editor_accepted') {
                    $rowclass = 'editor_accepted';
                }  elseif ($dataset->server_state == 'rejected_reviewer') {
                    $rowclass = 'rejected_reviewer';   
                } elseif ($dataset->server_state == 'reviewed') {
                    $rowclass = 'reviewed';   
                }elseif ($dataset->server_state == 'released') {
                    $rowclass = 'released';   
                } elseif ($dataset->server_state == 'published') {
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
                    {{-- <td>Preferred reviewer: {{ optional($dataset->reviewer)->login }} </td> --}}
                    <td>Preferred reviewer: {{ $dataset->preferred_reviewer }} </td>
                    @elseif ($dataset->server_state == "editor_accepted" || $dataset->server_state == "rejected_reviewer")
                    <td>in approvement by {{ optional($dataset->editor)->login }} </td>
                    @endif
                    <td>                        
                        {{ $dataset->server_date_modified }}                       
                    </td>      
                    <td>
                        @if ($dataset->server_state == "released")
                        <a href="{{ URL::route('publish.workflow.editor.receive', $dataset->id) }}" class="pure-button">
                            <i class="fa fa-check"></i>
                            <span>Receive editor task</span>
                        </a>                        
                        @elseif ($dataset->server_state == "editor_accepted" || $dataset->server_state == "rejected_reviewer")
                        <a href="{{ URL::route('publish.workflow.editor.edit', $dataset->id) }}" class="pure-button">
                            <i class="fa fa-edit"></i>
                            {{-- <span>Edit</span> --}}
                        </a> 
                        <a href="{{ URL::route('publish.workflow.editor.approve', $dataset->id) }}" class="pure-button">
                            <i class="fa fa-share"></i>
                            <span>Approve</span>
                        </a> 
                        <a href="{{ URL::route('publish.workflow.editor.reject', $dataset->id) }}" class="pure-button">
                            <i class="fas fa-undo"></i>
                            <span>Reject</span>
                        </a> 
                        @elseif ($dataset->server_state == "reviewed")
                        <a href="{{ URL::route('publish.workflow.editor.publishUpdate', $dataset->id) }}" class="pure-button">
                            <i class="fa fa-edit"></i>
                            <span>Publish</span>
                        </a>
                        @elseif ($dataset->server_state == "published" && ! $dataset->identifier()->exists())
                        <a href="{{ URL::route('publish.workflow.editor.doi', $dataset->id) }}" class="pure-button">
                            <i class="fa fa-edit"></i>
                            <span>Make DOI</span>
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