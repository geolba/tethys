@extends('settings.layouts.app') 
@section('content')
<div class="header">
    <h3 class="header-title">
        <i class="fas fa-file-code"></i>
        <span> Mime-Types</span>
    </h3>
</div>

<div class="pure-g box-content">

    <div class="pure-u-1 pure-u-md-2-3">
        {{-- <a class="pure-button button-small is-primary" href="{{ route('settings.person.add') }}">
            <i class="fa fa-plus-circle"></i>
            <span>ADD NEW MimeType</span>
        </a>
        <br><br> --}}

        <table class="pure-table pure-table-horizontal">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>File Extension</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @foreach($mimetypes as $mimetype)
                <tr>

                    <td>{{ $mimetype->name }}</td>
                    <td>{{ $mimetype->file_extension }}</td>
                    <td>
                        @if($mimetype->enabled == 1) Active @else Inactive @endif
                    </td>
                    <td>
                        @if($mimetype->enabled == 1)
                        <a href="{{ route('settings.mimetype.down', $mimetype->id) }}" class="pure-button button-small is-warning">Deactivate</a>                        @else
                        <a href="{{ route('settings.mimetype.up', $mimetype->id) }}" class="pure-button button-small is-success">Activate</a>                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

</div>

@stop