<fieldset id="fieldset-State">
        <legend>State & notes</legend>
        <div class="pure-g">
            @if(!empty($reject_reviewer_note))
            <div class="pure-u-1 pure-u-md-1-2 pure-div">
                {!! Form::label('reject_reviewer_note', 'editor reject note..') !!}
                {{-- {!! Form::select('server_state', Config::get('enums.server_states'), null, ['id' => 'server_state', 'placeholder' => '-- select server state --']) !!} --}}
                {!! Form::textarea('reject_editor_note', null, ['class'=>'pure-u-23-24','readonly', 'v-model' => 'form.reject_editor_note']) !!}
            </div>
            @endif
            <div class="pure-u-1 pure-u-md-1-2 pure-div">
                {!! Form::label('server_state', 'Status..') !!}
                {{-- {!! Form::select('server_state', Config::get('enums.server_states'), null, ['id' => 'server_state', 'placeholder' => '-- select server state --']) !!} --}}
                {!! Form::text('server_state', null, ['class'=>'pure-u-23-24','readonly', 'v-model' => 'form.server_state']) !!}
            </div>            
        </div>
</fieldset>

<fieldset id="fieldset-General">
    <legend>General</legend>
    <div class="pure-g">

        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {!! Form::label('Language', 'Language..') !!}
            <div class="select  pure-u-23-24">
                {!! Form::select('language', $languages, null, ['placeholder' => '[language]', 'v-model' =>
                'form.language', "data-vv-as" => "Language", "v-validate" => "'required'"]) !!}
            </div>
            <small id="languageHelp" class="pure-form-message-inline">select dataset main language</small>
        </div>

        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {!! Form::label('type', 'Type..') !!}
            <div class="select  pure-u-23-24">
                {!! Form::select('type', Lang::get('doctypes'), null, ['id' => 'type', 'placeholder' => '-- select type --', 
                'v-model' => 'form.type', "v-validate" => "'required'"]) !!}
            </div>
        </div>

        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {!! Form::label('project_id', 'Project..') !!}
            <div class="select pure-u-23-24">
                {{-- {!! Form::select('project_id', $projects, null, ['id' => 'project_id', 'placeholder' => '--no
                project--', 'v-model' => 'form.project_id']) !!} --}}
                <select name="project_id" v-model="form.project_id">
                    <option :value="null" selected hidden disabled>--no project--</option>
                    <option v-for="(item, key) in projects" :value="key">@{{item}}</option>
                </select>
            </div>
            <small id="projectHelp" class="pure-form-message-inline">project is optional</small>
        </div>     

        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {!! Form::label('creating_corporation', 'Creating Corporation') !!}
            {!! Form::text('creating_corporation', null, ['class' =>
            'pure-u-23-24', 'v-model' => 'form.creating_corporation', "v-validate" => "'required'"]) !!}
            <span class="md-error" v-if="errors.has('form.creating_corporation')">creating corporation is required.</span>
        </div>

    </div>
</fieldset>

<fieldset id="fieldset-dates">
    <legend>Date(s)</legend>   
    <div class="pure-u-1 pure-u-md-1-2 pure-div">
        {!! Form::label('embargo_date', 'Embargo Date') !!}
        {!! Form::date('embargo_date', null, ['placeholder' => date('y-m-d'), 'class'
        => 'pure-u-23-24', 'v-model' => 'form.embargo_date']) !!}
        <small id="projectHelp" class="pure-form-message-inline">EmbargoDate is optional</small>
    </div>
</fieldset>

<fieldset id="fieldset-geolocation">
    <legend>Geo Location</legend>
    <div class="pure-g">
        <div class="pure-u-1 pure-u-md-1 pure-u-lg-1 pure-div">
            <locations-map v-bind:geolocation="form.coverage"></locations-map>
        </div>        
    </div>
</fieldset> 

{{-- <fieldset id="fieldset-geolocation">
    <legend>Coverage: Geolocation, Elevation, Depth, Time</legend>
    <div class="pure-g">
        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {!! Form::label('xmin', 'xmin: ') !!}
            {!! Form::text('coverage[xmin]', null, ['class' => 'pure-u-23-24', 'v-model' => 'form.coverage.xmin'])
            !!}
        </div>
        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {!! Form::label('ymin', 'ymin: ') !!}
            {!! Form::text('coverage[ymin]', null, ['class' => 'pure-u-23-24', 'v-model' => 'form.coverage.ymin'])
            !!}
        </div>

        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {!! Form::label('xmax', 'xmax: ') !!}
            {!! Form::text('coverage[xmax]', null, ['class' => 'pure-u-23-24', 'v-model' => 'form.coverage.xmax'])
            !!}
        </div>
        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {!! Form::label('ymax', 'ymax: ') !!}
            {!! Form::text('coverage[ymax]', null, ['class' => 'pure-u-23-24', 'v-model' => 'form.coverage.ymax'])
            !!}
        </div>

        @if (isset($dataset->elevation_absolut))
        <div v-show="elevation === 'absolut'" class="pure-u-1 pure-u-md-1">
            {!! Form::label('elevation_absolut', 'elevation absolut: ') !!}
            {!! Form::text('coverage[elevation_absolut]', null,
            ['class' => 'pure-u-23-24', 'v-model' => 'form.coverage.elevation_absolut', 'data-vv-scope' => 'step-2',
            "v-validate" => "this.isElevationAbsolut ? 'required|integer' : '' " ]) !!}
        </div>
        @elseif (isset($dataset->elevation_min) && isset($dataset->elevation_max))
        <div v-show="elevation === 'range'" class="pure-u-1 pure-u-md-1">
            {!! Form::label('elevation_min', 'elevation min: ') !!}
            {!! Form::text('coverage[elevation_min]', null,
            ['class' => 'pure-u-23-24', 'v-model' => 'form.coverage.elevation_min', 'data-vv-scope' => 'step-2',
            "v-validate" => "this.isElevationRange ? 'required|integer' : '' "]) !!}
        </div>
        <div v-show="elevation === 'range'" class="pure-u-1 pure-u-md-1">
            {!! Form::label('elevation_max', 'elevation max: ') !!}
            {!! Form::text('coverage[elevation_max]', null,
            ['class' => 'pure-u-23-24', 'v-model' => 'form.coverage.elevation_max', 'data-vv-scope' => 'step-2',
            "v-validate" => "this.isElevationRange ? 'required|integer' : '' "]) !!}
        </div>
        @endif

        @if (isset($dataset->depth_absolut))
        <div v-show="elevation === 'absolut'" class="pure-u-1 pure-u-md-1">
            {!! Form::label('depth_absolut', 'depth absolut: ') !!}
            {!! Form::text('coverage[depth_absolut]', null,
            ['class' => 'pure-u-23-24', 'v-model' => 'form.coverage.depth_absolut', 'data-vv-scope' => 'step-2',
            "v-validate" => "this.isElevationAbsolut ? 'required|integer' : '' " ]) !!}
        </div>
        @elseif (isset($dataset->elevation_min) && isset($dataset->elevation_max))
        <div v-show="elevation === 'range'" class="pure-u-1 pure-u-md-1">
            {!! Form::label('depth_min', 'depth min: ') !!}
            {!! Form::text('coverage[depth_min]', null,
            ['class' => 'pure-u-23-24', 'v-model' => 'form.coverage.depth_min', 'data-vv-scope' => 'step-2',
            "v-validate" => "this.isElevationRange ? 'required|integer' : '' "]) !!}
        </div>
        <div v-show="elevation === 'range'" class="pure-u-1 pure-u-md-1">
            {!! Form::label('depth_max', 'depth max: ') !!}
            {!! Form::text('coverage[depth_max]', null,
            ['class' => 'pure-u-23-24', 'v-model' => 'form.coverage.depth_max', 'data-vv-scope' => 'step-2',
            "v-validate" => "this.isElevationRange ? 'required|integer' : '' "]) !!}
        </div>
        @endif

        @if (isset($dataset->depth_absolut))
        <div v-show="elevation === 'absolut'" class="pure-u-1 pure-u-md-1">
            {!! Form::label('time_absolut', 'time absolut: ') !!}
            {!! Form::text('coverage[time_absolut]', null,
            ['class' => 'pure-u-23-24', 'v-model' => 'form.coverage.time_absolut', 'data-vv-scope' => 'step-2',
            "v-validate" => "this.isElevationAbsolut ? 'required|integer' : '' " ]) !!}
        </div>
        @elseif (isset($dataset->elevation_min) && isset($dataset->elevation_max))
        <div v-show="elevation === 'range'" class="pure-u-1 pure-u-md-1">
            {!! Form::label('time_min', 'time min: ') !!}
            {!! Form::text('coverage[time_min]', null,
            ['class' => 'pure-u-23-24', 'v-model' => 'form.coverage.time_min', 'data-vv-scope' => 'step-2',
            "v-validate" => "this.isElevationRange ? 'required|integer' : '' "]) !!}
        </div>
        <div v-show="elevation === 'range'" class="pure-u-1 pure-u-md-1">
            {!! Form::label('time_max', 'time max: ') !!}
            {!! Form::text('coverage[time_max]', null,
            ['class' => 'pure-u-23-24', 'v-model' => 'form.coverage.time_max', 'data-vv-scope' => 'step-2',
            "v-validate" => "this.isElevationRange ? 'required|integer' : '' "]) !!}
        </div>
        @endif,
    </div>
</fieldset> --}}

<fieldset id="fieldset-titles">
    <legend>Title</legend>
    <div class="pure-g">

        {{-- @foreach($dataset->titles as $key => $title)
        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {{ Form::label('title', 'Title ' .($key+1).':') }}           
            {{ Form::text('titles['.$title->id.'][value]', $title->value, ['class' => 'pure-u-23-24']) }}
        </div>
        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {{ Form::label('language', 'Language..') }}
            {{ Form::text('titles['.$title->id.'][language]', $title->language, ['placeholder' => '--no language--', 'class' => 'pure-u-23-24', 'readonly']) }}
        </div>
        @endforeach --}}

        <template v-for="(title, key) in form.titles">
            <div class="pure-u-1 pure-u-md-1-2 pure-div"> 
               <label :for="'titles['+title.id+'][value]'"> @{{ 'Title ' + (key + 1) + ':' }}</label>
                <input type="text" :id="'titles['+title.id+'][value]'" :name="'titles['+title.id+'][value]'" v-model="title.value" class="pure-u-23-24">
            </div>
            <div class="pure-u-1 pure-u-md-1-2 pure-div"> 
                <label :for="'titles['+title.id+'][language]'"> @{{ 'Language for title ' + (key + 1) + ':' }}</label>
                <input type="text" :id="'titles['+title.id+'][language]'" :name="'titles['+title.id+'][language]'" v-model="title.language" class="pure-u-23-24" readonly>            
            </div>            
        </template>

    </div>
</fieldset>

<fieldset id="fieldset-abstracts">
    <legend>Abstract</legend>
    <div class="pure-g">

        {{-- @foreach($dataset->abstracts as $key => $abstract)
        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {{ Form::label('abstract', 'Abstract ' .($key+1).':') }}
            <!-- Notice this is an array now: -->
            {{ Form::textarea('abstracts['.$abstract->id.'][value]', $abstract->value, ['class' => 'pure-u-23-24', 'size' => '70x6']) }}
        </div>
        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {{ Form::label('language', 'Language..') }}
            {{ Form::text('abstracts['.$abstract->id.'][language]', $abstract->language, ['placeholder' => '--no language--', 'class' => 'pure-u-23-24', 'readonly']) }}
        </div>
        @endforeach --}}

        <template v-for="(abstract, key) in form.abstracts">
            <div class="pure-u-1 pure-u-md-1-2 pure-div"> 
               <label :for="'abstracts['+abstract.id+'][value]'"> @{{ 'Title ' + (key + 1) + ':' }}</label>
                <input type="text" :id="'abstracts['+abstract.id+'][value]'" :name="'abstracts['+abstract.id+'][value]'" v-model="abstract.value" class="pure-u-23-24">
            </div>
            <div class="pure-u-1 pure-u-md-1-2 pure-div"> 
                <label :for="'abstracts['+abstract.id+'][language]'"> @{{ 'Language for abstract ' + (key + 1) + ':' }}</label>
                <input type="text" :id="'abstracts['+abstract.id+'][language]'" :name="'abstracts['+abstract.id+'][language]'" v-model="abstract.language" class="pure-u-23-24" readonly>            
            </div>            
        </template>

    </div>
</fieldset>

<fieldset id="fieldset-licenses">
    <legend>Licenses</legend>

    <div class="pure-control-group checkboxlist">
        {{-- @foreach ($licenses as $license)
        <label for={{ "license". $license->id }} class="pure-checkbox">
            @if ($loop->first)
            <input name="licenses" value={{ $license->id }} v-model="form.checkedLicenses" {{ (in_array($license->id, $checkeds)) ? 'checked=checked' : '' }} 
            type="radio" class="form-check-input" v-validate="'required'" data-vv-as="Licence">
            <a href="{{ $license->link_licence }}" target="_blank">{{ $license->name_long }}</a>
            @else
            <input name="licenses" value={{ $license->id }} v-model="form.checkedLicenses" {{ (in_array($license->id, $checkeds)) ? 'checked=checked' : '' }} 
            type="radio" class="form-check-input">
            <a href="{{ $license->link_licence }}" target="_blank">{{ $license->name_long }}</a>
            @endif
        </label> 
        @endforeach --}}
        <br>
        {{-- <span>Checked license: @{{ dataset.checkedLicenses }}</span> --}}

        <template v-for="(license, index) in licenses">
        <label :for="'license'+license.id" class="pure-checkbox">            
            <input name="licenses" :value="license.id" v-model="checkeds" type="radio" class="form-check-input" v-validate="'required'" >
            <a :href="license.link_licence" target="_blank">@{{ license.name_long }}</a>          
        </label>        
    </template>   
    {{-- <span>old checkeds: @{{ checkeds }}</span> --}}

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
           
            @foreach($dataset->references as $key => $reference)
            <tr v-for="(item, index) in form.references">
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
            {{-- @foreach($dataset->subjects as $key => $keyword) --}}
            <tr>
            <tr v-for="(item, index) in form.subjects" :key="item.id"> 
                <td>
                <input v-bind:name="'subjects[' +  item.id +'][value]'" class="form-control" placeholder="[KEYWORD VALUE]" v-model="item.value" v-validate="'required'" />
                    {{-- {{ Form::text('keywords['.$keyword->id.'][value]', $keyword->value, ['class' => 'form-control',  'placeholder' => '[KEYWORD VALUE]']) }} --}}
                </td>
                <td>
                    {{-- {!! Form::select('keywords['.$keyword->id.'][type]', $keywordTypes, $keyword->type, ['placeholder'
                    => '[keyword type]', 'v-model' =>
                    'item.type', "v-validate" => "'required'", 'data-vv-scope' => 'step-2']) !!} --}}                    
                    {{-- <select  v-bind:name="'keywords[' +  item.id +'][type]'" v-model="item.type" class="form-control">
                            <option value="" disabled>[keyword type]</option>
                        @foreach($keywordTypes as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select> --}}
                    <input v-bind:name="'subjects[' +  item.id +'][type]'" readonly class="form-control" placeholder="[KEYWORD TYPE]" v-model="item.type" v-validate="'required'" />
                </td>
                <td>
                    {{-- <button class="pure-button button-small is-warning" @click.prevent="removeKeyword(index)">Remove</button> --}}
                </td>
            </tr>
            {{-- @endforeach --}}
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
            {{-- @foreach($dataset->files as $key => $file) --}}
            <tr v-for="(file, index) in form.files" :key="file.id">
                <td>
                    {{-- @if($file->exists() === true)
                    <a href="{{ route('settings.file.download', ['id' => $file->id]) }}"> {{ $file->path_name }} </a>
                    @else
                    <span class="alert">missing file: {{ $file->path_name }}</span>
                    @endif --}}
                    <a v-if="'storage/' + file.path_name" v-bind:src= " '/settings/file/download/' + file.id ">@{{ file.path_name }}</a>
                </td>
                <td>                  
                    {{-- {{ Form::text('files['.$file->id.'][label]', $file->label, ['class' => 'form-control',  'placeholder' => '[FILE LABEL]']) }} --}}
                    <input v-bind:name="'files[' +  file.id +'][label]'" class="form-control" placeholder="[FILE LABEL]" v-model="file.label" v-validate="'required'" />
                </td>
            </tr>
            {{-- @endforeach --}}
        </tbody>
    </table>
</fieldset>

<br />
<div class="pure-controls">
    <button :disabled="errors.any()" type="submit" class="pure-button button-small">
        <i class="fa fa-share"></i>
        <span>{!! $submitButtonText !!}</span>
    </button>
</div>