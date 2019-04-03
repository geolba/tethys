@extends('settings.layouts.app') 
@section('content')
<div class="header">
    <h3 class="header-title">
        <i class="fa fa-file"></i> Release saved datasets
    </h3>
</div>

<div class="pure-g box-content">
    <div class="pure-u-1">
        <table class="pure-table pure-table-horizontal">

            <thead>
                <th>Dataset Title</th>
                <th>ID</th>
                <th>Server State</th>
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

                    <td>
                        @if ($dataset->server_state == "inprogress")
                        <a href="{{ URL::route('publish.review.changestate',['id' => $dataset->id, 'targetState' => 'unpublished']) }}" class="pure-button button-small is-success">Release</a>
                        {{-- <a href="" class="pure-button button-small is-success">Restrict</a>  --}}                      
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>

@stop