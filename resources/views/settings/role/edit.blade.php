@extends('layouts.app')

@section('content')

<div class="pure-g">
  <div class="pure-u-1 pure-u-md-2-3">
     <div class="content">
         <h1 class="title">Edit {{ $role->login }}</h1>

            <div>
                <a href="{{ route('role.index') }}" class="pure-button button-small">
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

            {!! Form::model($role, ['method' => 'PATCH','route' => ['role.update', $role->id], 'class' => 'pure-form pure-form-aligned']) !!}
            
                <fieldset>
                <div class="pure-control-group @if ($errors->has('login')) field-validation-error @endif">
                    {!! Form::label('name', 'Name:', ['class' => 'control-label']) !!}
                   {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                    <em>*</em>
                </div>                  
                

                <h5><b>Assign Permissions</b></h5>
                <div class="pure-control-group checkboxlist @if ($errors->has('roles')) field-validation-error @endif">
                 <!--  <label for="Roles">Assign Permissions</label>-->
                

                    @foreach ($permissions as $permission)                        

                        <label for={{"permission". $permission->id }} class="pure-checkbox">
                            <input name="permissions[]" value={{ $permission->id }} {{ (in_array($permission->id, $checkeds)) ? 'checked=checked' : '' }} type="checkbox" class="form-check-input">
                            {{ $permission->name }}
                        </label>                 
                    @endforeach

                </div>
             
                <button type="submit" class="pure-button pure-button-primary">Save</button>
             
                </fieldset>
    

            {!! Form::close() !!}

        </div>
        </div>
    </div>

    <div class="pure-u-1 pure-u-md-1-3">
        <div class="sidebar">
            @foreach ($role->permissions as $permission)
            <p>permission: {{ $permission->name }}</p>
            @endforeach
        </div>
    </div>

</div>

@endsection