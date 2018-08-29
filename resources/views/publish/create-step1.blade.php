@extends('layouts.settings.layout')

@section('title', 'Publish')

@section('content')
<div class="header">
    <h3 class="header-title">
        <i class="fa fa-upload"></i> Publish New Dataset
    </h3>
</div>

<div id="app" class="box-content">
    <form action={{ route('dataset.store1') }} method="post" class="pure-form" enctype="multipart/form-data">   
        {{ csrf_field() }}

        <div v-if="step === 1">
            <h1>Step One</h1> 
            <fieldset class="left-labels">            
                <legend>Datensatztyp</legend>		
                <div class="description hint">
                <p>Bitte wählen Sie einen Datensatztyp aus der Liste aus.</p></div><p></p><div class="form-item">
                <label for="documentType">Datensatztyp<span class="required" title="Dieses Feld muss ausgefüllt werden."> *</span></label>
                <div class="select" style="width:300px" title="Bitte wählen Sie einen Datensatztyp aus der Liste aus.">
                    {!! Form::select('Type', Lang::get('doctypes'), null, ['id' => 'type', 'placeholder' => '-- select type --', 'v-model' => 'dataset.type']) !!}
                </div>
                </div>
            </fieldset>

            <fieldset class="left-labels">
                <legend>Einräumung eines einfachen Nutzungsrechts</legend>	      
                <div class="pure-u-1 pure-u-md-1-2 pure-div">
                    <small for="rights" class="pure-form-message-inline">Ich habe diese rechtlichen Hinweise gelesen und bin damit einverstanden.
                        <span class="required" title="Dieses Feld muss ausgefüllt werden.">*</span>
                    </small>
                    {{-- <input name="rights" value="0" type="hidden"> --}}
                    <input class="form-checkbox" name="rights" id="rights" type="checkbox" v-model="dataset.rights" true-value="1" false-value="0">
                </div>
            </fieldset>

            <br />
            <div class="pure-controls">
                {{-- <button type="submit" class="pure-button button-small">
                    <i class="fa fa-arrow-right"></i>
                    <span>Weiter zum nächsten Schritt</span>
                </button> --}}
                <button @click.prevent="next()" class="pure-button button-small">
                    <i class="fa fa-arrow-right"></i>
                    <span>Weiter zum nächsten Schritt</span>
                </button>
            </div>
        </div>

        <div v-if="step === 2">
            <h1>Step Two</h1>
            <fieldset id="fieldset-general">
                <legend>General</legend>
                <div class="pure-g">
        
                    {{-- <div class="pure-u-1 pure-u-md-1-2 pure-div">
                        {!! Form::label('Person', 'Person..') !!}
                        <div class="select  pure-u-23-24">
                        {!! Form::select('Person', $persons, null, ['id' => 'type', 'placeholder' => '-- select person --']) !!}
                        </div>
                    </div> --}}

                    <div class="pure-u-1 pure-u-md-1-2 pure-div">
                        {!! Form::label('Type', 'Type..') !!}
                        <div class="select  pure-u-23-24">
                        {!! Form::select('Type', Lang::get('doctypes'), null, ['id' => 'type', 'placeholder' => '-- select type --', 'v-model' => 'dataset.type']) !!}
                        </div>
                    </div>

                    <div class="pure-u-1 pure-u-md-1-2 pure-div">
                        {!! Form::label('State', 'State..') !!}
                        <div class="select  pure-u-23-24">
                        {!! Form::select(
                            'State', 
                            array_except(Config::get('enums.server_states'),['published', 'deleted', 'temporary']),
                            '',
                            ['placeholder' => '-- select server state --', 'v-model' => 'dataset.state']
                        ) !!}
                        </div>
                    </div>
        
                    <div class="pure-u-1 pure-u-md-1-2 pure-div">
                        {!! Form::label('CreatingCorporation', 'Creating Corporation') !!}
                        {!! Form::text('CreatingCorporation', null, ['class' => 'pure-u-23-24', 'v-model' => 'dataset.creating_corporation']) !!}
                    </div>
        
                    <div class="pure-u-1 pure-u-md-1-2 pure-div">
                        {!! Form::label('EmbargoDate', 'Embargo Date') !!}
                        {!! Form::date('EmbargoDate', null, ['placeholder' => date('y-m-d'), 'class' => 'pure-u-23-24', 'v-model' => 'dataset.embargo_date']) !!}
                        <small id="projectHelp" class="pure-form-message-inline">EmbargoDate is optional</small>
                    </div>
                            
                    <div class="pure-u-1 pure-u-md-1 checkboxlist">
                        <!-- checkboxes -->
                        <label for="BelongsToBibliography" class="pure-checkbox">
                            {{-- <input type="hidden" name="BelongsToBibliography" value="0"> --}}
                            <input name="BelongsToBibliography" v-model="dataset.belongs_to_bibliography" true-value="1"
                            false-value="0" type="checkbox" class="form-check-input"> 
                            Belongs To Bibliography?
                        </label>
                    </div>
                
                </div>
            </fieldset>
            <fieldset id="fieldset-titles">
                <legend>Main Title & Abstract</legend>
                <div class="pure-g">
        
                
                    <div class="pure-u-1 pure-u-md-1-2 pure-div">
                        {!! Form::label('TitleMain', 'Main Title ') !!}           
                        {!! Form::text('TitleMain[Value]', null, ['class' => 'pure-u-23-24', 'v-model' => 'dataset.title_main.value']) !!}
                    </div>
                    <div class="pure-u-1 pure-u-md-1-2 pure-div">
                        {!! Form::label('TitleLanguage', 'Title Language..') !!}
                        <div class="select pure-u-23-24">
                        {!! Form::select('TitleMain[Language]', $languages, null, ['placeholder' => '--no language--', 'v-model' => 'dataset.title_main.language']) !!}
                        </div>
                    </div>
        
                    <div class="pure-u-1 pure-u-md-1-2 pure-div">
                        {!! Form::label('TitleAbstract', 'Main Abstract ') !!} 
                        {{ Form::textarea('TitleAbstract[Value]', null, ['class' => 'pure-u-23-24', 'size' => '70x6', 'v-model' => 'dataset.abstract_main.value']) }}
                    </div>
                    <div class="pure-u-1 pure-u-md-1-2 pure-div">
                        {!! Form::label('AbstractLanguage', 'Abstract Language..') !!}
                        <div class="select pure-u-23-24">
                        {!! Form::select('TitleAbstract[Language]', $languages, null, ['placeholder' => '--no language--', 'v-model' => 'dataset.abstract_main.language']) !!}
                        </div>
                    </div>
                </div>   
            </fieldset>
            <fieldset id="fieldset-licenses">
                <legend>Licenses</legend>

                <div class="pure-control-group checkboxlist">
                    @foreach ($licenses as $license)            
                    <label for={{"license". $license->id }} class="pure-checkbox">
                        <input name="licenses[]" value={{ $license->id }} v-model="dataset.checkedLicenses" type="checkbox" class="form-check-input">
                        {{ $license->name_long }}
                    </label>    
                    @endforeach
                    <br>
                    <span>Checked licenses: @{{ dataset.checkedLicenses }}</span>
                </div>
            </fieldset>

            <br />
            <div class="pure-controls">
                <button @click.prevent="prev()" class="pure-button button-small">
                        <i class="fa fa-arrow-left"></i>
                        <span>Zurück</span>
                </button>
                
                <button @click.prevent="next()" class="pure-button button-small">
                    <i class="fa fa-arrow-right"></i>
                    <span>Review Dataset</span>
                </button>       
            </div> 

        </div>

        <div v-if="step === 3">
            <h1>File Upload</h1>
            
            <div class="dropbox">
                    <input type="file" multiple name="files" :disabled="isSaving" @change="filesChange($event.target.name, $event.target.files)" class="input-file">
                <p v-if="isInitial">
                    Drag your file(s) here to begin<br> or click to browse
                </p>
                <p v-if="isSaving">
                    Uploading @{{ fileCount }} files...
                </p>               
            </div>
            {{-- <button @click.prevent="resetDropbox()"  v-bind:disabled="isInitial" class="pure-button is-warning">Reset Dropbox</button>            --}}
            {{-- <ul class="list-unstyled">
                <li v-for="(item, index) in dataset.files">
                    @{{ item.name }} <i class="fa fa-remove"></i><span class="remove-file" v-on:click="removeFile(index)"> Remove</span>
                </li>
            </ul> --}}
            <table class="table table-hover">
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
                        <input class="form-control" v-model="item.file.name"/>
                    </td> 
                    <td>
                        <input class="form-control" v-model="item.label"/>
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

        <div v-if="errors.length > 0">
            <b>Please correct the following error(s):</b>
            <ul class="alert validation-summary-errors">
                <li style="margin-left:5px;" v-for="error in errors">@{{ error }}</li>
            </ul>
        </div>
                 

    </form>
    {{-- <br/><br/>Debug:@{{ dataset }} --}}
</div>

@stop

@section('scripts')
    {{-- <script type="text/javascript" src="{{ asset('js/lib.js') }}"></script> --}}

    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <!-- production version, optimized for size and speed -->
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    <script type="text/javascript" src="{{  asset('js/app.js') }}"></script>
    {{-- <script type="text/javascript" src="{{ resource_path('assets\js\app.js') }}"></script> --}}
@stop

