<fieldset>
    <div class="pure-control-group">
        {!! Form::label('academic_title', 'Title..') !!}
        {!! Form::text('academic_title', null, ['class' => 'form-control']) !!}
        <aside id="titleHelp" class="pure-form-message-inline">title is optional.</aside>
    </div>

    <div class="pure-control-group">
        {!! Form::label('last_name', 'Name..') !!}
        {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
         <em>*</em>
    </div>

    <div class="pure-control-group" ">
        {!! Form::label('first_name', 'First Name..') !!}
        {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
        <em>*</em>
        {{-- <aside id="firstNameHelp" class="pure-form-message-inline">first name is optional.</aside> --}}
    </div>


    <div class="pure-control-group">
        {!! Form::label('email', 'Email..') !!}
        {!! Form::text('email', null, ['class' => 'form-control']) !!}        
        <em>*</em>
    </div>

    <div class="pure-control-group">
        {!! Form::label('date_of_birth', 'Date Of Birth..') !!}
        {!! Form::date('date_of_birth', null, ['placeholder' => date('y-m-d'), 'class' => 'form-control']) !!}        
        <em>*</em>
    </div>
    
     <div class="pure-control-group">
        {!! Form::label('identifier_orcid', 'ORCID..') !!}
        {!! Form::text('identifier_orcid', null, ['class' => 'form-control']) !!}
        <small id="orcidHelp" class="pure-form-message-inline">orcid is optional.</small>
    </div>

    <div class="pure-control-group">
        {!! Form::label('name_type', 'Name Type') !!}
        <div class="select  form-control">
            {!! Form::select('name_type', ['Personal' => 'Personal', 'Organizational' => 'Organizational'], null, ['id' => 'name_type', 'placeholder' => '-- no name type --']) !!}
        </div>
        <small id="nameTypeHelp" class="pure-form-message-inline">name type is optional</small>
    </div>

     <h5><b>Status of person</b></h5>
    <div class="pure-control-group checkboxlist">
        <label for="status" class="pure-checkbox">
            <input type="hidden" name="status" value="0">
            <input name="status" value="1" {{ (isset($person) && $person->status == 1) ? 'checked="checked" ' : '' }} type="checkbox" class="form-check-input">
            Active?
        </label>
    </div>


  {!! Form::submit($submitButtonText, ['class' => 'pure-button button-small']) !!}
</fieldset>

@include('errors._errors')