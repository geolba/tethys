@extends('settings.layouts.app') 
@section('content')
<div class="header">
    <h3 class="header-title">
        <i class="fas fa-list"></i> Release saved datasets
    </h3>
</div>

<div class="pure-g box-content">
    <div class="pure-u-1">
        <table class="pure-table pure-table-horizontal">

            <thead>
                <th>Dataset Title</th>
                <th>Owner</th>    
                <th>Server State</th>
                <th>Date of last modification</th>  
                <th></th>
            </thead>

            <tbody>
                @foreach($datasets as $dataset)
                @php 
                //if userid changed from last iteration, store new userid and change color                
                // $lastid = $detail->payment->userid;
                if ($dataset->server_state == 'inprogress') {
                    $rowclass = 'inprogress';
                } elseif ($dataset->server_state == 'released') {
                    $rowclass = 'released';   
                }  
                elseif ($dataset->server_state == 'editor_accepted' || $dataset->server_state == 'rejected_reviewer') {
                    $rowclass = 'editor_accepted';   
                } elseif ($dataset->server_state == 'approved') {
                    $rowclass = 'approved';   
                } elseif ($dataset->server_state == 'reviewed') {
                    $rowclass = 'reviewed';   
                } elseif ($dataset->server_state == 'rejected_editor') {
                    $rowclass = 'rejected_editor';  
                } else {
                    $rowclass = "";
                }                                      
                @endphp
                <tr class="{{ $rowclass }}">
                    <td>
                        @if ($dataset->titles()->first()) 
                        {{ $dataset->titles()->first()->value }} 
                        @else 
                        no title 
                        @endif
                        {{ $dataset->files->count() }}
                    </td>
                    <td>
                        {{ $dataset->user->login }}
                    </td>
                    {{-- <td>
                        {{ optional($dataset->editor)->login }}
                    </td> --}}
                    <td>
                        {{ $dataset->server_state }}
                    </td>
                    <td>                        
                        {{ $dataset->server_date_modified }}                       
                    </td>  

                    <td>
                        @if ($dataset->server_state == "inprogress" || $dataset->server_state == "rejected_editor")
                        <a href="{{ URL::route('publish.workflow.submit.edit', $dataset->id) }}" class="pure-button">
                            <i class="fa fa-edit"></i>                           
                        </a> 
                        <a href="{{ URL::route('publish.workflow.submit.release', $dataset->id) }}" class="pure-button">
                            <i class="fa fa-share"></i>
                            <span>Release</span>
                        </a>  
                        <a href="{{ URL::route('publish.workflow.submit.delete', $dataset->id) }}" class="pure-button">
                            <i class="fa fa-trash"></i>
                            {{-- <span>Delete</span> --}}
                        </a>  
                                                           
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>

@stop