@extends('settings.layouts.app')

@section('content')
<div class="header">
    <h3 class="header-title">
        Create New Role
    </h3>
</div>
<div class="box-content">	
    <div>		
            <a href="{{ route('role.index') }}" class="pure-button button-small">           
            <i class="fa fa-chevron-left"></i>
            <span>BACK</span>
        </a>
    </div>

    {!! Form::open(['route' => 'role.store', 'method'=>'POST', 'class' => 'pure-form pure-form-aligned']) !!}
        
        <div class="pure-control-group @if ($errors->has('name')) field-validation-error @endif">
            {!! Form::label('name', 'Name:', ['class' => 'control-label']) !!}
            {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
            <em>*</em>
            {{-- @if($errors->has('name'))
            <p class="field-validation-error">
                {{ $errors->first('name') }}
            </p>
            @endif --}}
        </div>
        <div class="pure-control-group @if ($errors->has('display_name')) field-validation-error @endif">
            {!! Form::label('display_name', 'Display Label:', ['class' => 'control-label']) !!}
            {!! Form::text('display_name', null, array('placeholder' => 'Display Label','class' => 'form-control')) !!}             
        </div>  
        <div class="pure-control-group @if ($errors->has('description')) field-validation-error @endif">
            {!! Form::label('description', 'Description:', ['class' => 'control-label']) !!}
            {!! Form::text('description', null, array('placeholder' => 'Description for the role','class' => 'form-control')) !!}             
        </div>    
        

        <h5><b>Assign Permissions</b></h5>
        <div class="pure-control-group checkboxlist @if ($errors->has('roles')) field-validation-error @endif">
            @foreach ($permissions as $permission)
                <label for={{"permission". $permission->id }} class="pure-checkbox">
                    <input name="permissions[]" value={{ $permission->id }} type="checkbox" class="form-check-input">
                    {{ $permission->name }}
                </label>                    
            @endforeach
        </div>

        <button type="submit" class="pure-button button-small">Submit</button>        
    {!! Form::close() !!}

</div>

@endsection