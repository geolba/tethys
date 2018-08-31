@extends('settings.layouts.app')

@section('content')
<div class="header">
    <h3 class="header-title">
        Add Your Project
    </h3>
</div>	

<div class="box-content">	
    <a href="{{ route('settings.project') }}" class="pure-button button-small">
        <i class="fa fa-chevron-left"></i>
        <span>BACK</span>
    </a>
    <div>
        {!! Form::open(['route' => 'settings.project.post', 'class' => 'pure-form  pure-form-aligned']) !!}
        @include('settings/project/_form', ['submitButtonText' => 'Save Project', 'projectLabel' => 'Save Project.'])
        {!! Form::close() !!}
    </div>
</div>
@stop