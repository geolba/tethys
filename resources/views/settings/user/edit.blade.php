@extends('settings.layouts.app')

@section('content')
<div class="header">
    <h3 class="header-title">
        <span>Edit {{ $user->login }}</span>
    </h3>
</div>
    

<div class="pure-g box-content">
  <div class="pure-u-1 pure-u-md-2-3">  
            <div>
                <a href="{{ route('settings.user.index') }}" class="pure-button button-small">
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

            <div>
                {!! Form::model($user, ['method' => 'PATCH','route' => ['settings.user.update', $user->id], 'class' => 'pure-form pure-form-aligned']) !!}
                
                    <fieldset>
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
                                <input name="roles[]" value={{ $role->id }} {{ (in_array($role->id, $checkeds)) ? 'checked=checked' : '' }} type="checkbox" class="form-check-input">
                                {{ $role->name }}
                            </label>
                        
                        @endforeach
                    </div>
                
                    <button type="submit" class="pure-button button-small">Save</button>
                
                    </fieldset>
        

                {!! Form::close() !!}
            </div>       
    </div>

    <div class="pure-u-1 pure-u-md-1-3">
        <div class="sidebar">
            @foreach ($user->roles as $role)
            <p>role: {{ $role->name }}</p>
            @endforeach
        </div>
    </div>

</div>

@endsection