@extends('settings.layouts.app')
@section('content')
<div class="header">
    <h3 class="header-title">
        <i class="fas fa-list"></i> DOI LIST:update registerede DOI datasets
    </h3>
</div>

<div class="pure-g box-content">
    <div class="pure-u-1">
        <table class="pure-table pure-table-horizontal">

            <thead>
                <th>Dataset Title</th>
                <th>Tethys ID</th>
                <th>Server State</th>
                <th>Date of last modification</th>
                <th></th>
            </thead>

            <tbody>
                @foreach($datasets as $dataset)
                <tr class="released">
                    <td>
                        @if ($dataset->titles()->first())
                        {{ $dataset->titles()->first()->value }}
                        @else
                        no title
                        @endif
                    </td>
                    <td>
                        {{ $dataset->publish_id }}
                    </td>
                    <td>
                        {{ $dataset->server_state }}
                    </td>
                    <td>
                        {{ $dataset->server_date_modified }}
                    </td>
                    <td>
                        @if ($dataset->server_state == "published")
                        <a href="{{ URL::route('publish.workflow.doi.edit', $dataset->id) }}" class="pure-button">
                            <i class="fa fa-edit"></i>
                            <span>Update DOI</span>
                        </a>
                        @endif
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>

@stop