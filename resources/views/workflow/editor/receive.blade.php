@extends('settings.layouts.app') 
@section('content')
<div class="header">
    <h3 class="header-title">
        <i class="fa fa-share"></i> Receive released dataset 
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
                @php                
                // if ($dataset->editor->id == Auth::user()->id) {
                //     $userIsDesiredEditor = true;
                // } else {
                //     $userIsDesiredEditor = false;
                //     $message = 'you are not the desired editor, but you can still accept the dataset';  
                // }
                $message = 'If you are not the desired editor, you can still accept the dataset!!';            
                @endphp
               
            {!! Form::model($dataset, [ 'method' => 'POST', 'route' => ['publish.workflow.editor.receiveUpdate', $dataset->id], 'id' => 'acceptForm',
            'class' => 'pure-form', 'enctype' => 'multipart/form-data', 'v-on:submit.prevent' => 'checkForm']) !!}
            <fieldset id="fieldset-General">
                <legend>General</legend>
                <div class="pure-g">

                    <div class="pure-u-1 pure-u-md-1-1 pure-div">
                        {!! Form::label('editor_id', 'preferred editor:') !!}
                        {!! $dataset->preferred_editor !!}
                       
                        <span class="help is-danger"> {!! $message !!}</span>
                      
                        {{-- <span class="help is-danger" v-if="errors.has('editor_id')" v-text="errors.first('editor_id')"></span> --}}

                    </div>
                    <div class="pure-u-1 pure-u-md-1-1 pure-div">
                            {!! Form::label('owner', 'dataset owner:') !!}
                            {!! $dataset->user->login !!}
                            {{-- <span class="help is-danger" v-if="errors.has('editor_id')" v-text="errors.first('editor_id')"></span> --}}
    
                    </div>
                    <div class="pure-u-1 pure-u-md-1-1 pure-div">
                            {!! Form::label('title', 'dataset title:') !!}
                        @if ($dataset->titles()->first()) 
                            {{ $dataset->titles()->first()->value }}                   
                        @endif
                    </div>
                </div>
            </fieldset>

            <br />
            <div class="pure-controls">
                <button type="submit" class="pure-button">
                    <i class="fa fa-share"></i>
                    <span>Set Received</span>
                </button>
            </div>

            {!! Form::close() !!}
        </div>
    </div>

</div>

@stop 

@section('after-scripts') 
@stop