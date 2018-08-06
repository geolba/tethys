<fieldset>

    <div class="pure-g">

        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {!! Form::label('name', 'Lizenzname') !!}
            {!! Form::text('name_long', null, ['class' => 'pure-u-23-24']) !!}
        </div>

        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {!! Form::label('language', 'Sprache') !!}
            <div class="select  pure-u-23-24">
            {!! Form::select('language', $languages, null, ['id' => 'language', 'placeholder' => '--no language--']) !!}
            </div>
            <small id="languageHelp" class="pure-form-message-inline">language is optional</small>
        </div>

        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {!! Form::label('link_licence', 'URI zur Lizenz') !!}
            {!! Form::text('link_licence', null, ['class' => 'pure-u-23-24']) !!}
        </div>

        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {!! Form::label('link_logo', 'URI zum Logo') !!}
            {!! Form::text('link_logo', null, ['class' => 'pure-u-23-24']) !!}
        </div>

        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {!! Form::label('desc_text', 'Beschreibungstext') !!}
            {!! Form::textarea('desc_text', null, ['class' => 'pure-u-23-24', 'size' => '70x6']) !!}
        </div>

        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {!! Form::label('desc_markup', 'Beschreibung als Markup') !!}
            {!! Form::textarea('desc_markup', null, ['class' => 'pure-u-23-24', 'size' => '70x6']) !!}
        </div>

        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {!! Form::label('comment_internal', 'Interne Bermerkung') !!}
            {!! Form::textarea('comment_internal', null, ['class' => 'pure-u-23-24', 'size' => '70x6']) !!}
        </div>

        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {!! Form::label('mime_type', 'Internet Media Typ') !!}
            {!! Form::text('mime_type', null, ['class' => 'pure-u-23-24']) !!}
        </div>

        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {!! Form::label('sort_order', 'Sortierreihenfolge') !!}
            {!! Form::text('sort_order', null, ['class' => 'pure-u-23-24']) !!}
        </div>

    </div>

    <!-- checkboxes -->
    <label for="active" class="pure-checkbox">
        <input type="hidden" name="active" value="0">
        <input name="active" value="1" {{ ($license->active == 1) ? 'checked="checked" ' : '' }} type="checkbox" class="form-check-input"> 
        Active?
    </label>

    <label for="pod_allowed" class="pure-checkbox">
        <input type="hidden" name="pod_allowed" value="0">
        <input name="pod_allowed" value="1" {{ ($license->pod_allowed == 1) ? 'checked="checked" ' : '' }} type="checkbox" class="form-check-input">
        Print on Demand
    </label>


    <br />
    <div class="pure-controls">
        {!! Form::submit($submitButtonText, ['class' => 'pure-button button-small']) !!}
    </div>


</fieldset>

@include('errors._errors')