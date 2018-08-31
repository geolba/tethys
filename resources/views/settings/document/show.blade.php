@extends('settings.layouts.app')

@section('content')
<div class="header">
    <h3 class="header-title">
        <i class="fa fa-archive"></i> Detail
    </h3>
</div>

<div class="pure-g box-content">
    <div id="titlemain-wrapper"  class="pure-u-1 pure-u-md-1-2">
        <div class="frontdoor_pagination">
            <a href="{{ route('settings.document') }}" class="pure-button button-small">
                <i class="fa fa-arrow-left"></i> BACK
            </a>
        </div>
        @foreach ($document->titles as $title)
        <h2 class="titlemain"> {{ $title->value }}</h2>
        @endforeach
    </div>

    <div id="result-data"  class="pure-u-1 pure-u-md-1-2">
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