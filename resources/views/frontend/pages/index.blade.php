@extends('layouts.app')

@section('content')
<section id="sitelinks" class="normal u-full-width">
    <div class="container">
        <div class="row">
            <div class="twelve columns">
                <h1>
                    {!! $page->title !!}
                </h1>

                <div>
                    <p>
                        {!! $page->description !!}
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection