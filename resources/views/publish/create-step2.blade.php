@extends('layouts.settings.layout')

@section('title', 'Publish')

@section('content')
<div class="header">
    <h3 class="header-title">
        <i class="fa fa-upload"></i> Publish New Dataset - Step 2
    </h3>
</div>
    
<div class="box-content">
{{-- <div method="post" enctype="multipart/from-data" class="pure-form"> --}}
{!! Form::model($dataset, ['method' => 'post', 'files' => true , 'route' => ['dataset.store2'], 'class' => 'pure-form']) !!}
  
    <fieldset id="fieldset-general">
        <legend>General</legend>
        <div class="pure-g">

            <div class="pure-u-1 pure-u-md-1-2 pure-div">
                {!! Form::label('Type', 'Type..') !!}
                <div class="select  pure-u-23-24">
                {!! Form::select('Type', Lang::get('doctypes'), null, ['id' => 'type', 'placeholder' => '-- select type --']) !!}
                </div>
            </div>

            <div class="pure-u-1 pure-u-md-1-2 pure-div">
                {!! Form::label('CreatingCorporation', 'Creating Corporation') !!}
                {!! Form::text('CreatingCorporation', null, ['class' => 'pure-u-23-24']) !!}
            </div>

            <div class="pure-u-1 pure-u-md-1-2 pure-div">
                {!! Form::label('EmbargoDate', 'Embargo Date') !!}
                {!! Form::date('EmbargoDate', null, ['placeholder' => date('y-m-d'), 'class' => 'pure-u-23-24']) !!}
                <small id="projectHelp" class="pure-form-message-inline">EmbargoDate is optional</small>
            </div>

            <div class="pure-u-1 pure-u-md-1 checkboxlist">
                <!-- checkboxes -->
                <label for="BelongsToBibliography" class="pure-checkbox">
                    <input type="hidden" name="BelongsToBibliography" value="0">
                    <input name="BelongsToBibliography" value="1" type="checkbox" class="form-check-input"> 
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
                {!! Form::text('TitleMain[Value]', null, ['class' => 'pure-u-23-24']) !!}
            </div>
            <div class="pure-u-1 pure-u-md-1-2 pure-div">
                {!! Form::label('language', 'Title Language..') !!}
                <div class="select pure-u-23-24">
                {!! Form::select('TitleMain[Language]', $languages, null, ['placeholder' => '--no language--']) !!}
                </div>
            </div>

            <div class="pure-u-1 pure-u-md-1-2 pure-div">
                {!! Form::label('TitleAbstract', 'Main Abstract ') !!} 
                {{ Form::textarea('TitleAbstract[Value]', null, ['class' => 'pure-u-23-24', 'size' => '70x6']) }}
            </div>
            <div class="pure-u-1 pure-u-md-1-2 pure-div">
                {!! Form::label('language', 'Abstract Language..') !!}
                <div class="select pure-u-23-24">
                {!! Form::select('TitleAbstract[Language]', $languages, null, ['placeholder' => '--no language--']) !!}
                </div>
            </div>
        </div>   
    </fieldset>

    <fieldset id="fieldset-files">
        <legend>Files</legend>
        <div class="pure-g">
            <div class="fpure-u-1 pure-u-md-1-2 pure-div">
                {!! Form::label('dataset_file', 'Dataset File') !!}
                <input type="file" {{ (!empty($dataset['DatasetFile'])) ? "disabled" : "" }} class="pure-u-23-24" name="dataset_file">
                <small id="fileHelp" class="pure-form-message-inline">Please upload a valid image file. Size of image should not be more than 2MB.</small>
            </div>
        </div>
    </fieldset>
   
    <br />
    <div class="pure-controls">
        <button type="submit" class="pure-button button-small">
            <i class="fa fa-arrow-right"></i>
            <span>Review Dataset Details</span>
        </button>
    </div>
    @include('errors._errors')
{!! Form::close() !!}


 @if(isset($dataset['DatasetFile']))
    <form action="" method="post">
        {{ csrf_field() }}
    <button type="submit" class="btn btn-danger">Remove Image</button>
    </form>
 @endif
 <div class="box-content">

@stop