<fieldset id="fieldset-General">
 <legend>General</legend>
<div class="pure-g">

    <div class="pure-u-1 pure-u-md-1-2 pure-div">
        {!! Form::label('type', 'Type..') !!}
        <div class="select  pure-u-23-24">
        {!! Form::select('type', Lang::get('doctypes'), null, ['id' => 'type', 'placeholder' => '-- select type --']) !!}
        </div>
      
    </div>

    <div class="pure-u-1 pure-u-md-1-2 pure-div">
        {!! Form::label('server_state', 'Status..') !!}
        <div class="select pure-u-23-24">
        {!! Form::select('server_state', Config::get('enums.server_states'), null, ['id' => 'server_state']) !!}
        </div>
    </div>

    <div class="pure-u-1 pure-u-md-1-2 pure-div">
        {!! Form::label('thesis_year_accepted', 'Jahr der Erstveröffentlichung') !!}
        <div class="select pure-u-23-24">
        {!! Form::select('thesis_year_accepted', $years, null, ['id' => 'thesis_year_accepted', 'placeholder' => '-- None --']) !!}
        </div>
        <small id="thesisYearHelp" class="pure-form-message-inline">thesis_year_accepted is optional</small>
    </div>

    <div class="pure-u-1 pure-u-md-1-2 pure-div">
        {!! Form::label('project_id', 'Project..') !!}
        <div class="select pure-u-23-24">
        {!! Form::select('project_id', $projects, null, ['id' => 'project_id', 'placeholder' => '--no project--']) !!}
        </div>
        <small id="projectHelp" class="pure-form-message-inline">project is optional</small>
    </div>

    {{-- <div class="pure-control-group">
			{!! Form::label('shelf_id', 'Shelf..') !!}
			{!! Form::select('shelf_id', $shelves, null, ['id' => 'shelf_id']) !!}
		</div>  --}}

   <div class="pure-u-1 pure-u-md-1-2 pure-div">
        {!! Form::label('embargo_date', 'Embargo Date') !!}
        {!! Form::date('embargo_date', null, ['placeholder' => date('y-m-d'), 'class' => 'pure-u-23-24']) !!}
        <small id="projectHelp" class="pure-form-message-inline">embargo_date is optional</small>
    </div>
</div>
</fieldset>

<fieldset id="fieldset-titles">
    <legend>Title</legend>
    <div class="pure-g">

    @foreach($document->titles as $key => $title)
    <div class="pure-u-1 pure-u-md-1-2 pure-div">
        {{ Form::label('title', 'Title ' .($key+1).':') }}
        <!-- Notice this is an array now: -->
        {{ Form::text('titles['.$title->id.'][value]', $title->value, ['class' => 'pure-u-23-24']) }}
    </div>
    <div class="pure-u-1 pure-u-md-1-2 pure-div">
        {{ Form::label('language', 'Language..') }}
        <div class="select pure-u-23-24">
        {{ Form::select('titles['.$title->id.'][language]', $languages, $title->language, ['placeholder' => '--no language--']) }}
        </div>
    </div>
    @endforeach

    </div>
</fieldset>

<fieldset id="fieldset-abstracts">
    <legend>Abstract</legend>
    <div class="pure-g">

    @foreach($document->abstracts as $key => $abstract)
    <div class="pure-u-1 pure-u-md-1-2 pure-div">
        {{ Form::label('abstract', 'Abstract ' .($key+1).':') }}
        <!-- Notice this is an array now: -->       
        {{ Form::textarea('abstracts['.$abstract->id.'][value]', $abstract->value, ['class' => 'pure-u-23-24', 'size' => '70x6']) }}
    </div>
    <div class="pure-u-1 pure-u-md-1-2 pure-div">
        {{ Form::label('language', 'Language..') }}
        <div class="select pure-u-23-24">
        {{ Form::select('abstracts['.$abstract->id.'][language]', $languages, $abstract->language, ['placeholder' => '--no language--']) }}
        </div>
    </div>
    @endforeach

    </div>
</fieldset>

<fieldset id="fieldset-licenses">
    <legend>Licenses</legend>

    {{-- <div class="form-group">
	{!! Form::label('licenses[]', 'Licenses..') !!}
	{!! Form::select('licenses[]', $options, array_pluck($document->licenses, 'id'), ['multiple' ]) !!}
    </div> --}}

    <div class="pure-control-group checkboxlist">
        @foreach ($options as $license)

        <label for={{"license". $license->id }} class="pure-checkbox">
            <input name="licenses[]" value={{ $license->id }} {{ (in_array($license->id, $checkeds)) ? 'checked=checked' : '' }} type="checkbox" class="form-check-input">
            {{ $license->name_long }}
        </label>

        <!--{!! Form::checkbox('licenses[]', $license->id, in_array($license->id, $checkeds) ? true : false) !!}
        {!! Form::label('license' . $license->id, $license->name_long) !!}-->
        @endforeach
    </div>
</fieldset>


<fieldset id="fieldset-abstracts">
    <legend>Files</legend>
    <table id="items" class="pure-table pure-table-horizontal">
        <thead>
            <tr>
                <th>Path Name</th>
                <th>Label</th>
            </tr>
        </thead>    
        <tbody>
            @foreach($document->files as $key => $file)
            <tr>
                <td>
                    @if($file->exists() === true)
                    <a href="{{ route('settings.file.download', ['id' => $file->id]) }}"> {{ $file->path_name }} </a> 
                    @else
                    <span class="alert">missing file: {{ $file->path_name }}</span> 
                    @endif
                </td>
                <td> {{ $file->label }} </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</fieldset>

<br />
<div class="pure-controls">
    <button type="submit" class="pure-button button-small">
        <i class="fa fa-save"></i>
        <span>{!! $submitButtonText !!}</span>
    </button>
</div>
