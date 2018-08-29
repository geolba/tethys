@extends('app')

@section('content')

<div class="pure-g">
    <div class="pure-u-1 pure-u-md-2-3">
        <div class="content">
            {!! Form::open(array('class' => 'pure-form pure-form-stacked')) !!}

            <fieldset>

                <div class="pure-control-group">
                    <label for="data-en-key-home_index_imprint_pagetitle" class="optional">Titel der Seite bzw. des Reiters im Web-Browser</label>                
                    <input name="data[en][key][home_index_imprint_pagetitle]" id="data-en-key-home_index_imprint_pagetitle" class="pure-input-1" type="text">
                </div>

                <div class="pure-control-group">
                    <label for="data-en-key-home_index_imprint_title" class="optional">Titel auf der Seite</label>
                    <input name="data[de][key][home_index_imprint_title]" id="data-de-key-home_index_imprint_title" value="Impressum nach �5 Telemediengesetz" class="pure-input-1" type="text">
                </div>

            </fieldset>

            {!! Form::close() !!}
        </div>
    </div>
</div>

@endsection
