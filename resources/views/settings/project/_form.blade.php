<fieldset>
    <div class="pure-control-group">
        {{ Form::label('name', 'project name') }}
        {{ Form::text('name', null, ['class' => 'form-control']) }}
        <em>*</em>
    </div>
    <div class="pure-control-group">
        {{ Form::label('label', 'project label') }}
        {{ Form::text('label', null, ['class' => 'form-control']) }}
        <em>*</em>
    </div>
    <div class="pure-control-group">
        {{ Form::label('description', 'description') }}
        {{ Form::textarea('description', null, ['class' => 'form-control', 'size' => '70x6']) }}
        <em>*</em>
    </div>
    {{ Form::submit($submitButtonText, ['class' => 'pure-button button-small']) }}
</fieldset>

@include('errors._errors')