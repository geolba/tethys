@extends('settings.layouts.app')

@section('content')
<div class="header">
    <h3 class="header-title">
            Edit Your License
    </h3>
</div>

<div class="pure-g  box-content">

    <div class="pure-u-1 pure-u-md-1">
        <div>
            <a href="{{ route('settings.license') }}" class="pure-button button-small">
                <i class="fa fa-chevron-left"></i>
                <span>BACK</span>
            </a>
        </div>   
        <div>
            {!! Form::model($license, ['method' => 'PATCH', 'route' => ['settings.license.update', $license->id], 'class' => 'pure-form', 'enctype' => 'multipart/form-data']) !!}
                @include('settings/license/_form', ['submitButtonText' => 'Edit License', 'daysLabel' => 'Days..', 'finesLabel' => 'Licenses..'])              
            {!! Form::close() !!}
        </div>      
    </div>

</div>
@stop