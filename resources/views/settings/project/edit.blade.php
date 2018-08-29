@extends('layouts.settings.layout')

@section('content')
<div class="header">
    <h3 class="header-title">
        <span>Edit Your Project</span>
    </h3>
</div>

<div class="pure-g box-content">
    <div class="pure-u-1 pure-u-md-2-3">    
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

@stop
