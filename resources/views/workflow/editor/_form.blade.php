<fieldset id="fieldset-General">
    <legend>General</legend>
    <div class="pure-g">

        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {!! Form::label('Language', 'Language..') !!}
            <div class="select  pure-u-23-24">
                {!! Form::select('Language', $languages, null, ['placeholder' => '[language]', 'v-model' =>
                'dataset.language', "v-validate" => "'required'"]) !!}
            </div>
            <small id="languageHelp" class="pure-form-message-inline">select dataset main language</small>
        </div>

        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {!! Form::label('type', 'Type..') !!}
            <div class="select  pure-u-23-24">
                {!! Form::select('type', Lang::get('doctypes'), null, ['id' => 'type', 'placeholder' => '-- select type
                --']) !!}
            </div>
        </div>

        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {!! Form::label('project_id', 'Project..') !!}
            <div class="select pure-u-23-24">
                {!! Form::select('project_id', $projects, null, ['id' => 'project_id', 'placeholder' => '--no
                project--']) !!}
            </div>
            <small id="projectHelp" class="pure-form-message-inline">project is optional</small>
        </div>

        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {!! Form::label('server_state', 'Status..') !!}
            {{-- {!! Form::select('server_state', Config::get('enums.server_states'), null, ['id' => 'server_state', 'placeholder' => '-- select server state --']) !!} --}}
            {!! Form::text('server_state', null, ['class'=>'pure-u-23-24','readonly']) !!}

        </div>

        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {!! Form::label('reject_reviewer_note', 'reviewer reject note..') !!}
            {{-- {!! Form::select('server_state', Config::get('enums.server_states'), null, ['id' => 'server_state', 'placeholder' => '-- select server state --']) !!} --}}
            {!! Form::textarea('reject_reviewer_note', null, ['class'=>'pure-u-23-24','readonly']) !!}

        </div>

        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {!! Form::label('embargo_date', 'Embargo Date') !!}
            {!! Form::date('embargo_date', null, ['placeholder' => date('y-m-d'), 'class' => 'pure-u-23-24']) !!}
            <small id="projectHelp" class="pure-form-message-inline">embargo_date is optional</small>
        </div>

        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {!! Form::label('creating_corporation', 'Creating Corporation') !!}
            {!! Form::text('creating_corporation', null, ['class' =>
            'pure-u-23-24', 'v-model' => 'dataset.creating_corporation', "v-validate" => "'required'", 'data-vv-scope'
            => 'step-1']) !!}
        </div>

    </div>
</fieldset>

<fieldset id="fieldset-geolocation">
    <legend>Coverage: Geolocation, Elevation, Depth, Time</legend>
    <div class="pure-g">
        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {!! Form::label('xmin', 'xmin: ') !!}
            {!! Form::text('coverage[xmin]', null, ['class' => 'pure-u-23-24', 'v-model' => 'dataset.coverage.xmin']) !!}
        </div>
        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {!! Form::label('ymin', 'ymin: ') !!}
            {!! Form::text('coverage[ymin]', null, ['class' => 'pure-u-23-24', 'v-model' => 'dataset.coverage.ymin']) !!}
        </div>

        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {!! Form::label('xmax', 'xmax: ') !!}
            {!! Form::text('coverage[xmax]', null, ['class' => 'pure-u-23-24', 'v-model' => 'dataset.coverage.xmax']) !!}
        </div>
        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {!! Form::label('ymax', 'ymax: ') !!}
            {!! Form::text('coverage[ymax]', null, ['class' => 'pure-u-23-24', 'v-model' => 'dataset.coverage.ymax']) !!}
        </div>

        @if (isset($dataset->elevation_absolut))
        <div v-show="elevation === 'absolut'" class="pure-u-1 pure-u-md-1">
            {!! Form::label('elevation_absolut', 'elevation absolut: ') !!}
            {!! Form::text('coverage[elevation_absolut]', null,
            ['class' => 'pure-u-23-24', 'v-model' => 'dataset.coverage.elevation_absolut', 'data-vv-scope' => 'step-2',
            "v-validate" => "this.isElevationAbsolut ? 'required|integer' : '' " ]) !!}
        </div>
        @elseif (isset($dataset->elevation_min) && isset($dataset->elevation_max))
        <div v-show="elevation === 'range'" class="pure-u-1 pure-u-md-1">
            {!! Form::label('elevation_min', 'elevation min: ') !!}
            {!! Form::text('coverage[elevation_min]', null,
            ['class' => 'pure-u-23-24', 'v-model' => 'dataset.coverage.elevation_min', 'data-vv-scope' => 'step-2',
            "v-validate" => "this.isElevationRange ? 'required|integer' : '' "]) !!}
        </div>
        <div v-show="elevation === 'range'" class="pure-u-1 pure-u-md-1">
            {!! Form::label('elevation_max', 'elevation max: ') !!}
            {!! Form::text('coverage[elevation_max]', null,
            ['class' => 'pure-u-23-24', 'v-model' => 'dataset.coverage.elevation_max', 'data-vv-scope' => 'step-2',
            "v-validate" => "this.isElevationRange ? 'required|integer' : '' "]) !!}
        </div>
        @endif

        @if (isset($dataset->depth_absolut))
        <div v-show="elevation === 'absolut'" class="pure-u-1 pure-u-md-1">
            {!! Form::label('depth_absolut', 'depth absolut: ') !!}
            {!! Form::text('coverage[depth_absolut]', null,
            ['class' => 'pure-u-23-24', 'v-model' => 'dataset.coverage.depth_absolut', 'data-vv-scope' => 'step-2',
            "v-validate" => "this.isElevationAbsolut ? 'required|integer' : '' " ]) !!}
        </div>
        @elseif (isset($dataset->elevation_min) && isset($dataset->elevation_max))
        <div v-show="elevation === 'range'" class="pure-u-1 pure-u-md-1">
            {!! Form::label('depth_min', 'depth min: ') !!}
            {!! Form::text('coverage[depth_min]', null,
            ['class' => 'pure-u-23-24', 'v-model' => 'dataset.coverage.depth_min', 'data-vv-scope' => 'step-2',
            "v-validate" => "this.isElevationRange ? 'required|integer' : '' "]) !!}
        </div>
        <div v-show="elevation === 'range'" class="pure-u-1 pure-u-md-1">
            {!! Form::label('depth_max', 'depth max: ') !!}
            {!! Form::text('coverage[depth_max]', null,
            ['class' => 'pure-u-23-24', 'v-model' => 'dataset.coverage.depth_max', 'data-vv-scope' => 'step-2',
            "v-validate" => "this.isElevationRange ? 'required|integer' : '' "]) !!}
        </div>
        @endif

        @if (isset($dataset->depth_absolut))
        <div v-show="elevation === 'absolut'" class="pure-u-1 pure-u-md-1">
            {!! Form::label('time_absolut', 'time absolut: ') !!}
            {!! Form::text('coverage[time_absolut]', null,
            ['class' => 'pure-u-23-24', 'v-model' => 'dataset.coverage.time_absolut', 'data-vv-scope' => 'step-2',
            "v-validate" => "this.isElevationAbsolut ? 'required|integer' : '' " ]) !!}
        </div>
        @elseif (isset($dataset->elevation_min) && isset($dataset->elevation_max))
        <div v-show="elevation === 'range'" class="pure-u-1 pure-u-md-1">
            {!! Form::label('time_min', 'time min: ') !!}
            {!! Form::text('coverage[time_min]', null,
            ['class' => 'pure-u-23-24', 'v-model' => 'dataset.coverage.time_min', 'data-vv-scope' => 'step-2',
            "v-validate" => "this.isElevationRange ? 'required|integer' : '' "]) !!}
        </div>
        <div v-show="elevation === 'range'" class="pure-u-1 pure-u-md-1">
            {!! Form::label('time_max', 'time max: ') !!}
            {!! Form::text('coverage[time_max]', null,
            ['class' => 'pure-u-23-24', 'v-model' => 'dataset.coverage.time_max', 'data-vv-scope' => 'step-2',
            "v-validate" => "this.isElevationRange ? 'required|integer' : '' "]) !!}
        </div>
        @endif,
    </div>
</fieldset>

<fieldset id="fieldset-titles">
    <legend>Title</legend>
    <div class="pure-g">

        @foreach($dataset->titles as $key => $title)
        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {{ Form::label('title', 'Title ' .($key+1).':') }}
            <!-- Notice this is an array now: -->
            {{ Form::text('titles['.$title->id.'][value]', $title->value, ['class' => 'pure-u-23-24']) }}
        </div>
        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {{ Form::label('language', 'Language..') }}
            {{ Form::text('titles['.$title->id.'][language]', $title->language, ['placeholder' => '--no language--', 'class' => 'pure-u-23-24', 'readonly']) }}
        </div>
        @endforeach

    </div>
</fieldset>

<fieldset id="fieldset-abstracts">
    <legend>Abstract</legend>
    <div class="pure-g">

        @foreach($dataset->abstracts as $key => $abstract)
        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {{ Form::label('abstract', 'Abstract ' .($key+1).':') }}
            <!-- Notice this is an array now: -->
            {{ Form::textarea('abstracts['.$abstract->id.'][value]', $abstract->value, ['class' => 'pure-u-23-24', 'size' => '70x6']) }}
        </div>
        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {{ Form::label('language', 'Language..') }}
            {{ Form::text('abstracts['.$abstract->id.'][language]', $abstract->language, ['placeholder' => '--no language--', 'class' => 'pure-u-23-24', 'readonly']) }}
        </div>
        @endforeach

    </div>
</fieldset>

<fieldset id="fieldset-licenses">
    <legend>Licenses</legend>

    {{-- <div class="form-group">
	{!! Form::label('licenses[]', 'Licenses..') !!}
	{!! Form::select('licenses[]', $options, array_pluck($dataset->licenses, 'id'), ['multiple' ]) !!}
    </div> --}}

    <div class="pure-control-group checkboxlist">
        @foreach ($options as $license)

        <label for={{"license". $license->id }} class="pure-checkbox">
            <input name="licenses[]" value={{ $license->id }}
                {{ (in_array($license->id, $checkeds)) ? 'checked=checked' : '' }} type="checkbox"
                class="form-check-input">
            {{ $license->name_long }}
        </label>

        <!--{!! Form::checkbox('licenses[]', $license->id, in_array($license->id, $checkeds) ? true : false) !!}
        {!! Form::label('license' . $license->id, $license->name_long) !!}-->
        @endforeach
    </div>
</fieldset>

<fieldset id="fieldset-references">
    <legend>Dataset References</legend>
    {{-- <table class="table table-hover" v-if="dataset.keywords.length"> --}}
    @if ($dataset->references->count() > 0)
    <table id="references" class="pure-table pure-table-horizontal">
        <thead>
            <tr>
                <th style="width: 20px;">Reference value</th>
                <th style="width: 20px;">Label</th>
                <th>Type</th>
                <th>Relation</th>
                <th style="width: 130px;"></th>
            </tr>
        </thead>
        <tbody>
            {{-- <tr v-for="(item, index) in dataset.keywords"> --}}
            @foreach($dataset->references as $key => $reference)
            <tr>
                <td>
                    {{ Form::text('references['.$reference->id.'][value]', $reference->value, ['class' => 'form-control',  'placeholder' => '[REFERENCE VALUE]']) }}
                </td>
                <td>
                    {{ Form::text('references['.$reference->id.'][label]', $reference->label, ['class' => 'form-control',  'placeholder' => '[REFERENCE LABEL]']) }}
                </td>
                <td>
                    {!! Form::select('references['.$reference->id.'][type]', $referenceTypes, $reference->type,
                    ['placeholder' => '[reference type]', 'v-model' =>
                    'item.type', "v-validate" => "'required'", 'data-vv-scope' => 'step-2']) !!}
                </td>
                <td>
                    {!! Form::select('references['.$reference->id.'][relation]', $relationTypes, $reference->relation,
                    ['placeholder' => '[relation type]', 'v-model' =>
                    'item.relation', "v-validate" => "'required'", 'data-vv-scope' => 'step-2']) !!}
                </td>
                <td>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <span>...there are no references</span>
    @endif
</fieldset>

<fieldset id="fieldset-keywords">
    <legend>Dataset Keywords</legend>
    {{-- <table class="table table-hover" v-if="dataset.keywords.length"> --}}
    @if ($dataset->subjects->count() > 0)
    <table id="keywords" class="pure-table pure-table-horizontal">
        <thead>
            <tr>
                <th style="width: 20px;">Keyword</th>
                <th>Type</th>
                <th style="width: 130px;"></th>
            </tr>
        </thead>
        <tbody>
            {{-- <tr v-for="(item, index) in dataset.keywords"> --}}
            @foreach($dataset->subjects as $key => $keyword)
            <tr>
                <td>
                    {{-- <input name="Keyword Value" class="form-control" placeholder="[KEYWORD VALUE]" v-model="item.value" v-validate="'required'"
                        data-vv-scope="step-2" /> --}}
                    {{ Form::text('keywords['.$keyword->id.'][value]', $keyword->value, ['class' => 'form-control',  'placeholder' => '[KEYWORD VALUE]']) }}
                </td>
                <td>
                    {!! Form::select('keywords['.$keyword->id.'][type]', $keywordTypes, $keyword->type, ['placeholder'
                    => '[keyword type]', 'v-model' => 'item.type', "v-validate" => "'required'", 'data-vv-scope' => 'step-2']) !!}
                </td>
                <td>
                    {{-- <button class="pure-button button-small is-warning" @click.prevent="removeKeyword(index)">Remove</button> --}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <span>...there are no keywords</span>
    @endif
</fieldset>

<fieldset id="fieldset-files">
    <legend>Files</legend>
    <table id="items" class="pure-table pure-table-horizontal">
        <thead>
            <tr>
                <th>Path Name</th>
                <th>Label</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dataset->files as $key => $file)
            <tr>
                <td>
                    @if($file->exists() === true)
                    <a href="{{ route('settings.file.download', ['id' => $file->id]) }}"> {{ $file->path_name }} </a>
                    @else
                    <span class="alert">missing file: {{ $file->path_name }}</span>
                    @endif
                </td>
                <td>
                    {{-- {{ $file->label }} --}}
                    {{ Form::text('files['.$file->id.'][label]', $file->label, ['class' => 'form-control',  'placeholder' => '[FILE LABEL]']) }}
                </td>
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
