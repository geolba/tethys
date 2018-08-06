@extends('layouts.app')


@section('content')


<h2 class="title">Create New User</h2>
	<div>		
         <a href="{{ route('user.index') }}" class="pure-button button-small">           
            <i class="fa fa-chevron-left"></i>
            <span>BACK</span>
        </a>
	</div>


@if (count($errors) > 0)

  <div class="alert alert-danger">
    <strong>Whoops!</strong> There were some problems with your input.<br><br>
    <ul>
       @foreach ($errors->all() as $error)
         <li>{{ $error }}</li>
       @endforeach
    </ul>
  </div>
@endif


{!! Form::open(['route' => 'user.store', 'method'=>'POST', 'class' => 'pure-form pure-form-aligned']) !!}

<div class="row">

  

     <div class="pure-control-group @if ($errors->has('login')) field-validation-error @endif">
        <label>Login:</label>
        {!! Form::text('login', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
        <em>*</em>
    </div>  

    <div class="pure-control-group @if ($errors->has('email')) field-validation-error @endif">
    <label>Email:</label>
    {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
    <em>*</em>
</div>

     <div class="pure-control-group @if ($errors->has('password')) field-validation-error @endif">
        <label>Password:</label>
        {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}
          <em>*</em>  
    </div>

    <div class="pure-control-group @if ($errors->has('password')) field-validation-error @endif">        
        <label>Confirm Password:</label>
        {!! Form::password('password_confirmation', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}
        <em>*</em>
    </div>

    <h5><b>Assign Roles</b></h5>
    <div class="pure-control-group checkboxlist @if ($errors->has('roles')) field-validation-error @endif">
        <!--  <label for="Roles">Assign Roles</label>-->
                

        @foreach ($roles as $role)

            <label for={{"role". $role->id }} class="pure-checkbox">
                <input name="roles[]" value={{ $role->id }} type="checkbox" class="form-check-input">
                {{ $role->name }}
            </label>
                    
        @endforeach
    </div>

<!--
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Role:</strong>
            {!! Form::select('roles[]', $roles,[], array('class' => 'form-control','multiple')) !!}
        </div>
    </div>-->

    <div class="col-xs-12 col-sm-12 col-md-12 text-center">

        <button type="submit" class="btn btn-primary">Submit</button>

    </div>

</div>

{!! Form::close() !!}


@endsection