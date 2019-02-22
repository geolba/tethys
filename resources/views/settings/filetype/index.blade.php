@extends('settings.layouts.app')

@section('content')
<div class="header">
    <h3 class="header-title">
        <i class="fa fa-file"></i>
        <span> Mime-Types</span>
    </h3>
</div>

<div class="pure-g box-content">

    <div class="pure-u-1 pure-u-md-2-3">  
            {{-- <a class="pure-button button-small is-primary" href="{{ route('access.user.create') }}">
            <i class="fa fa-plus-circle"></i>
            <span>Create New File Extension</span>
        </a> --}}       

        @if ($message = Session::get('success'))
        <div class="alert  summary-success">
            <p>{{ $message }}</p>
        </div>
        @endif

        {!! Form::open(array('route' => 'settings.mimetype.update','method' => 'PATCH', 'class'=>'pure-form')) !!}
        
        <div class="pure-control-group checkboxlist">
            @foreach ($options as $key => $value)    
            <label for={{"mimetype". $key }} class="pure-checkbox">
                {{-- <input name="mimetypes[]" value={{ $value }} {{ (in_array($value, $checkeds)) ? 'checked=checked' : '' }} type="checkbox" class="form-check-input"> --}}
               
                {!! Form::checkbox( 'mimetypes['. $key .']', 
                  $value,
                  in_array($value, $checkeds),
                  ['class' => 'md-check', 'id' => $key] 
                  ) !!}
                   {{ $value }}
            </label>         
            @endforeach
        </div>

        {{-- <div class="pure-controls">
            <button type="submit" class="pure-button button-small">
                <i class="fa fa-save"></i>
                <span>Update allowed mimetypes</span>
            </button>
        </div> --}}

        {!! Form::close() !!}  
       
    </div>

</div>

@stop