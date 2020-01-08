<fieldset id="fieldset-State">
    <legend>State & notes</legend>
    <div class="pure-g">
        @if(!empty($reject_reviewer_note))
        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            {!! Form::label('reject_reviewer_note', 'editor reject note..') !!}
            {{-- {!! Form::select('server_state', Config::get('enums.server_states'), null, ['id' => 'server_state', 'placeholder' => '-- select server state --']) !!} --}}
            {!! Form::textarea('reject_editor_note', null, ['class'=>'pure-u-23-24','readonly', 'v-model' =>
            'form.reject_editor_note']) !!}
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
            {!! Form::text('creating_corporation', null, ['class' =>
            'pure-u-23-24', 'v-model' => 'form.creating_corporation', "v-validate" => "'required'"]) !!}
            <span class="md-error" v-if="errors.has('form.creating_corporation')">creating corporation is
                required.</span>
        </div>

    </div>
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
            <tr v-for="(title, key) in form.titles">
                <td>
                    <input type="text" :id="'titles['+title.id+'][value]'" :name="'titles['+title.id+'][value]'"
                        v-validate="'required'" class="form-control" v-model="title.value">
                </td>
                <td>
                    <template v-if="title.type == 'Main'">
                        <input v-bind:name="'titles['+title.id+'][type]'" v-model="title.type" class="form-control"
                            v-validate="'required'" v-bind:readonly="title.type == 'Main'">
                    </template>
                    <template v-else>
                        <select type="text" :id="'titles['+title.id+'][type]'" :name="'titles['+title.id+'][type]'"
                            class="form-control"
                            v-validate="{required: true, translatedLanguage: [form.language, title.type]}"
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
                        <input v-bind:name="'titles['+title.id+'][language]'" v-model="title.language"
                            class="form-control" v-validate="'required'" v-bind:readonly="title.type == 'Main'">
                    </template>
                    <template v-else>
                        <select type="text" :id="'titles['+title.id+'][language]'"
                            :name="'titles['+title.id+'][language]'" class="form-control"
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
                        @click.prevent="removeTitle(key)">
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
            <tr v-for="(abstract, key) in form.abstracts">
                <td>
                    {{-- <label :for="'abstracts['+abstract.id+'][value]'"> @{{ 'Title ' + (key + 1) + ':' }}</label>
                    --}}
                    <input type="text" :id="'abstracts['+abstract.id+'][value]'"
                        :name="'abstracts['+abstract.id+'][value]'" v-validate="'required'" v-model="abstract.value"
                        class="form-control">
                </td>
                <td>
                    <template v-if="abstract.type == 'Abstract'">
                        <input v-bind:name="'abstracts['+abstract.id+'][type]'" v-model="abstract.type"
                            class="form-control" v-validate="'required'" v-bind:readonly="abstract.type == 'Abstract'">
                    </template>
                    <template v-else>
                        <select type="text" :id="'abstracts['+abstract.id+'][type]'"
                            :name="'abstracts['+abstract.id+'][type]'" class="form-control"
                            v-validate="{required: true, translatedLanguage: [form.language, abstract.type]}"
                            v-model="abstract.type" v-bind:readonly="abstract.type == 'Abstract'">
                            <option v-for="option in descriptionTypes" :value='option'>
                                @{{ option }}
                            </option>
                        </select>
                    </template>
                </td>
                <td>
                    <template v-if="abstract.type == 'Abstract'">
                        <input v-bind:name="'abstracts['+abstract.id+'][language]'" v-model="abstract.language"
                            class="form-control" v-validate="'required'" v-bind:readonly="abstract.type == 'Abstract'">
                    </template>
                    <template v-else>
                        <select type="text" :id="'abstracts['+abstract.id+'][language]'"
                            :name="'abstracts['+abstract.id+'][language]'" class="form-control"
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
                        @click.prevent="removeDescription(key)">
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
                {!! Form::text('elevation_absolut', null,
                ['class' => 'pure-u-23-24', 'v-model' => 'form.coverage.elevation_absolut', 'data-vv-scope' => 'step-2',
                "v-validate" => "this.isElevationAbsolut ? 'required|integer' : '' " ]) !!}
            </div>
            <div v-show="elevation === 'range'" class="pure-u-1 pure-u-md-1">
                {!! Form::label('elevation_min', 'elevation min: ') !!}
                {!! Form::text('elevation_min', null,
                ['class' => 'pure-u-23-24', 'v-model' => 'form.coverage.elevation_min', 'data-vv-scope' => 'step-2',
                "v-validate" => "this.isElevationRange ? 'required|integer' : '' "]) !!}
            </div>
            <div v-show="elevation === 'range'" class="pure-u-1 pure-u-md-1">
                {!! Form::label('elevation_max', 'elevation max: ') !!}
                {!! Form::text('elevation_max', null,
                ['class' => 'pure-u-23-24', 'v-model' => 'form.coverage.elevation_max', 'data-vv-scope' => 'step-2',
                "v-validate" => "this.isElevationRange ? 'required|integer' : '' "]) !!}
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
                {!! Form::label('depth_absolut', 'depth absolut: ') !!} {!! Form::text('depth_absolut', null, ['class'
                => 'pure-u-23-24',
                'v-model' => 'form.coverage.depth_absolut', 'data-vv-scope' => 'step-2', "v-validate" =>
                "this.isDepthAbsolut
                ? 'required|integer' : '' " ]) !!}
            </div>
            <div v-show="depth === 'range'" class="pure-u-1 pure-u-md-1">
                {!! Form::label('depth_min', 'depth min: ') !!} {!! Form::text('depth_min', null, ['class' =>
                'pure-u-23-24', 'v-model' =>
                'form.coverage.depth_min', 'data-vv-scope' => 'step-2', "v-validate" => "this.isDepthRange ?
                'required|integer'
                : '' "]) !!}
            </div>
            <div v-show="depth === 'range'" class="pure-u-1 pure-u-md-1">
                {!! Form::label('depth_max', 'depth max: ') !!} {!! Form::text('depth_max', null, ['class' =>
                'pure-u-23-24', 'v-model' =>
                'form.coverage.depth_max', 'data-vv-scope' => 'step-2', "v-validate" => "this.isDepthRange ?
                'required|integer'
                : '' "]) !!}
            </div>
        </div>

        <div class="pure-u-1 pure-u-md-1-2">
            <div class="pure-u-1 pure-u-md-1">
                <label for="time-option-one" class="pure-radio">
                    <input id="time-option-one" type="radio" v-model="time" value="absolut">
                    absolut time (dd.MM.yyyy HH:mm:ss)
                </label>
                <label for="time-option-two" class="pure-radio">
                    <input id="time-option-two" type="radio" v-model="time" value="range">
                    time range (dd.MM.yyyy HH:mm:ss)
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
                <datetime name="time_absolut"
                    v-validate="this.isTimeAbsolut ? 'required|date_format:dd-MM-yyyy HH:mm:ss' : '' "
                    data-vv-scope="step-2" format="YYYY-MM-DD h:i:s" v-model='form.coverage.time_absolut'></datetime>
                {{-- <datetime name="time_absolut" format="MM-DD-YYYY H:i:s" width="300px" v-model="dataset.coverage.time_absolut"></datetime> --}}
            </div>
            <div v-show="time === 'range'" class="pure-u-1 pure-u-md-1">
                {!! Form::label('time_min', 'time min: ') !!}
                {{-- {!! Form::datetimelocal('time_min', null, ['class' => 'pure-u-23-24', 'placeholder' => 'dd.MM.yyyy HH:mm:ss', 
                'v-model' => 'dataset.coverage.time_min', 'data-vv-scope' => 'step-2', 'step' => 1,
                "v-validate" => "this.isTimeRange ? 'required|date_format:dd.MM.yyyy HH:mm:ss' : '' "]) !!} --}}
                <datetime name="time_min"
                    v-validate="this.isTimeRange ? 'required|date_format:dd-MM-yyyy HH:mm:ss' : '' "
                    data-vv-scope="step-2" format="DD-MM-YYYY h:i:s" v-model='form.coverage.time_min'></datetime>
            </div>
            <div v-show="time === 'range'" class="pure-u-1 pure-u-md-1">
                {!! Form::label('timemax', 'time max: ') !!}
                {{-- {!! Form::datetimelocal('time_max', null, ['class' => 'pure-u-23-24', 'placeholder' => 'dd.MM.yyyy HH:mm:ss',
                'v-model' => 'dataset.coverage.time_max', 'data-vv-scope' => 'step-2', 'step' => 1,
                "v-validate" => "this.isTimeRange ? 'required|date_format:dd.MM.yyyy HH:mm:ss' : '' "]) !!} --}}
                <datetime name="time_max"
                    v-validate="this.isTimeRange ? 'required|date_format:dd-MM-yyyy HH:mm:ss' : '' "
                    data-vv-scope="step-2" format="DD-MM-YYYY h:i:s" v-model='form.coverage.time_max'></datetime>
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
                    {{-- {{ Form::text('references['.$reference->id.'][value]', $reference->value, ['class' => 'form-control',  'placeholder' => '[REFERENCE VALUE]']) }}
                    --}}
                    <input v-bind:name="'references[' +  item.id +'][value]'" class="form-control"
                        placeholder="[REFERENCE VALUE]" v-model="item.value" v-validate="'required'" />
                </td>
                <td>
                    {{-- {{ Form::text('references['.$reference->id.'][label]', $reference->label, ['class' => 'form-control',  'placeholder' => '[REFERENCE LABEL]']) }}
                    --}}
                    <input v-bind:name="'references[' +  item.id +'][label]'" class="form-control"
                        placeholder="[REFERENCE LABEL]" v-model="item.label" v-validate="'required'" />
                </td>
                <td>
                    {{-- {!! Form::select('references['.$reference->id.'][type]', $referenceTypes, $reference->type,
                    ['placeholder' => '[REFERENCE TYPE]', 'v-model' => 'item.type', "v-validate" => "'required'"]) !!} --}}
                    <select v-bind:name="'references[' +  item.id +'][type]'" v-model="item.type" class="form-control"
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
                    <select v-bind:name="'references[' +  item.id +'][relation]'" v-model="item.relation"
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
    <label name="SubjectLabel">Add Reference </label>
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
            {{-- @foreach($dataset->subjects as $key => $keyword) --}}
            <tr v-for="(item, index) in form.subjects" :key="item.id">
                <td>
                    <input v-bind:name="'subjects[' +  item.id +'][value]'" class="form-control"
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
                    <input v-bind:name="'subjects[' +  item.id +'][type]'" readonly class="form-control"
                        placeholder="[KEYWORD TYPE]" v-model="item.type" v-validate="'required'" />
                </td>
                <td>
                    <button v-if="item.id == undefined" class="pure-button button-small is-warning"
                        @click.prevent="removeKeyword(index)">
                        <i class="fa fa-trash"></i>
                    </button>
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
                    <a v-if="'storage/' + file.path_name"
                        v-bind:src=" '/settings/file/download/' + file.id ">@{{ file.path_name }}</a>
                </td>
                <td>
                    {{-- {{ Form::text('files['.$file->id.'][label]', $file->label, ['class' => 'form-control',  'placeholder' => '[FILE LABEL]']) }}
                    --}}
                    <input v-bind:name="'files[' +  file.id +'][label]'" class="form-control" placeholder="[FILE LABEL]"
                        v-model="file.label" v-validate="'required'" />
                </td>
            </tr>
            {{-- @endforeach --}}
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