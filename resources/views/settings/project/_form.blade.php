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

  <!--  <div class="pure-controls">-->
        {{ Form::submit($submitButtonText, ['class' => 'pure-button button-small']) }}
    <!--</div>-->
</fieldset>

@include('errors._errors')