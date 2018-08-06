@extends('layouts.app')


@section('content')
	
	<h1 class="title">Add Your Person</h1>
	<div>		
         <a href="{{ route('settings.person') }}" class="pure-button button-small">           
            <i class="fa fa-chevron-left"></i>
            <span>BACK</span>
        </a>
	</div>

    <div class="col-md-4">

        {!! Form::open(['route' => 'settings.person.post', 'class' => 'pure-form pure-form-stacked']) !!}

        @include('settings/person/_form', ['submitButtonText' => 'Add Person', 'projectLabel' => 'Save Person.'])

        {!! Form::close() !!}

    </div>
		
		
		

@stop