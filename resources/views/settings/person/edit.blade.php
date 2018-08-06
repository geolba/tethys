@extends('layouts.app')

@section('content')
<div class="pure-g">

    <div class="pure-u-1 pure-u-md-2-3">
        <div class="content">
            <h1 class="title">Edit {{ $person->getFullName() }}</h1>

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