@extends('layouts.app')

@section('title', Lang::get('resources.home_index_contact_pagetitle'))

@section('content')
<!--<div class="pure-g content">
    <div class="pure-u-1 content lead">
        <h1 class="title"> @lang('resources.home_index_contact_title')</h1>
    </div>
</div>-->

<div class="pure-g">

    <div class="pure-u-1 pure-u-md-2-3">
        <div class="content">
            <h1>Kontakt</h1>
            <div id="simplecontact">
                {!! Form::open(array('class' => 'pure-form pure-form-stacked')) !!}

                <fieldset>
                    <div class="pure-control-group">
                        {!! Form::label('name', 'Your Name') !!}
                        {!! Form::text('name', null, ['class' => 'pure-input-1']) !!}
                        <span class="pure-form-message-inline">This is a required field.</span>
                    </div>

                    <div class="pure-control-group">
                        {!! Form::label('email', 'E-mail Address') !!}
                        {!! Form::text('email', null, ['class' => 'pure-input-1']) !!}
                    </div>

                    <div class="pure-control-group">
                        {!! Form::label('msg', 'Message') !!}
                        {!! Form::textarea('msg', null, ['class' => 'pure-input-1', 'placeholder' => "Enter something here..."]) !!}
                    </div>

                    <div class="pure-controls">
                        {!! Form::submit('Send', ['class' => 'pure-button pure-button-primary']) !!}
                    </div>

                </fieldset>

                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <div class="pure-u-1 pure-u-md-1-3">
        <div class="sidebar">
        </div>
    </div>

</div>

@endsection