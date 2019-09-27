@extends('layouts.app')

@section('title', Lang::get('resources.solrsearch_title_simple'))

@section('content')

<section data-sr id="search" class="search normal u-full-width">
    {{-- <div class="container"> --}}

    {{-- <div class="row">
        <div class="twelve columns">          
            <div class="content">
                <div class="sidebar-simplesearch">
                    {{ Form::open(array('method' => 'GET')) }}
    {!! Form::text('q', Input::get('q'), array('class'=>'search-input',
    'placeholder'=>'Enter your search term')) !!}
    <span class="input-group-btn">
        <button type="submit">
            <i class="fa fa-search"></i>
        </button>
    </span>
    {{ Form::close() }}
    </div>
    </div>
    </div>
    </div> --}}

    <!-- SEARCH RESULTS GO HERE, EXACTLY AS BEFORE -->

    <div class="search-container row">

        <div class="four columns left-bar">
            <div class="sidebar left-bar">
                {{-- <h3 class="indexheader">Refine by</h3> --}}
                <h2 class="indexheader">DataXplore</h2>
                @if (isset($resultset))



                <?php $facet = $resultset->getFacetSet()->getFacet('language'); ?>
                <div class="panel panel-primary">
                    <h3 class="panel-title">Language</h3>

                    <ul class="filter-items">
                        @foreach ($facet as $value => $count)
                        @if ($count)
                        <li class="active" role="radio">
                            <input class="css-w1gpbi" name="language" id="{{ $value }}" type="radio"
                                value="{{ $value }}">
                            <label for="{{ $value }}"><span>{{ $value }} ({{ $count }}) </span></label>
                        </li>
                        {{-- <li class="list-group-item">
                            <a class="firstLabel"
                                href="?{{ http_build_query(array_merge(Input::all(), array('language' => $value))) }}">{{ $value }}</a>
                        <span class="badge">{{ $count }}</span>
                        </li> --}}
                        @endif
                        @endforeach
                    </ul>
                </div>

                <?php $facet = $resultset->getFacetSet()->getFacet('datatype'); ?>
                <div class="panel panel-primary">
                    {{-- <h3 class="panel-title">Datatype</h3> --}}
                    <h3 class="panel-title">Data Type</h3>
                    <ul class="filterItems">
                        @foreach ($facet as $value => $count)
                        @if ($count)
                        <li class="list-group-item">
                            <a class="firstLabel"
                                href="?{{ http_build_query(array_merge(Input::all(), array('datatype' => $value))) }}">{{ $value }} ({{ $count }})</a>
                            {{-- <span class="badge">{{ $count }}</span> --}}
                        </li>
                        @endif
                        @endforeach
                    </ul>
                </div>

                <?php $facet = $resultset->getFacetSet()->getFacet('year'); ?>
                <div class="panel panel-primary">
                    <h3 class="panel-title">Year</h3>

                    <ul class="filterItems">
                        @foreach ($facet as $value => $count)
                        @if ($count)
                        <li class="list-group-item">
                            <a class="firstLabel"
                                href="?{{ http_build_query(array_merge(Input::all(), array('year' => $value))) }}">{{ $value }} ({{ $count }})</a>
                            {{-- <span class="badge">{{ $count }}</span> --}}
                        </li>
                        @endif
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>

        <div class="eight columns right-bar">
            <div class="sidebar right-bar">
            <div id="search-input"></div>
                {{-- <div class="row">
                    <div class="twelve columns">
                        <div class="sidebar-simplesearch">
                            {{ Form::open(array('method' => 'GET')) }}

                            {!! Form::text('q', Input::get('q'), array('class'=>'search-input',
                            'placeholder'=>'Enter your search term...')) !!}

                            <button type="submit" class="css-1gklxk5 ekqohx90">
                                <svg alt="Search" class="search-icon" height="14" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 15 15">
                                    <title>Search</title>
                                    <path
                                        d=" M6.02945,10.20327a4.17382,4.17382,0,1,1,4.17382-4.17382A4.15609,4.15609, 0,0,1,6.02945,10.20327Zm9.69195,4.2199L10.8989,9.59979A5.88021,5.88021, 0,0,0,12.058,6.02856,6.00467,6.00467,0,1,0,9.59979,10.8989l4.82338, 4.82338a.89729.89729,0,0,0,1.29912,0,.89749.89749,0,0,0-.00087-1.29909Z ">
                                    </path>
                                </svg>
                            </button> 
                            {{ Form::close() }}
                        </div>

                    </div>
                </div> --}}

                @if (isset($resultset))
                <div class="results">
                    <div class="result-list-info">
                        <div class="resultheader">
                            Your search yielded <strong>{{ $resultset->getNumFound() }}</strong> results:
                        </div>
                    </div>

                    <section class="result-list-container">
                        <div class="row">                           
                            <ul class="search-items isotope js-isotope u-cf">
                                @foreach ($resultset as $document)


                                <li class="six columns post">
                                    <div class="search-detail">

                                        <div>
                                            <a href="{{ URL::route('frontend.dataset.show',['id' =>$document->id]) }}">
                                                {{ $document->title_output }}
                                            </a>

                                        </div>

                                        {{ date('D, m M, Y', $document->server_date_modified) }}
                                        <p>
                                            @if (isset($document->title_additional) &&
                                            is_array($document->title_additional))
                                            <em>Additional Title: {{ implode('; ', $document->title_additional) }}</em>
                                            @endif
                                        </p>
                                        @if (is_array($document->author))
                                        <em>Author: {{ implode('; ', $document->author) }}</em>
                                        @endif                                      
                                        <p class="clamped clamped-2">
                                            <span class="text">
                                                Abstract: {{ $document->abstract_output }}
                                                <span class="ellipsis">
                                                    &#133;
                                                </span>
                                                <span class="fill"></span>
                                            </span>
                                        </p>
                                        <div class="css-subject">
                                            <div class="css-keyword">#blade</div>
                                            <div class="css-keyword">#graphql</div>
                                        </div> 
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </section>
                </div>
                @endif
            </div>
        </div>
    </div>



</section>

@endsection


@section('head')
<link rel="stylesheet" href="{{  asset('css/search_style.css') }}">
@endsection

@section('after-scripts')
<script type="text/javascript" src="{{  asset('js/search/main.js') }}"></script>
@stop