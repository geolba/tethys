@extends('layouts.app')

@section('title', Lang::get('resources.home_index_contact_pagetitle'))

@section('content')

{{-- <div class="pure-g">
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
</div> --}}

{{-- <section data-sr id="contact" class="contact u-full-width">
    <div class="container">
        <div class="row">
            <div class="two-thirds column">
                <h3 class="separator">Get in touch</h3>
                <h4>
                    Want to keep updated or need further information?
                </h4>
            </div>
            <div class="one-third column">
                <div class="sidebar">
                </div>
            </div>
        </div>
    </div>
</section> --}}
<!-- Contact Us -->
<section data-sr class="contact-us u-full-width">
        <div class="container">
    <div class="row">      
       
        <h4>
            Want to keep updated or need further information?
        </h4>
       
           
    </div>
    <div class="row">
        <div class="four columns contact-us-details">
            <h3>Our Location</h3>
            <h5>
                Neulinggasse 38 <br />
                1030 Wien <br />
                +43-1-7125674 <br />
            </h5>
            <ul class="social-links">
                <li>
                    <a href="https://twitter.com/GeologischeBA" target="_blank">
                        <i class="fab fa-twitter"></i>
                    </a>
                </li>
                <li>
                    <a href="https://www.facebook.com/geologie.ac.at" target="_blank">
                        <i class="fab fa-facebook"></i>
                    </a>
                </li>

            </ul>
        </div>
        <div class="eight columns contact-us-form">
            <form>
                <div class="row">
                    <div class="six columns">
                        <input class="u-full-width" type="text" placeholder="Name" id="nameInput">
                    </div>
                    <div class="six columns">
                        <input class="u-full-width" type="email" placeholder="Email" id="emailInput">
                    </div>
                </div>
                <textarea class="u-full-width" placeholder="Message" id="messageInput"></textarea>
                <input class="button u-pull-right" type="submit" value="Send">
            </form>

        </div>
    </div>
        </div>
</section>

@endsection