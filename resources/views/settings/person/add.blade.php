@extends('layouts.settings.layout')


@section('content')
<div class="header">
    <h3 class="header-title">
            Add Your Person
    </h3>
</div>

<div class="box-content">
    <div>		
        <a href="{{ route('settings.person') }}" class="pure-button button-small">           
            <i class="fa fa-chevron-left"></i>
            <span>BACK</span>
        </a>
    </div>

    <div>
        {!! Form::open(['route' => 'settings.person.post', 'class' => 'pure-form pure-form-aligned']) !!}
        @include('settings/person/_form', ['submitButtonText' => 'Add Person', 'projectLabel' => 'Save Person.'])
        {!! Form::close() !!}
    </div>

</div>
@stop