@extends('settings.layouts.app') 
@section('content')
<div class="header">
    <h3 class="header-title">
        <i class="fa fa-share"></i> Review approved dataset 
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
                @php                
                // if ($dataset->editor->id == Auth::user()->id) {
                //     $userIsDesiredEditor = true;
                // } else {
                //     $userIsDesiredEditor = false;
                //     $message = 'you are not the desired editor, but you can still accept the dataset';  
                // }
                $message = 'If you are not the desired editor, you can still accept the dataset!!';            
                @endphp
               
            {!! Form::model($dataset, [ 'method' => 'POST', 'route' => ['publish.workflow.review.reviewUpdate', $dataset->id], 'id' => 'reviewForm',
            'class' => 'pure-form', 'enctype' => 'multipart/form-data', 'v-on:submit.prevent' => 'checkForm']) !!}
            <fieldset id="fieldset-General">
                <legend>Values</legend>
                <div class="pure-g">

                    {{-- <div class="pure-u-1 pure-u-md-1-1 pure-div">
                        {!! Form::label('title', 'dataset title:') !!}
                        @if ($dataset->titles()->first()) 
                            {{ $dataset->titles()->first()->value }}                   
                        @endif
                    </div>
                    <div class="pure-u-1 pure-u-md-1-1 pure-div">
                        {!! Form::label('editor', 'editor:') !!}
                        {!! $dataset->editor->login !!}
                    </div>
                    <div class="pure-u-1 pure-u-md-1-1 pure-div">
                            {!! Form::label('owner', 'dataset owner:') !!}
                            {!! $dataset->user->login !!}    
                    </div> --}}
                    @foreach($fields as $field => $fieldValue)
                    <div class="pure-u-1 pure-u-md-1-1 pure-div">
                        {{ Form::label($field, $field . ": ") }}
                        <span class="help is-info"> {!! $fieldValue !!} </span>                       
                    </div>   
                    @endforeach 
                   
                </div>
            </fieldset>

            <br />
            <div class="pure-controls">
                <button type="submit" class="pure-button">
                    <i class="fa fa-share"></i>
                    <span>Set reviewed</span>
                </button>
            </div>

            {!! Form::close() !!}
        </div>
    </div>

</div>

@stop 

@section('after-scripts') 
@stop