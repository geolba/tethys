@extends('settings.layouts.app') 
@section('content')
<div class="header">
    <h3 class="header-title">
        <i class="fa fa-share"></i> Delete saved datasets
    </h3>
</div>

<div class="header">
    <h3 class="header-title">
        Delete the created dataset again
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
            {!! Form::model($dataset, [ 'method' => 'POST', 'route' => ['publish.workflow.submit.deleteUpdate', $dataset->id], 'id' => 'releaseForm',
            'class' => 'pure-form', 'enctype' => 'multipart/form-data', 'v-on:submit.prevent' => 'checkForm']) !!}
            <fieldset id="fieldset-General">
                <legend>General</legend>
                <div class="pure-g">

                   

                    <div class="pure-u-1 pure-u-md-1-1">                                   
                       <label>Do you really want to delete the dataset?</label>                                                                
                    </div>  


                </div>
            </fieldset>

            <br />
            <div class="pure-controls">
                <button :disabled="errors.any()" type="submit" class="pure-button">
                    <i class="fa fa-trash"></i>
                    <span>Delete</span>
                </button>
            </div>

            {!! Form::close() !!}
        </div>
    </div>

</div>

@stop 
