@extends('settings.layouts.app') 
@section('content')
<div class="header">
    <h3 class="header-title">
        <i class="fa fa-share"></i> Reject submitted dataset 
    </h3>
</div>

@if (count($errors) > 0)
  <div class="alert alert-danger">
    <strong>Whoops!</strong> There were some problems with your input.<br><br>
    <ul>
       @foreach ($errors->all() as $error)
         <li>{{ $error }}</li>
       @endforeach
    </ul>
  </div>
@endif

<div class="pure-g  box-content">

    <div class="pure-u-1 pure-u-md-1">
        <div>
            <a href="{{ route('publish.workflow.editor.index') }}" class="pure-button button-small">
                <i class="fa fa-chevron-left"></i>
                <span>BACK</span>
            </a>
        </div>
        <div id="app1">            
               
            {!! Form::model($dataset, [ 'method' => 'POST', 'route' => ['publish.workflow.editor.rejectUpdate', $dataset->id], 'id' => 'rejectForm',
            'class' => 'pure-form', 'enctype' => 'multipart/form-data', 'v-on:submit.prevent' => 'checkForm']) !!}
            <fieldset id="fieldset-General">
                <legend>General</legend>
                <div class="pure-g">

                        <div class="pure-u-1 pure-u-md-1-1 pure-div">
                            {!! Form::label('server_state', 'Status..') !!}
                            {!! Form::text('server_state', 'rejected_editor', ['class'=>'pure-u-23-24','readonly']) !!}                               
                        </div>
                                    
                    <div class="pure-u-1 pure-u-md-1-1 pure-div">
                            {!! Form::label('reject_editor_note', 'reject note:') !!}
                            {!! Form::textarea('reject_editor_note',null, ['id' => 'reject_editor_note', 'class'=>'pure-u-23-24',
                            'placeholder' => '-- reject note for submitter --', 'size' => '70x6',
                            'v-model' => 'dataset.reject_editor_note', "v-validate" => "'required|min:10|max:500'"]) !!}
                             <em>*</em>                           
                            <span class="help is-danger" v-if="errors.has('reject_editor_note')" v-text="errors.first('reject_editor_note')"></span>    
                    </div>      
                    
                    @if ($dataset->reject_reviewer_note != null)
                    <div class="pure-u-1 pure-u-md-1-1">
                        {!! Form::label('reject_reviewer_note', 'Reviewer reject note..') !!}
                        {!! Form::text('reject_reviewer_note', null, ['class'=>'pure-u-23-24','readonly']) !!}                               
                    </div>
                    <div class="pure-u-1 pure-u-md-1-1">
                        <label for="BelongsToBibliography" class="pure-checkbox">
                            <input type="hidden" name="reviewer_note_visible" value="0">
                            <input name="reviewer_note_visible" value="1" type="checkbox" class="form-check-input"> 
                            should reviewer note be visible to submitter?
                        </label> 
                    </div>
                    @else
                    <div class="pure-u-1 pure-u-md-1-1">
                        <span class="help is-info">There is no reject note from the reviewer</span>
                    </div>                  
                    @endif
                </div>
            </fieldset>

            <br />
            <div class="pure-controls">
                <button type="submit" class="pure-button">
                    <i class="fas fa-undo"></i>
                    <span>Reject to submitter</span>
                </button>
                {{-- <span class="help is-danger">..to do: write code for setting state 'rejected_reviewer' in database</span> --}}
            </div>

            {!! Form::close() !!}
        </div>
    </div>

</div>

@stop 

@section('after-scripts') 
@stop