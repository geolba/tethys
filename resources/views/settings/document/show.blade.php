@extends('layouts.app')

@section('content')

<div>
    <div id="titlemain-wrapper">
        <div class="frontdoor_pagination">
            <a href="{{ route('settings.document') }}" class="pure-button">
                <i class="fa fa-arrow-left"></i> BACK
            </a>
        </div>
        @foreach ($document->titles as $title)
        <h2 class="titlemain"> {{ $title->value }}</h2>
        @endforeach
    </div>

    <div id="result-data">
        <div id="abstract">
            <ul>
                @foreach ($document->abstracts as $abstract)
                <li class="abstract preserve-spaces"> {{ $abstract->value }}</li>
                @endforeach
            </ul>
        </div>

    </div>


</div>

@stop