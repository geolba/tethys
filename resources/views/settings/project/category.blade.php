@extends('layouts.app')

@section('content')
<div class="pure-g">

    <div class="pure-u-1 pure-u-md-2-3">
        <div class="content">
            <div class="title">
                <h1>Project</h1>
            </div>
            <a href="{{ route('settings.project.add') }}" class="pure-button button-small is-primary">
                <i class="fa fa-plus-circle"></i>
                <span>ADD NEW Project</span>
            </a>

            <br><br>

            <table class="pure-table pure-table-horizontal">

                <thead>
                    <tr>
                        <th>Project</th>
                        <th>Options</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($projects as $project)
                    <tr>
                        <td>{{ $project->name }}</td>
                        <td>
                            <a class="edit" href="{{ route('settings.project.edit', $project->id) }}"><span></span></a> &nbsp;
                            <a class="delete" href="{{ route('settings.project.delete', $project->id) }}"><span></span></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
</div>
            
@stop