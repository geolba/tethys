@extends('layouts.app')


@section('content')
<div class="pure-g">

    <div class="pure-u-1 pure-u-md-3-3">
        <div class="content">
            <h1 class="title">Edit Your Dataset</h1>
            <div>
                <a href="{{ route('settings.document') }}" class="pure-button button-small">
                    <i class="fa fa-chevron-left"></i>
                    <span>BACK</span>
                </a>              
            </div>


            <div>

                {!! Form::model($document, ['method' => 'PATCH', 'route' => ['settings.document.update', $document->id], 'class' => 'pure-form', 'enctype' => 'multipart/form-data' ]) !!}

                @include('settings/document/_form', ['submitButtonText' => 'Edit Dataset', 'bookLabel' => 'Edit Dataset.'])

                {!! Form::close() !!}

            </div>
        </div>
    </div>

</div>
@stop