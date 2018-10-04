@extends('settings.layouts.app')

@section('content')
<div class="header">
    <h3 class="header-title">
        Edit Collection Role
    </h3>
</div>	

<div class="box-content">	
    <a href="{{ route('settings.collectionrole.index') }}" class="pure-button button-small">
        <i class="fa fa-chevron-left"></i>
        <span>BACK</span>
    </a>
    <div>
        {!! Form::model($collectionrole, ['method' => 'PATCH', 'route' => ['settings.collectionrole.update', $collectionrole->id], 'class' => 'pure-form  pure-form-aligned']) !!}
        <fieldset>
            
            <div class="pure-control-group">
                {{ Form::label('name', 'collectionrole name') }}
                {{ Form::text('name', null, ['class' => 'form-control']) }}
                <em>*</em>
            </div>  
            <div class="pure-control-group">
                {{ Form::label('oai_name', 'name of oai set') }}
                {{ Form::text('oai_name', null, ['class' => 'form-control']) }}
                <em>*</em>
            </div>     
            <!-- checkboxes -->
            <label for="active" class="pure-checkbox">
                <input type="hidden" name="visible" value="0">
                <input name="visible" value="1" {{ ($collectionrole->visible == 1) ? 'checked="checked" ' : '' }} type="checkbox" class="form-check-input"> 
                Visible?
            </label>   
            <label for="active" class="pure-checkbox">
                <input type="hidden" name="visible_oai" value="0">
                <input name="visible_oai" value="1" {{ ($collectionrole->visible_oai == 1) ? 'checked="checked" ' : '' }} type="checkbox" class="form-check-input"> 
                Use collection as OAI set
            </label>   

            {{ Form::submit("Submit", ['class' => 'pure-button button-small']) }}
        </fieldset>
        {!! Form::close() !!}
    </div>
</div>
@endsection