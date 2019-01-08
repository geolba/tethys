@extends('settings.layouts.app') 

@section('title', 'Publish') 

@section('content')
<div class="header">
    <h3 class="header-title">
        <i class="fa fa-upload"></i> Publish New Dataset
    </h3>
</div>

<div id="app" class="box-content">
    {{--
    <form action={{ route( 'publish.dataset.store1') }} method="post" class="pure-form" enctype="multipart/form-data">
        --}}
        <main class="steps pure-form" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div v-if="step === 1 && isInitial" data-vv-scope="step-1">
                <h1>Step One</h1>

                <div class="form-group">
                    <legend>Datensatztyp</legend>
                    <div class="description hint">
                        <p>Bitte wählen Sie einen Datensatztyp aus der Liste aus.</p>
                    </div>
                    <p></p>
                    <div class="form-item">
                        <label for="documentType">Datensatztyp<span class="required" title="Dieses Feld muss ausgefüllt werden."> *</span></label>
                        <div class="select" style="width:300px" title="Bitte wählen Sie einen Datensatztyp aus der Liste aus.">
                            {!! Form::select('Type', Lang::get('doctypes'), null, ['id' => 'type', 'placeholder' => '-- select type --', 'v-model' =>
                            'dataset.type', "v-validate" => "'required'", 'data-vv-scope' => 'step-1']) !!}
                        </div>
                    </div>
                </div>

                <div :class="{'form-group':true, 'has-error':errors.has('rights')}">
                    <legend>Einräumung eines einfachen Nutzungsrechts</legend>                 
                        
                        <label for="rights" class="pure-checkbox">                           
                            <input class="form-checkbox" name="rights" id="rights" type="checkbox" v-model="dataset.rights"  v-validate="'required'" data-vv-scope="step-1"> 
                            I accept
                            <a target="_blank" href="{{ route("frontend.pages.show", ['page_slug'=>'terms-and-conditions']) }}">
                                    {!! trans('validation.attributes.backend.create-dataset.terms_and_conditions').'*' !!}
                                </a>
                        </label>

                    

                        <br />
                        <i v-show="errors.has('step-1.rights')" class="fa fa-warning"></i>
                        <span v-show="errors.has('step-1.rights')" class="text-danger">@{{ errors.first('step-1.rights') }}</span>
                        
                   
                        <span class="help-block">You must agree to continue</span> {{-- </div> --}}
                </div>

                <br />
                <div class="pure-controls">
                <button @click.prevent="next('step-1')" class="pure-button button-small" :disabled="errors.any()">               
                    <i class="fa fa-arrow-right"></i>
                    <span>Continue</span>
                </button>
                </div>
                <div v-if="errors.items.length > 0">
                    <b>Please correct the following error(s):</b>
                    <ul class="alert validation-summary-errors">
                        <li style="margin-left:5px;" v-for="error in errors.items">@{{ error.msg }}</li>
                    </ul>
                </div>
            </div>

            <div v-if="step === 2 && isInitial" data-vv-scope="step-2">
                <h1>Step Two</h1>
                <fieldset id="fieldset-general">
                    <legend>General</legend>
                    <div class="pure-g">

                        {{--
                        <div class="pure-u-1 pure-u-md-1-2 pure-div">
                            {!! Form::label('Person', 'Person..') !!}
                            <div class="select  pure-u-23-24">
                                {!! Form::select('Person', $persons, null, ['id' => 'type', 'placeholder' => '-- select person --']) !!}
                            </div>
                        </div> --}}

                        <div class="pure-u-1 pure-u-md-1-2 pure-div">
                            {!! Form::label('Type', 'Type..') !!}
                            <div class="select  pure-u-23-24">
                                {!! Form::select('Type', Lang::get('doctypes'), null, ['id' => 'type', 'placeholder' => '-- select type --', 'v-model' =>
                                'dataset.type', "v-validate" => "'required'", 'data-vv-scope' => 'step-2']) !!}
                            </div>
                        </div>

                        <div class="pure-u-1 pure-u-md-1-2 pure-div">
                            {!! Form::label('State', 'State..') !!}
                            {{-- {{ Form::text('State', null, ['class' =>  'pure-u-23-24', 'placeholder' => trans('validation.attributes.backend.pages.title'),
                            'v-model' => 'dataset.state', "v-validate" => "'required'", 'data-vv-scope' => 'step-2', 'readonly' => 'true']) }}  --}}
                            <div class="select  pure-u-23-24">
                                {!! Form::select( 'State', ['unpublished' => 'unpublished', 'inprogress' => 'inprogress'], null, ['id' => 'state',
                                'placeholder' => '-- select server state --', 'v-model' => 'dataset.state', "v-validate" => "'required'", 'data-vv-scope' => 'step-2'] ) !!}
                            </div> 
                            {{-- <div class="select  pure-u-23-24">
                                {!! Form::select( 'State', array_except(Config::get('enums.server_states'),['published', 'deleted', 'temporary']), '',
                                ['placeholder' => '-- select server state --', 'v-model' => 'dataset.state', "v-validate" => "'required'", 'data-vv-scope' => 'step-2'] ) !!}
                            </div> --}}
                        </div>

                        <div class="pure-u-1 pure-u-md-1-2 pure-div">
                            {!! Form::label('CreatingCorporation', 'Creating Corporation') !!} 
                            {!! Form::text('CreatingCorporation', null, ['class' =>
                            'pure-u-23-24', 'v-model' => 'dataset.creating_corporation', "v-validate" => "'required'", 'data-vv-scope' => 'step-2']) !!}
                        </div>

                        <div class="pure-u-1 pure-u-md-1-2 pure-div">
                            {!! Form::label('project_id', 'Project..') !!}
                            <div class="select pure-u-23-24">
                                {!! Form::select('project_id', $projects, null, ['id' => 'project_id', 'placeholder' => '--no project--', 'v-model' => 'dataset.project_id', 'data-vv-scope' => 'step-2'])
                                !!}
                            </div>
                            <small id="projectHelp" class="pure-form-message-inline">project is optional</small>
                        </div>

                        <div class="pure-u-1 pure-u-md-1-2 pure-div">
                            {!! Form::label('EmbargoDate', 'Embargo Date') !!} 
                            {!! Form::date('EmbargoDate', null, ['placeholder' => date('y-m-d'), 'class'
                            => 'pure-u-23-24', 'v-model' => 'dataset.embargo_date', 'data-vv-scope' => 'step-2']) !!}
                            <small id="projectHelp" class="pure-form-message-inline">EmbargoDate is optional</small>
                        </div>

                        {{-- <div class="pure-u-1 pure-u-md-1 checkboxlist">
                            <!-- checkboxes -->
                            <label for="BelongsToBibliography" class="pure-checkbox">                            
                            <input name="BelongsToBibliography" v-model="dataset.belongs_to_bibliography" data-vv-scope="step-2" true-value="1"
                            false-value="0" type="checkbox" class="form-check-input"> 
                            Belongs To Bibliography?
                        </label>
                        </div> --}}

                    </div>
                </fieldset>

                <fieldset id="fieldset-titles">
                    <legend>Bounding Box</legend>
                    <div class="pure-g">
                        <div class="pure-u-1 pure-u-md-1 pure-u-lg-1 pure-div">
                            <locations-map v-bind:geolocation="dataset.geolocation"></locations-map>
                        </div>
                        <div class="pure-u-1 pure-u-md-1-2 pure-div">
                            {!! Form::label('xmin', 'xmin: ') !!} 
                            {!! Form::text('xmin', null, ['class' => 'pure-u-23-24', 'v-model' => 'dataset.geolocation.xmin', 'readonly']) !!}
                        </div>
                        <div class="pure-u-1 pure-u-md-1-2 pure-div">
                            {!! Form::label('ymin', 'ymin: ') !!} 
                            {!! Form::text('ymin', null, ['class' => 'pure-u-23-24', 'v-model' => 'dataset.geolocation.ymin', 'readonly']) !!}
                        </div>

                        <div class="pure-u-1 pure-u-md-1-2 pure-div">
                            {!! Form::label('xmax', 'xmax: ') !!} 
                            {!! Form::text('xmax', null, ['class' => 'pure-u-23-24', 'v-model' => 'dataset.geolocation.xmax', 'readonly']) !!}
                        </div>
                        <div class="pure-u-1 pure-u-md-1-2 pure-div">
                            {!! Form::label('ymax', 'ymax: ') !!} 
                            {!! Form::text('ymax', null, ['class' => 'pure-u-23-24', 'v-model' => 'dataset.geolocation.ymax', 'readonly']) !!}
                        </div>
                    </div>
                </fieldset>

                <fieldset id="fieldset-titles">
                    <legend>Main Title & Abstract</legend>
                    <div class="pure-g">

                        <div class="pure-u-1 pure-u-md-1-2 pure-div">
                            {!! Form::label('TitleMain', 'Main Title ') !!} 
                            {!! Form::text('TitleMain[Value]', null, ['class' => 'pure-u-23-24', 'v-model'
                            => 'dataset.title_main.value', "v-validate" => "'required|min:3'", "data-vv-as" => "Main Title", 'data-vv-scope' => 'step-2']) !!}
                        </div>
                        <div class="pure-u-1 pure-u-md-1-2 pure-div">
                            {!! Form::label('TitleLanguage', 'Title Language..') !!}
                            <div class="select pure-u-23-24">
                                {!! Form::select('TitleMain[Language]', $languages, null, ['placeholder' => '--no language--', 'v-model' => 'dataset.title_main.language',
                                "v-validate" => "'required'", "data-vv-as" => "Title Language", 'data-vv-scope' => 'step-2']) !!}
                            </div>
                        </div>

                        <div class="pure-u-1 pure-u-md-1-2 pure-div">
                            {!! Form::label('TitleAbstract', 'Main Abstract ') !!} 
                            {{ Form::textarea('TitleAbstract[Value]', null, ['class' => 'pure-u-23-24',
                            'size' => '70x6', 'v-model' => 'dataset.abstract_main.value', "v-validate" => "'required|min:3'",
                            "data-vv-as" => "Main Abstract", 'data-vv-scope' => 'step-2']) }}
                        </div>
                        <div class="pure-u-1 pure-u-md-1-2 pure-div">
                            {!! Form::label('AbstractLanguage', 'Abstract Language..') !!}
                            <div class="select pure-u-23-24">
                                {!! Form::select('TitleAbstract[Language]', $languages, null, ['placeholder' => '--no language--', 'v-model' => 'dataset.abstract_main.language',
                                "v-validate" => "'required'", "data-vv-as" => "Abstract Language", 'data-vv-scope' => 'step-2']) !!}
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset id="fieldset-licenses">
                    <legend>Licenses</legend>

                   <div class="pure-control-group checkboxlist">
                        @foreach ($licenses as $indexKey => $license)
                        <label for={{ "license". $license->id }} class="pure-checkbox">
                            @if ($loop->first)
                            <input name="licenses" value={{ $license->id }} v-model="dataset.checkedLicenses" type="radio" class="form-check-input" v-validate="'required'" 
                            data-vv-as="Licence" data-vv-scope="step-2">
                            {{ $license->name_long }}
                            @else
                            <input name="licenses" value={{ $license->id }} v-model="dataset.checkedLicenses" type="radio" class="form-check-input" data-vv-scope="step-2">
                            {{ $license->name_long }}
                            @endif
                        </label> 
                        @endforeach
                        <br>
                        <span>Checked license: @{{ dataset.checkedLicenses }}</span>
                    </div>
                </fieldset>
                <fieldset id="fieldset-references">
                    <legend>Document references</legend>
                    <button class="pure-button button-small" @click.prevent="addReference()">Add Reference</button>
                    <table class="table table-hover"  v-if="dataset.references.length">
                        <thead>
                            <tr>
                                <th style="width: 20px;">Value of the identifier</th>
                                <th>Type</th>
                                <th>Relation</th>
                                <th>Display text of the identifier</th>
                                <th style="width: 130px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in dataset.references">
                                <td>
                                    <input name="Reference Value" class="form-control" v-model="item.value" v-validate="'required'" data-vv-scope="step-2" />
                                </td>
                                <td>                                   
                                    {!! Form::select('Reference[Type]', $types, null, 
                                    ['placeholder' => '--no type--', 'v-model' => 'item.type', "v-validate" => "'required'", 'data-vv-scope' => 'step-2']) !!}
                                </td>
                                <td>                                   
                                    {!! Form::select('Reference[Relation]', $relations, null, 
                                    ['placeholder' => '--no relation--', 'v-model' => 'item.relation', 'data-vv-scope' => 'step-2']) !!}
                                </td>
                                <td>
                                    <input name="Reference Label" class="form-control" v-model="item.label"  v-validate="'required'" data-vv-scope="step-2" />
                                </td>
                                <td>
                                    <button class="pure-button button-small is-warning" @click.prevent="removeReference(index)">Remove</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <br />
                <div class="pure-controls">
                    <button @click.prevent="prev()" class="pure-button button-small">
                        <i class="fa fa-arrow-left"></i>
                        <span>Back</span>
                </button>

                <button @click.prevent="next('step-2')" class="pure-button button-small" v-bind:disabled="errors.any()">               
                    <i class="fa fa-arrow-right"></i>
                    <span>Continue</span>
                </button>
                </div>
                <div v-if="errors.items.length > 0">
                    <b>Please correct the following error(s):</b>
                    <ul class="alert validation-summary-errors">
                        <li style="margin-left:5px;" v-for="error in errors.items">@{{ error.msg }}</li>
                    </ul>
                </div>
            </div>

            <div v-if="step === 3 && isInitial" data-vv-scope="step-3">
                <h1>Select authors, contributors, submitters</h1>
                
                <fieldset id="fieldset-general">
                    <legend>Authors</legend>
                    <div class="pure-g">
                        <div class="pure-u-1 pure-u-md-1-2 pure-div">
                            <my-autocomplete title="searching active person table" @person="onAddAuthor"></my-autocomplete>
                            {{--
                            <my-autocomplete :items="[ 'Apple', 'Banana', 'Orange', 'Mango', 'Pear', 'Peach', 'Grape', 'Tangerine', 'Pineapple']"></my-autocomplete> --}}
                        </div>
                        <div class="pure-u-1 pure-u-md-1-2 pure-div">
                            <div class="pure-control-group checkboxlist">
                                <label v-for="(person, index) in dataset.persons" :for="person.id" class="pure-checkbox">                           
                                                        <input type="checkbox" name="persons" v-bind:value="person.id"  v-model="dataset.checkedAuthors"  class="form-check-input" data-vv-scope="step-3">
                                                        @{{ person.full_name }}                               
                                                    </label>
                                <br />
                                <span>Checked Authors: @{{ dataset.checkedAuthors }}</span>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset id="fieldset-general">
                    <legend>Contributors</legend>
                    <div class="pure-g">
                        <div class="pure-u-1 pure-u-md-1-2 pure-div">
                            <my-autocomplete title="searching active person table" @person="onAddContributor"></my-autocomplete>
                            {{--
                            <my-autocomplete :items="[ 'Apple', 'Banana', 'Orange', 'Mango', 'Pear', 'Peach', 'Grape', 'Tangerine', 'Pineapple']"></my-autocomplete> --}}
                        </div>
                        <div class="pure-u-1 pure-u-md-1-2 pure-div">
                            <div class="pure-control-group checkboxlist">
                                <label v-for="(contributor, index) in dataset.contributors" :for="contributor.id" class="pure-checkbox">                           
                                                        <input type="checkbox" name="contributors" v-bind:value="contributor.id"  v-model="dataset.checkedContributors"  class="form-check-input" data-vv-scope="step-3">
                                                        @{{ contributor.full_name }}                               
                                                    </label>
                                <br />
                                <span>Checked Contributors: @{{ dataset.checkedContributors }}</span>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset id="fieldset-general">
                    <legend>Submitters</legend>
                    <div class="pure-g">
                        <div class="pure-u-1 pure-u-md-1-2 pure-div">
                            <my-autocomplete title="searching active person table" @person="onAddSubmitter"></my-autocomplete>
                        </div>
                        <div class="pure-u-1 pure-u-md-1-2 pure-div">
                            <div class="pure-control-group checkboxlist">
                                <label v-for="(submitter, index) in dataset.submitters" :for="submitter.id" class="pure-checkbox">                           
                                                        <input type="checkbox" name="submitters" v-bind:value="submitter.id"  v-model="dataset.checkedSubmitters"  class="form-check-input" data-vv-scope="step-3">
                                                        @{{ submitter.full_name }}                               
                                                    </label>
                                <br />
                                <span>Checked Submitters: @{{ dataset.checkedSubmitters }}</span>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <br />
                <div class="pure-controls">
                    <button @click.prevent="prev()" class="pure-button button-small">
                        <i class="fa fa-arrow-left"></i>
                        <span>Back</span>
                </button>

                <button @click.prevent="next('step-3')" class="pure-button button-small">               
                    <i class="fa fa-arrow-right"></i>
                    <span>Review Dataset</span>
                </button>
                </div>
            </div>

            <div v-if="step === 4 && (isInitial || isSaving)" data-vv-scope="step-4">
                <h1>File Upload</h1>

                <div class="dropbox">
                    <input type="file" multiple name="files" v-bind:disabled="isSaving" @change="filesChange($event.target.name, $event.target.files)"
                        class="input-file" data-vv-scope="step-4">
                    <p v-if="isInitial">
                        Drag your file(s) here to begin<br> or click to browse
                    </p>
                    <p v-if="isSaving">
                        Uploading @{{ fileCount }} files...
                    </p>
                </div>
                {{-- <button @click.prevent="resetDropbox()" v-bind:disabled="isInitial" class="pure-button is-warning">Reset Dropbox</button>                --}} {{--
                <ul class="list-unstyled">
                    <li v-for="(item, index) in dataset.files">
                        @{{ item.name }} <i class="fa fa-remove"></i><span class="remove-file" v-on:click="removeFile(index)"> Remove</span>
                    </li>
                </ul> --}}
                <table class="table table-hover"  v-if="dataset.files.length">
                    <thead>
                        <tr>
                            <th style="width: 20px;">Sorting</th>
                            <th>File</th>
                            <th>Label</th>
                            <th style="width: 130px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in dataset.files">
                            <td>
                                @{{ index +1 }}
                            </td>
                            <td>
                                <input class="form-control" v-model="item.file.name" />
                            </td>
                            <td>
                                <input class="form-control" v-model="item.label" />
                            </td>
                            <td>
                                <button class="pure-button button-small is-warning" @click.prevent="removeFile(index)">Remove</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button @click.prevent="prev()" class="pure-button button-small">
                        <i class="fa fa-arrow-left"></i>
                        <span>Zurück</span>
                </button>
                <button @click.prevent="submit()" class="pure-button button-small">               
                    <i class="fa fa-save"></i>
                    <span>Create Dataset</span>
                </button>

            </div>

            <!--SUCCESS-->
            <div v-if="isSuccess">
                <h2>Uploaded @{{ dataset.files.length }} file(s) successfully.</h2>
                <p>
                <a href="javascript:void(0)" @click="reset()" class="pure-button button-small">Upload new Dataset</a>
                </p>
                <p>
                <a href="javascript:void(0)" @click="editNewDataset()" class="pure-button button-small">@{{ redirectLink }}</a>
                </p>
                <ul class="list-unstyled">
                {{-- <li v-for="item in uploadedFiles">
                    <img :src="item.url" class="img-responsive img-thumbnail" :alt="item.originalName">
                </li> --}}
                </ul>
            </div>

            <!--ERROR-->
            <div v-if="isFailed">
                <h2>Uploaded failed.</h2>
                <p>
                    <a href="javascript:void(0)" @click="retry()">Retry: Edit inputs</a>
                </p>
                <p>
                    <a href="javascript:void(0)" @click="reset()">Start again</a>
                </p>              
                <div v-if="serrors.length > 0">
                    <b>Please correct the following server error(s):</b>
                    <ul class="alert validation-summary-errors">
                        <li style="margin-left:5px;" v-for="error in serrors">@{{ error }}</li>
                    </ul>
                </div>
            </div>


        </main>
        {{-- <br/><br/>Debug:@{{ dataset }} --}}
</div>


@stop 
@section('styles')
<style type="text/css">
    /* main.steps article {
        display:block;
    } */

    .help-block {
        display: none;
        font-size: 0.8em;
    }

    .has-error .help-block {
        display: block;
    }
</style>


@stop 
@section('after-scripts') {{--
<script type="text/javascript" src="{{ asset('js/lib.js') }}"></script> --}} {{--
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue"></script>--}} {{--
<script type="text/javascript" src="{{ resource_path('assets\js\datasetPublish.js') }}"></script> --}}
<script type="text/javascript" src="{{  asset('backend/publish/datasetPublish.js') }}"></script>



@stop