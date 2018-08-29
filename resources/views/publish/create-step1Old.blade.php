@extends('layouts.settings.layout')

@section('title', 'Publish')

@section('content')
<div class="header">
    <h3 class="header-title">
        <i class="fa fa-upload"></i> Publish New Dataset - Step 1
    </h3>
</div>

<div id="app" class="box-content">
    {{-- <form action={{ route('dataset.store1') }} method="post" class="pure-form" enctype="multipart/form-data"> --}}
    {!! Form::model($dataset, ['method' => 'POST', 'route' => ['dataset.store1'], 'class' => 'pure-form']) !!}
        {{ csrf_field() }}
        
        <fieldset class="left-labels">            
            <legend>Datensatztyp</legend>		
            <div class="description hint">
            <p>Bitte wählen Sie einen Datensatztyp aus der Liste aus.</p></div><p></p><div class="form-item">
            <label for="documentType">Datensatztyp<span class="required" title="Dieses Feld muss ausgefüllt werden."> *</span></label>
            <div class="select" style="width:300px" title="Bitte wählen Sie einen Datensatztyp aus der Liste aus.">
                {!! Form::select('Type', Lang::get('doctypes'), null, ['id' => 'type', 'placeholder' => '-- select type --']) !!}				
            </div>
            </div>
        </fieldset>

        <fieldset class="left-labels">
            <legend>Einräumung eines einfachen Nutzungsrechts</legend>	      
            <div class="class="pure-u-1 pure-u-md-1-2 pure-div"">
                <small for="rights" class="pure-form-message-inline">Ich habe diese rechtlichen Hinweise gelesen und bin damit einverstanden.
                    <span class="required" title="Dieses Feld muss ausgefüllt werden.">*</span>
                </small>
                <input name="rights" value="0" type="hidden">
                <input class="form-checkbox" name="rights" id="rights" value="1" type="checkbox">
            </div>
        </fieldset>

        <br />
        <div class="pure-controls">
            {{-- <button type="submit" class="pure-button button-small">
                <i class="fa fa-arrow-right"></i>
                <span>Weiter zum nächsten Schritt</span>
            </button> --}}
            <button @click.prevent="next()">Next</button>
        </div>
       
    {!! Form::close() !!}   
</div>

@include('errors._errors')
@stop

@section('scripts')
    {{-- <script type="text/javascript" src="{{ asset('js/lib.js') }}"></script> --}}
    <script type="text/javascript" src="{{  asset('js/app.js') }}"></script>
@stop