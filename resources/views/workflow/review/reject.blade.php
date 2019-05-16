@extends('settings.layouts.app') 
@section('content')
<div class="header">
    <h3 class="header-title">
        <i class="fa fa-share"></i> Reject approved dataset 
    </h3>
</div>

<div class="pure-g  box-content">

    <div class="pure-u-1 pure-u-md-1">
        <div>
            <a href="{{ route('publish.workflow.review.index') }}" class="pure-button button-small">
                <i class="fa fa-chevron-left"></i>
                <span>BACK</span>
            </a>
        </div>
        <div id="app1">            
               
            {!! Form::model($dataset, [ 'method' => 'POST', 'route' => ['publish.workflow.review.rejectUpdate', $dataset->id], 'id' => 'rejectForm',
            'class' => 'pure-form', 'enctype' => 'multipart/form-data', 'v-on:submit.prevent' => 'checkForm']) !!}
            <fieldset id="fieldset-General">
                <legend>General</legend>
                <div class="pure-g">
                                       
                    <div class="pure-u-1 pure-u-md-1-1 pure-div">
                            {!! Form::label('owner', 'reject note:') !!}
                            {!! Form::text('reject_reviewer_note', null, ['id' => 'reject_reviewer_note', 'class'=>'pure-u-23-24',
                            'placeholder' => '-- reject note for editor --',
                            'v-model' => 'dataset.reject_reviewer_note', "v-validate" => "'required|min:10|max:255'"]) !!}
                             <em>*</em>
                           
                            <span class="help is-danger" v-if="errors.has('preferred_reviewer')" v-text="errors.first('preferred_reviewer')"></span>
    
                    </div>                   
                </div>
            </fieldset>

            <br />
            <div class="pure-controls">
                {{-- <button type="submit" class="pure-button">
                    <i class="fas fa-undo"></i>
                    <span>Reject</span>
                </button> --}}
                <span class="help is-danger">..to do: write code for setting state 'rejected_reviewer' in database</span>
            </div>

            {!! Form::close() !!}
        </div>
    </div>

</div>

@stop 

@section('after-scripts') 
@stop