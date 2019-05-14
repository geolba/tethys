@extends('settings.layouts.app') 
@section('content')
<div class="header">
    <h3 class="header-title">
        <i class="fa fa-share"></i> Release saved datasets
    </h3>
</div>

<div class="header">
    <h3 class="header-title">
        Release your dataset for Editor
    </h3>
</div>

<div class="pure-g  box-content">

    <div class="pure-u-1 pure-u-md-1">
        <div>
            <a href="{{ route('publish.workflow.submit.index') }}" class="pure-button button-small">
                <i class="fa fa-chevron-left"></i>
                <span>BACK</span>
            </a>
        </div>
        <div id="app1">
            {!! Form::model($dataset, [ 'method' => 'POST', 'route' => ['publish.workflow.submit.releaseUpdate', $dataset->id], 'id' => 'releaseForm',
            'class' => 'pure-form', 'enctype' => 'multipart/form-data', 'v-on:submit.prevent' => 'checkForm']) !!}
            <fieldset id="fieldset-General">
                <legend>General</legend>
                <div class="pure-g">

                    <div class="pure-u-1 pure-u-md-1-1 pure-div">
                        {!! Form::label('preferred_reviewer', 'name of preferred reviewer:') !!}
                       
                            {{-- {!! Form::select('editor_id', $editors, null, ['id' => 'editor_id', 'placeholder' => '-- select editor --', 'v-model' =>
                            'dataset.editor_id', "v-validate" => "'required'"]) !!} --}}
                        {!! Form::text('preferred_reviewer', null, ['id' => 'preferred_reviewer', 'class'=>'pure-u-23-24',
                        'placeholder' => '-- enter name of preferred reviewer --',
                        'v-model' => 'dataset.preferred_reviewer', "v-validate" => "'required|min:3|max:20'"]) !!}
                         <em>*</em>
                       
                        <span class="help is-danger" v-if="errors.has('preferred_reviewer')" v-text="errors.first('preferred_reviewer')"></span>

                    </div>
                    <div class="pure-u-1 pure-u-md-1-1 pure-div">
                        {!! Form::label('preferred_reviewer_email', 'email of preferred reviewer:') !!}
                        {!! Form::text('preferred_reviewer_email', null, array(
                            'placeholder' => 'Email', 'id' => 'preferred_reviewer_email', 'class' => 'pure-u-23-24',
                            'v-model' => 'dataset.preferred_reviewer_email', "v-validate" => "'required|email'"
                            )) !!}
                        <em>*</em>

                        <span class="help is-danger" v-if="errors.has('preferred_reviewer_email')" v-text="errors.first('preferred_reviewer_email')"></span>
                    </div>
                </div>
            </fieldset>

            <br />
            <div class="pure-controls">
                <button :disabled="errors.any()" type="submit" class="pure-button">
                    <i class="fa fa-share"></i>
                    <span>Release</span>
                </button>
            </div>

            {!! Form::close() !!}
        </div>
    </div>

</div>

@stop 

@section('after-scripts') {{--
<script type="text/javascript" src="{{ asset('js/lib.js') }}"></script> --}} {{--
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue"></script>--}} {{--
<script type="text/javascript" src="{{ resource_path('assets\js\datasetPublish.js') }}"></script> --}}
<script type="text/javascript" src="{{  asset('backend/publish/releaseDataset.js') }}"></script>

@stop