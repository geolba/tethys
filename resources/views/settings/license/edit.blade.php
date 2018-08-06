@extends('layouts.app')

@section('content')
<div class="pure-g">

    <div class="pure-u-1 pure-u-md-1">
        <div class="content">
            <h1 class="title">Edit Your License</h1>

            <div>
                <a href="{{ route('settings.license') }}" class="pure-button button-small">
                    <i class="fa fa-chevron-left"></i>
                    <span>BACK</span>
                </a>
            </div>          

            <div>
                {!! Form::model($license, ['method' => 'PATCH', 'route' => ['settings.license.update', $license->id], 'class' => 'pure-form']) !!}

                @include('settings/license/_form', ['submitButtonText' => 'Edit License', 'daysLabel' => 'Days..', 'finesLabel' => 'Licenses..'])

                {!! Form::close() !!}
            </div>

        </div>
    </div>
</div>
@stop