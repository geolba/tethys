@extends('settings.layouts.app')

@section('content')
<div class="header">
        <h3 class="header-title">
                Edit {{ $person->full_name }}
        </h3>
    </div>

<div class="pure-g box-content">

    <div class="pure-u-1 pure-u-md-2-3">  
        <div>
            <a href="{{ route('settings.person') }}" class="pure-button button-small">
                <i class="fa fa-chevron-left"></i>
                <span>BACK</span>
            </a>
        </div>
        <div>
            {!! Form::model($person, ['method' => 'PATCH', 'route' => ['settings.person.update', $person->id],'class' => 'pure-form pure-form-aligned']) !!}

            @include('settings/person/_form', ['submitButtonText' => 'Save Person'])

            {!! Form::close() !!}
        </div>      
    </div>

    <div class="pure-u-1 pure-u-md-1-3">
        <div class="sidebar">
            @foreach ($person->documents as $document)
            <p>document role: {{ $document->pivot->role }}</p>
            @endforeach
        </div>
    </div>

</div>

@stop