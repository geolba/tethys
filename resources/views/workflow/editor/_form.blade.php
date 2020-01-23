<fieldset id="fieldset-State">
    <legend>State & notes</legend>
    <div class="pure-g">
        @if($dataset->reject_reviewer_note)
        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {!! Form::label('reject_reviewer_note', 'reviewer reject note..') !!}
            {{-- {!! Form::select('server_state', Config::get('enums.server_states'), null, ['id' => 'server_state', 'placeholder' => '-- select server state --']) !!} --}}
            {!! Form::textarea('reject_reviewer_note', null, ['class'=>'pure-u-23-24','readonly', 'v-model' =>
            'form.reject_reviewer_note']) !!}
        </div>
        @endif
        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {!! Form::label('server_state', 'Status..') !!}
            {{-- {!! Form::select('server_state', Config::get('enums.server_states'), null, ['id' => 'server_state', 'placeholder' => '-- select server state --']) !!} --}}
            {!! Form::text('server_state', null, ['class'=>'pure-u-23-24','readonly', 'v-model' => 'form.server_state'])
            !!}
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
                {!! Form::select('type', Lang::get('doctypes'), null, ['id' => 'type', 'placeholder' => '-- select type
                --',
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
            {!! Form::text('creating_corporation', null, ['readonly', 'class' =>
            'pure-u-23-24', 'v-model' => 'form.creating_corporation', "v-validate" => "'required'"]) !!}
            <span class="md-error" v-if="errors.has('form.creating_corporation')">creating corporation is
                required.</span>
        </div>

    </div>
</fieldset>

<fieldset id="fieldset-creator">
    <legend>Creator(s)</legend>
    <div class="pure-g">
        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            <my-autocomplete title="searching active person table" v-on:person="onAddAuthor"></my-autocomplete>
        </div>
    </div>
    <div class="pure-u-1 pure-u-md-1-2 pure-div">
        {{-- {!! Form::label('additionalCreators', 'Add additional creator(s) if creator is not in database') !!} 
        <button class="pure-button button-small" @click.prevent="addNewAuthor()">+</button> --}}
    </div>
    <input name="authors" v-model="form.authors" type="hidden" class="form-check-input" v-validate="'required'"
        data-vv-as="Author">
    <person-table name="authors" v-bind:heading="'authors'" v-bind:personlist="form.authors"></person-table>
    <person-table name="contributors" v-bind:heading="'contributors'" v-bind:personlist="form.contributors">
    </person-table>
</fieldset>

<fieldset id="fieldset-titles">
    <legend>Title</legend>
    <div>

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
    <div class="pure-u-1 pure-u-md-1-2 pure-div">
        <label name="TitleMain">Add additional title(s) </label>
        <button class="pure-button button-small" @click.prevent="addTitle()"><i class="fas fa-plus-circle"></i></button>
    </div>
    <table v-if="form.titles.length" class="pure-table pure-table-horizontal">
        <thead>
            <tr>
                <th style="width: 20px;">Title</th>
                <th>Type</th>
                <th>Language</th>
                <th style="width: 130px;"></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(title, index) in form.titles">
                <td>
                    <input v-bind:name="'titles['+index+'][id]'" readonly class="form-control" v-model="title.id" hidden />
                    <input type="text" :id="'titles['+index+'][value]'" :name="'titles['+index+'][value]'"
                       v-validate="'required|min:4|max:255'" class="form-control" v-model="title.value">
                </td>
                <td>
                    <template v-if="title.type == 'Main'">
                        <input v-bind:name="'titles['+index+'][type]'" v-model="title.type" class="form-control"
                            v-validate="'required'" v-bind:readonly="title.type == 'Main'">
                    </template>
                    <template v-else>
                        <select type="text" :id="'titles['+index+'][type]'" :name="'titles['+index+'][type]'"
                            class="form-control"
                            v-validate="{required: true}"
                            v-model="title.type" v-bind:readonly="title.type == 'Main'">
                            <option v-for="option in remainingTitleTypes" :value='option'
                                :disabled="title.type == 'Main'">
                                @{{ option }}
                            </option>
                        </select>
                    </template>
                </td>
                <td>
                    <template v-if="title.type == 'Main'">
                        <input v-bind:name="'titles['+index+'][language]'" v-model="title.language"
                            class="form-control" v-validate="'required'" v-bind:readonly="title.type == 'Main'">
                    </template>
                    <template v-else>
                        <select type="text" :id="'titles['+index+'][language]'"
                            :name="'titles['+index+'][language]'" class="form-control"
                            v-validate="{required: true, translatedLanguage: [form.language, title.type]}"
                            v-model="title.language" v-bind:readonly="title.type == 'Main'">
                            <option v-for="option in languages" :value='option'>
                                @{{ option }}
                            </option>
                        </select>
                    </template>
                </td>
                <td>
                    <button v-if="title.id == undefined" class="pure-button button-small is-warning"
                        @click.prevent="removeTitle(index)">
                        <i class="fa fa-trash"></i>
                </td>
            </tr>
        </tbody>
    </table>
    </div>
</fieldset>

<fieldset id="fieldset-abstracts">
    <legend>Abstract</legend>
    <div>

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

    <div class="pure-u-1 pure-u-md-1-2 pure-div">
        <label name="DescriptionMain">Add additional abstract(s) </label>
        <button class="pure-button button-small" @click.prevent="addDescription()"><i
                class="fas fa-plus-circle"></i></button>
    </div>
    <table v-if="form.abstracts.length" class="pure-table pure-table-horizontal">
        <thead>
            <tr>
                <th style="width: 20px;">Value</th>
                <th>Type</th>
                <th>Language</th>
                <th style="width: 130px;"></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(abstract, index) in form.abstracts">
                <td>
                    <input v-bind:name="'abstracts[' +  index +'][id]'" readonly class="form-control" v-model="abstract.id" hidden />

                    <input type="text" :id="'abstracts['+ index +'][value]'"
                        :name="'abstracts['+index+'][value]'" v-validate="'required|min:4|max:2500'" v-model="abstract.value"
                        class="form-control">
                </td>
                <td>
                    <template v-if="abstract.type == 'Abstract'">
                        <input v-bind:name="'abstracts['+ index +'][type]'" v-model="abstract.type"
                            class="form-control" v-validate="'required'" v-bind:readonly="abstract.type == 'Abstract'">
                    </template>
                    <template v-else>
                        <select type="text" :id="'abstracts['+ index +'][type]'"
                            :name="'abstracts['+ index +'][type]'" class="form-control"
                            v-validate="{required: true}"
                            v-model="abstract.type" v-bind:readonly="abstract.type == 'Abstract'">
                            <option v-for="option in descriptionTypes" :value='option'>
                                @{{ option }}
                            </option>
                        </select>
                    </template>
                </td>
                <td>
                    <template v-if="abstract.type == 'Abstract'">
                        <input v-bind:name="'abstracts['+index+'][language]'" v-model="abstract.language"
                            class="form-control" v-validate="'required'" v-bind:readonly="abstract.type == 'Abstract'">
                    </template>
                    <template v-else>
                        <select type="text" :id="'abstracts['+index+'][language]'"
                            :name="'abstracts['+index+'][language]'" class="form-control"
                            v-validate="{required: true, translatedLanguage: [form.language, abstract.type]}"
                            v-model="abstract.language" v-bind:readonly="abstract.type == 'Abstract'">
                            <option v-for="option in languages" :value='option'>
                                @{{ option }}
                            </option>
                        </select>
                    </template>
                </td>
                <td>
                    <button v-if="abstract.id == undefined" class="pure-button button-small is-warning"
                        @click.prevent="removeDescription(index)">
                        <i class="fa fa-trash"></i>
                </td>
            </tr>
        </tbody>
    </table>

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

<fieldset id="fieldset-coverage">
    <legend>Coverage</legend>
    <div class="pure-g">

        <div class="pure-u-1 pure-u-md-1-2">
            <div class="pure-u-1 pure-u-md-1">
                <label for="elevation-option-one" class="pure-radio">
                    <input id="elevation-option-one" type="radio" v-model="elevation" value="absolut">
                    absolut elevation (m)
                </label>
                <label for="elevation-option-two" class="pure-radio">
                    <input id="elevation-option-two" type="radio" v-model="elevation" value="range">
                    elevation range (m)
                </label>
                <label for="elevation-option-three" class="pure-radio">
                    <input id="elevation-option-three" type="radio" v-model="elevation" value="no_elevation">
                    no elevation
                </label>
            </div>
            <div v-show="elevation === 'absolut'" class="pure-u-1 pure-u-md-1">
                {!! Form::label('elevation_absolut', 'elevation absolut: ') !!}
                {{-- {!! Form::text('elevation_absolut', null,
                ['class' => 'pure-u-23-24', 'v-model' => 'form.coverage.elevation_absolut',
                "v-validate" => "this.isElevationAbsolut ? 'required|integer' : '' " ]) !!} --}}
                 <input
                 name="coverage[elevation_absolut]"
                 type="text"
                 class="pure-u-23-24"
                 v-model="form.coverage.elevation_absolut"               
                 id="elevation_absolut"
                 v-validate.immediate="{ required: isElevationAbsolut, integer: true}"                 
               />
            </div>
            <div v-show="elevation === 'range'" class="pure-u-1 pure-u-md-1">
                {!! Form::label('elevation_min', 'elevation min: ') !!}
                {{-- {!! Form::text('elevation_min', null,
                ['class' => 'pure-u-23-24', 'v-model' => 'form.coverage.elevation_min',
                "v-validate" => "this.isElevationRange ? 'required|integer' : '' "]) !!} --}}
                 <input
                 name="coverage[elevation_min]"
                 type="text"
                 class="pure-u-23-24"
                 v-model="form.coverage.elevation_min"                
                 id="elevation_min"                 
                 v-validate.immediate="{ required: this.isElevationRange, integer: true}" 
                 />
            </div>
            <div v-show="elevation === 'range'" class="pure-u-1 pure-u-md-1">
                {!! Form::label('elevation_max', 'elevation max: ') !!}
                {{-- {!! Form::text('elevation_max', null,
                ['class' => 'pure-u-23-24', 'v-model' => 'form.coverage.elevation_max',
                "v-validate" => "this.isElevationRange ? 'required|integer' : '' "]) !!} --}}
                <input
                name="coverage[elevation_max]"
                type="text"
                class="pure-u-23-24"
                v-model="form.coverage.elevation_max"             
                id="elevation_max"               
                v-validate.immediate="{ required: this.isElevationRange, integer: true}" 
                />
            </div>
        </div>

        <div class="pure-u-1 pure-u-md-1-2">
            <div class="pure-u-1 pure-u-md-1">
                <label for="depth-option-one" class="pure-radio">
                    <input id="depth-option-one" type="radio" v-model="depth" value="absolut">
                    absolut depth (m)
                </label>
                <label for="depth-option-two" class="pure-radio">
                    <input id="depth-option-two" type="radio" v-model="depth" value="range">
                    depth range (m)
                </label>
                <label for="depth-option-three" class="pure-radio">
                    <input id="depth-option-three" type="radio" v-model="depth" value="no_depth">
                    no depth
                </label>
            </div>

            <div v-show="depth === 'absolut'" class="pure-u-1 pure-u-md-1">
                {!! Form::label('depth_absolut', 'depth absolut: ') !!}               
                 <input
                 name="coverage[depth_absolut]"
                 type="text"
                 class="pure-u-23-24"
                 v-model="form.coverage.depth_absolut"               
                 id="depth_absolut"
                 v-validate.immediate="{ required: this.isDepthAbsolut, integer: true}"                 
               />
            </div>
            <div v-show="depth === 'range'" class="pure-u-1 pure-u-md-1">
                {!! Form::label('depth_min', 'depth min: ') !!}                
                 <input
                 name="coverage[depth_min]"
                 type="text"
                 class="pure-u-23-24"
                 v-model="form.coverage.depth_min"                
                 id="depth_min"                 
                 v-validate.immediate="{ required: this.isDepthRange, integer: true}" 
                 />
            </div>
            <div v-show="depth === 'range'" class="pure-u-1 pure-u-md-1">
                {!! Form::label('depth_max', 'depth max: ') !!}                
                <input
                name="coverage[depth_max]"
                type="text"
                class="pure-u-23-24"
                v-model="form.coverage.depth_max"             
                id="depth_max"               
                v-validate.immediate="{ required: this.isDepthRange, integer: true}" 
                />
            </div>
        </div>

        <div class="pure-u-1 pure-u-md-1-2">
            <div class="pure-u-1 pure-u-md-1">
                <label for="time-option-one" class="pure-radio">
                    <input id="time-option-one" type="radio" v-model="time" value="absolut">
                    absolut time (yyyy-MM-dd HH:mm:ss)
                </label>
                <label for="time-option-two" class="pure-radio">
                    <input id="time-option-two" type="radio" v-model="time" value="range">
                    time range (yyyy-MM-dd HH:mm:ss)
                </label>
                <label for="time-option-three" class="pure-radio">
                    <input id="time-option-three" type="radio" v-model="time" value="no_time">
                    no time
                </label>
            </div>

            <div v-show="time === 'absolut'" class="pure-u-1 pure-u-md-1">
                {!! Form::label('time_absolut', 'time absolut: ') !!}
                {{-- {!! Form::datetime('time_absolut', null, ['class' => 'pure-u-23-24', 'placeholder' => 'dd.MM.yyyy HH:mm',
                'v-model' => 'dataset.coverage.time_absolut', 'data-vv-scope' => 'step-2', 'format' => 'yyyy-MM-dd HH:mm',
                "v-validate" => "this.isTimeAbsolut ? 'required|date_format:dd.MM.yyyy HH:mm:ss' : '' " ]) !!} --}}
                <datetime ref="absoluteTimeDatepicker" name="coverage[time_absolut]"
                    v-validate.immediate="this.isTimeAbsolut ? 'required' : '' "
                    format="YYYY-MM-DD h:i:s" v-model='form.coverage.time_absolut'></datetime>
                {{-- <datetime name="time_absolut" format="MM-DD-YYYY H:i:s" width="300px" v-model="dataset.coverage.time_absolut"></datetime> --}}
            </div>
            <div v-show="time === 'range'" class="pure-u-1 pure-u-md-1">
                {!! Form::label('time_min', 'time min: ') !!}
                {{-- {!! Form::datetimelocal('time_min', null, ['class' => 'pure-u-23-24', 'placeholder' => 'dd.MM.yyyy HH:mm:ss', 
                'v-model' => 'dataset.coverage.time_min', 'data-vv-scope' => 'step-2', 'step' => 1,
                "v-validate" => "this.isTimeRange ? 'required|date_format:dd.MM.yyyy HH:mm:ss' : '' "]) !!} --}}
                <datetime ref="minTimeDatepicker" name="coverage[time_min]"
                    v-validate.immediate="this.isTimeRange ? 'required' : '' "
                    format="YYYY-MM-DD h:i:s" v-model='form.coverage.time_min'></datetime>
            </div>
            <div v-show="time === 'range'" class="pure-u-1 pure-u-md-1">
                {!! Form::label('timemax', 'time max: ') !!}
                {{-- {!! Form::datetimelocal('time_max', null, ['class' => 'pure-u-23-24', 'placeholder' => 'dd.MM.yyyy HH:mm:ss',
                'v-model' => 'dataset.coverage.time_max', 'data-vv-scope' => 'step-2', 'step' => 1,
                "v-validate" => "this.isTimeRange ? 'required|date_format:dd.MM.yyyy HH:mm:ss' : '' "]) !!} --}}
                <datetime ref="maxTimeDatepicker" name="coverage[time_max]"
                    v-validate.immediate="this.isTimeRange ? 'required' : '' "
                    format="YYYY-MM-DD h:i:s" v-model='form.coverage.time_max'></datetime>
            </div>
        </div>

    </div>
</fieldset>

<fieldset id="fieldset-licenses">
    <legend>Licenses</legend>

    <div class="pure-control-group checkboxlist">
        {{-- @foreach ($licenses as $license)
        <label for={{ "license". $license->id }} class="pure-checkbox">
        @if ($loop->first)
        <input name="licenses" value={{ $license->id }} v-model="form.checkedLicenses"
            {{ (in_array($license->id, $checkeds)) ? 'checked=checked' : '' }} type="radio" class="form-check-input"
            v-validate="'required'" data-vv-as="Licence">
        <a href="{{ $license->link_licence }}" target="_blank">{{ $license->name_long }}</a>
        @else
        <input name="licenses" value={{ $license->id }} v-model="form.checkedLicenses"
            {{ (in_array($license->id, $checkeds)) ? 'checked=checked' : '' }} type="radio" class="form-check-input">
        <a href="{{ $license->link_licence }}" target="_blank">{{ $license->name_long }}</a>
        @endif
        </label>
        @endforeach --}}
        <br>
        {{-- <span>Checked license: @{{ dataset.checkedLicenses }}</span> --}}

        <template v-for="(license, index) in licenses">
            <label :for="'license'+license.id" class="pure-checkbox">
                <input name="licenses" :value="license.id" v-model="checkeds" type="radio" class="form-check-input"
                    v-validate="'required'">
                <a :href="license.link_licence" target="_blank">@{{ license.name_long }}</a>
            </label>
        </template>
        {{-- <span>old checkeds: @{{ checkeds }}</span> --}}

    </div>


</fieldset>

<fieldset id="fieldset-references">
    <legend>Dataset References</legend>
    <label name="ReferenceLabel">Add Reference </label>
    <button class="pure-button button-small" @click.prevent="addReference()"><i class="fas fa-plus-circle"></i></button>
    {{-- <table class="table table-hover" v-if="dataset.keywords.length"> --}}
    {{-- @if ($dataset->references->count() > 0) --}}
    <table v-show="form.references.length" id="references" class="pure-table pure-table-horizontal">
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

            {{-- @foreach($dataset->references as $key => $reference) --}}
            <tr v-for="(item, index) in form.references">
                <td>
                    <input v-bind:name="'references[' +  index +'][id]'" readonly class="form-control" v-model="item.id" hidden />

                    <input v-bind:name="'references[' +  index +'][value]'" class="form-control"
                        placeholder="[REFERENCE VALUE]" v-model="item.value" v-validate="'required'" />
                </td>
                <td>
                    {{-- {{ Form::text('references['.$reference->id.'][label]', $reference->label, ['class' => 'form-control',  'placeholder' => '[REFERENCE LABEL]']) }}
                    --}}
                    <input v-bind:name="'references[' +  index +'][label]'" class="form-control"
                        placeholder="[REFERENCE LABEL]" v-model="item.label" v-validate="'required'" />
                </td>
                <td>
                    {{-- {!! Form::select('references['.$reference->id.'][type]', $referenceTypes, $reference->type,
                    ['placeholder' => '[REFERENCE TYPE]', 'v-model' => 'item.type', "v-validate" => "'required'"]) !!} --}}
                    <select v-bind:name="'references[' +  index +'][type]'" v-model="item.type" class="form-control"
                        v-validate="'required'">
                        <option v-for="option in referenceTypes" :value='option'>
                            @{{ option }}
                        </option>
                    </select>
                    {{-- <span>Selected: @{{ item.type }}</span> --}}
                </td>
                <td>
                    {{-- {!! Form::select('references['.$reference->id.'][relation]', $relationTypes, $reference->relation,
                    ['placeholder' => '[REFERENCE TYPE]', 'v-model' => 'item.relation', "v-validate" => "'required'"]) !!} --}}
                    <select v-bind:name="'references[' +  index +'][relation]'" v-model="item.relation"
                        class="form-control" v-validate="'required'">
                        <option v-for="option in relationTypes" :value='option'>
                            @{{ option }}
                        </option>
                    </select>
                </td>
                <td>
                    <button v-if="item.id == undefined" class="pure-button button-small is-warning"
                        @click.prevent="removeReference(index)">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
            {{-- @endforeach --}}
        </tbody>
    </table>
    {{-- @else
    <span>...there are no references</span>
    @endif --}}
</fieldset>

<fieldset id="fieldset-keywords">
    <legend>Dataset Keywords</legend>
    <label name="SubjectLabel">Add Keyword </label>
    <input type="hidden" v-validate:keywords_length="'min_value:3'" data-vv-as="keyword list" name="keywords_list">
    <button class="pure-button button-small" @click.prevent="addKeyword()"><i class="fas fa-plus-circle"></i></button>
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
            <tr v-for="(item, index) in form.subjects" :key="item.id">
                
                <td>
                    <input v-bind:name="'subjects[' +  index +'][id]'" readonly class="form-control" v-model="item.id" hidden />
                    <input v-bind:name="'subjects[' +  index +'][value]'" class="form-control"
                        placeholder="[KEYWORD VALUE]" v-model="item.value" v-validate="'required'" />
                    {{-- {{ Form::text('keywords['.$keyword->id.'][value]', $keyword->value, ['class' => 'form-control',  'placeholder' => '[KEYWORD VALUE]']) }}
                    --}}
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
                    <input v-bind:name="'subjects[' +  index +'][type]'" readonly class="form-control"
                        placeholder="[KEYWORD TYPE]" v-model="item.type" v-validate="'required'" />
                </td>
                <td>
                    <button v-if="item.id == undefined" class="pure-button button-small is-warning"
                        @click.prevent="removeKeyword(index)">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>           
        </tbody>
    </table>
    @else
    <span>...there are no keywords</span>
    @endif
</fieldset>

<fieldset id="fieldset-files">
    <legend>Files</legend>

    {{-- <h3>File Upload</h3>--}}
    {{-- <div class="dropbox"> --}}
    <div>
        <input type="hidden" v-validate:files_length="'min_value:1'" data-vv-scope="step-4" data-vv-as="files list" name="files_list">
        {{-- <input type="file" name="uploads[]" multiple @change="filesChange($event.target.name, $event.target.files)" class="input-file">
        <p>
            Drag your file(s) here to begin<br> or click to browse
        </p>        --}}
    </div> 

    <table id="items" class="pure-table pure-table-horizontal">
        <thead>
            <tr>
                <th>#</th>              
                <th>Path Name</th>
                <th>ID</th>
                <th>Label</th>
                {{-- <th>New</th> --}}
                <th style="width: 130px;"></th>
            </tr>
        </thead>
        <tbody>           
            <tr v-for="(file, index) in form.files" :key="file.id">
                <td>
                    @{{ index +1 }}
                </td>               
                <td>
                    {{-- @if($file->exists() === true)
                    <a href="{{ route('settings.file.download', ['id' => $file->id]) }}"> {{ $file->path_name }} </a>
                    @else
                    <span class="alert">missing file: {{ $file->path_name }}</span>
                    @endif --}}
                    <a v-if="file.id != undefined" v-bind:href=" '/publish/workflow/editor/file/download/' + file.id ">                       
                      @{{ file.path_name }}                     
                    </a>
                    <span v-else> File name will be generated</span>

                
                </td>
                <td>
                    <input v-bind:name="'files[' +  index +'][id]'" readonly class="form-control" v-model="file.id" />
                </td>
                <td>                    
                    <input v-bind:name="'files['+index+'][label]'" class="form-control" placeholder="[FILE LABEL]"
                        v-model="file.label" v-validate="'required'" />
                </td>
                {{-- <td>
                    <i v-if="file.file" class="fas fa-file-upload"></i>
                    <input  type="text" v-bind:name="'files['+index+'][file]'" class="form-control" v-model="file.file"/>
                </td> --}}
                <td>
                    <button v-if="file.id == undefined" class="pure-button button-small is-warning"
                        @click.prevent="removeFile(index)">
                        <i class="fa fa-trash"></i>
                </td>
            </tr>           
        </tbody>


      


    </table>
</fieldset>

<br />
<div class="pure-controls">
    <button :disabled="errors.any()" type="submit" class="pure-button button-small">
        <i class="fas fa-save"></i>
        <span>{!! $submitButtonText !!}</span>
    </button>
</div>