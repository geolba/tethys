@extends('settings.layouts.app') 
@section('content')
<div class="header">
    <h3 class="header-title">
        <i class="fa fa-share"></i> Approve corrected datasets
    </h3>
</div>

<div class="pure-g  box-content">

    <div class="pure-u-1 pure-u-md-1">
        <div>
            <a href="{{ route('publish.workflow.editor.index') }}" class="pure-button button-small">
                <i class="fa fa-chevron-left"></i>
                <span>BACK</span>
            </a>
        </div>
        <div id="app1">
            {!! Form::model($dataset, [ 'method' => 'POST', 'route' => ['publish.workflow.editor.approveUpdate', $dataset->id], 'id' => 'approveForm',
            'class' => 'pure-form', 'enctype' => 'multipart/form-data', 'v-on:submit.prevent' => 'checkForm']) !!}
            <fieldset id="fieldset-General">
                <legend>General</legend>
                <div class="pure-g">

                    <div class="pure-u-1 pure-u-md-1-2 pure-div">
                        {!! Form::label('reviewer_id', 'reviewer:') !!}
                        <div class="select  pure-u-23-24">
                            {!! Form::select('reviewer_id', $reviewers, null, ['id' => 'reviewer_id', 'placeholder' => '-- select reviewer --', 'v-model' =>
                            'dataset.reviewer_id', "v-validate" => "'required'"]) !!}
                        </div>
                        <span class="help is-danger" v-if="errors.has('reviewer_id')" v-text="errors.first('reviewer_id')"></span>

                    </div>
                </div>
            </fieldset>

            <br />
            <div class="pure-controls">
                <button :disabled="errors.any()" type="submit" class="pure-button">
                    <i class="fa fa-share"></i>
                    <span>Set Approved</span>
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
<script type="text/javascript" src="{{  asset('backend/publish/approveDataset.js') }}"></script>

@stop