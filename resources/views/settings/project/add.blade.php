@extends('layouts.app')

@section('content')

	
	<h1 class="title">Add Your Project</h1>
	<div>		
        <a href="{{ route('settings.project') }}" class="pure-button button-small">
            <i class="fa fa-chevron-left"></i>
            <span>BACK</span>
        </a>		
	</div>

    <div class="col-md-4">

        {!! Form::open(['route' => 'settings.project.post', 'class' => 'pure-form pure-form-stacked']) !!}

        @include('settings/project/_form', ['submitButtonText' => 'Save Project', 'projectLabel' => 'Save Project.'])

        {!! Form::close() !!}

    </div>
		
		
		

@stop