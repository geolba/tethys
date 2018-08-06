@extends('layouts.app')

@section('content')

<div class="pure-g">

    <div class="pure-u-1 pure-u-md-2-3">
        <div class="content">

            <h1 class="title">Edit Your Project</h1>
            <div>
                <a href="{{ route('settings.project') }}" class="pure-button button-small">
                    <i class="fa fa-chevron-left"></i>
                    <span>BACK</span>
                </a>
            </div>
            <div>

                {!! Form::model($project, ['method' => 'PATCH', 'route' => ['settings.project.update', $project->id], 'class' => 'pure-form pure-form-aligned']) !!}

                @include('settings/project/_form', ['submitButtonText' => 'Save Project', 'projectLabel' => 'Edit Project.'])

                {!! Form::close() !!}

            </div>

        </div>
    </div>
</div>

@stop
