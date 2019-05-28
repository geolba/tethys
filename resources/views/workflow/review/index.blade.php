@extends('settings.layouts.app') 
@section('content')
<div class="header">
    <h3 class="header-title">
        <i class="fas fa-list"></i> REVIEW PAGE: Review approved datasets assigned to you
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
                <th>remaining time</th>        
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
                    <td>editor: {{ optional($dataset->editor)->login }}</td>
                   <td>
                        {{-- @php                       
                        $dateDiff = $dataset['server_date_modified']->addDays(14);
                        $remainingDays = Carbon\Carbon::now()->diffInDays($dateDiff, false);
                        @endphp --}}

                        {{  $dataset->remaining_time . ' days' }}
                   </td>
                   
                    <td>
                        @if ($dataset->server_state == "approved")
                        <a href="{{ URL::route('publish.workflow.review.review', $dataset->id) }}" class="pure-button">
                            <i class="fa fa-check"></i>
                            <span>Review</span>
                        </a> 
                        <a href="{{ URL::route('publish.workflow.review.reject', $dataset->id) }}" class="pure-button">
                            <i class="fas fa-undo"></i>
                            <span>Reject</span>
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