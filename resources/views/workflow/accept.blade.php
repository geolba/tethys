@extends('settings.layouts.app') 
@section('content')
<div class="header">
    <h3 class="header-title">
        <i class="fa fa-share"></i> Accept released dataset 
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
            <a href="{{ route('publish.workflow.editorIndex') }}" class="pure-button button-small">
                <i class="fa fa-chevron-left"></i>
                <span>BACK</span>
            </a>
        </div>
        <div id="app1">
                @php 
                //if userid changed from last iteration, store new userid and change color                
                // $lastid = $detail->payment->userid;
                if ($dataset->editor->id == Auth::user()->id) {
                    $userIsDesiredEditor = true;
                } else {
                    $userIsDesiredEditor = false;
                    $message = 'you are not the desired editor, but you can still accept the dataset';  
                }            
                @endphp
               
            {!! Form::model($dataset, [ 'method' => 'POST', 'id' => 'acceptForm',
            'class' => 'pure-form', 'enctype' => 'multipart/form-data', 'v-on:submit.prevent' => 'checkForm']) !!}
            <fieldset id="fieldset-General">
                <legend>General</legend>
                <div class="pure-g">

                    <div class="pure-u-1 pure-u-md-1-1 pure-div">
                        {!! Form::label('editor_id', 'preferred editor:') !!}
                        {!! $dataset->editor->login !!}
                        @if($userIsDesiredEditor == false)
                            <span class="help is-danger"> {!! $message !!}</span>
                        @endif
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
                <button :disabled="errors.any()" type="submit" class="pure-button">
                    <i class="fa fa-share"></i>
                    <span>Accept</span>
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