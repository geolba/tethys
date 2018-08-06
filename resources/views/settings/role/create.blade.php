@extends('layouts.app')


@section('content')


<h2 class="title">Create New Role</h2>
	<div>		
         <a href="{{ route('role.index') }}" class="pure-button button-small">           
            <i class="fa fa-chevron-left"></i>
            <span>BACK</span>
        </a>
	</div>

{!! Form::open(['route' => 'user.store', 'method'=>'POST', 'class' => 'pure-form pure-form-aligned']) !!}

<div class="row">
  

     <div class="pure-control-group">
        {!! Form::label('name', 'Name:', ['class' => 'control-label']) !!}
        {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
        <em>*</em>       
        @if($errors->has('name'))
            <p class="field-validation-error">
                {{ $errors->first('name') }}
            </p>
        @endif
    </div>  
    

    <h5><b>Assign Permissions</b></h5>
    <div class="pure-control-group checkboxlist @if ($errors->has('roles')) field-validation-error @endif">
        <!--  <label for="Roles">Assign Roles</label>-->
                

        @foreach ($permissions as $permission)

            <label for={{"permission". $permission->id }} class="pure-checkbox">
                <input name="permissions[]" value={{ $permission->id }} type="checkbox" class="form-check-input">
                {{ $permission->name }}
            </label>
                    
        @endforeach
    </div>


    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>

</div>

{!! Form::close() !!}


@endsection