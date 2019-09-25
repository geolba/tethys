@extends('layouts.app')

@section('title', Lang::get('resources.solrsearch_title_simple'))

@section('content')

<section data-sr id="search" class="search normal u-full-width">
    <div class="container">
        <div class="row">
            <div class="twelve columns">
                <h3 class="separator">SEARCH</h3>
            </div>
        </div>
        <!-- SEARCH RESULTS GO HERE, EXACTLY AS BEFORE -->
        <div class="content">
            <div class="sidebar-simplesearch">
                {{ Form::open(array('method' => 'GET')) }}

                {!! Form::text('q', Input::get('q'), array('class'=>'u-full-width',
                'placeholder'=>'Enter your search term')) !!}
                <span class="input-group-btn">
                    {{-- {{ Form::submit('Search', array('class' => 'btn btn-primary btn-lg')) }} --}}
                    <button type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </span>


                {{ Form::close() }}
            </div>
        </div>

        @if (isset($resultset))
        <div class="row">

            <div class="four columns">
                <div id="left-bar">
                    <h3 class="indexheader">Refine by</h3>
                    <?php $facet = $resultset->getFacetSet()->getFacet('language'); ?>
                    <div class="panel panel-primary">

                        {{-- <h3 class="panel-title">Language</h3> --}}
                        <h3 class="filterViewModelName">Language</h3>

                        <ul class="filterItems">
                            @foreach ($facet as $value => $count)
                            @if ($count)
                            <li class="list-group-item">
                                <a class="firstLabel"
                                    href="?{{ http_build_query(array_merge(Input::all(), array('language' => $value))) }}">{{ $value }}</a>
                                <span class="badge">{{ $count }}</span>
                            </li>
                            @endif
                            @endforeach
                        </ul>
                    </div>

                    <?php $facet = $resultset->getFacetSet()->getFacet('datatype'); ?>
                    <div class="panel panel-primary">
                        {{-- <h3 class="panel-title">Datatype</h3> --}}
                        <h3 class="filterViewModelName">Data Type</h3>
                        <ul class="filterItems">
                            @foreach ($facet as $value => $count)
                            @if ($count)
                            <li class="list-group-item">
                                <a class="firstLabel"
                                    href="?{{ http_build_query(array_merge(Input::all(), array('datatype' => $value))) }}">{{ $value }}</a>
                                <span class="badge">{{ $count }}</span>
                            </li>
                            @endif
                            @endforeach
                        </ul>
                    </div>

                    <?php $facet = $resultset->getFacetSet()->getFacet('year'); ?>
                    <div class="panel panel-primary">
                        <h3 class="filterViewModelName">Year</h3>

                        <ul class="filterItems">
                            @foreach ($facet as $value => $count)
                            @if ($count)
                            <li class="list-group-item">
                                <a class="firstLabel"
                                    href="?{{ http_build_query(array_merge(Input::all(), array('year' => $value))) }}">{{ $value }}</a>
                                <span class="badge">{{ $count }}</span>
                            </li>
                            @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="eight columns">
                @if (isset($resultset))
                <header>
                    <p>Your search yielded <strong>{{ $resultset->getNumFound() }}</strong> results:</p>
                    <hr />
                </header>

                <section class="results normal">
                    <ul class="search-items isotope js-isotope u-cf">
                        @foreach ($resultset as $document)

                        <li>
                            <div class="post">
                                <header class="post-header">
                                    <h3 class="post-title">
                                        <a href="{{ URL::route('frontend.dataset.show',['id' =>$document->id]) }}">
                                            {{ $document->title_output }}
                                        </a>
                                    </h3>
                                </header>
                                <div class="blog-meta">
                                    {{ date('D, m M, Y', $document->server_date_modified) }}
                                </div>
                                @if (isset($document->title_additional) && is_array($document->title_additional))
                                <div class="blog-meta">
                                    <em>Additional Title: {{ implode('; ', $document->title_additional) }}</em>
                                </div>
                                @endif

                                <div class="post-description">

                                    @if (is_array($document->author))
                                    <em>Author: {{ implode('; ', $document->author) }}</em>
                                    @endif

                                    <p>
                                        <em>Abstract: {{ $document->abstract_output }}</em>
                                    </p>
                                    <p>
                                        <em>Creating Corporation: {{ $document->creating_corporation }}</em>
                                    </p>
                                </div>


                            </div>
                        </li>

                        @endforeach
                    </ul>
                </section>
                @endif
            </div>
        </div>
        @endif

    </div>
</section>

@endsection


@section('head')

<style>
    *-------------------------------------------------- */

    /* lists in the left bar */
    /*-------------------------------------------------- */
    .overflowing {
        color: #444444;
        list-style: none;
    }

    .overflowing span {
        color: #444444;
        font-style: italic;
        text-decoration: underline;
        cursor: pointer;
    }

    ul.filterItems li {
        min-height: 15px;
        color: #444;
        font-size: 12px;

        list-style-type: disc;
        /*list-style-position:inside;*/
        /*
     * We want the bullets outside of the list,
     * so the text is aligned. Now the actual bullet
     * is outside of the list’s container
     */
        list-style-position: outside;
        /*
     * Because the bullet is outside of the list’s
     * container, indent the list entirely
     */
        margin-left: 1em;
    }

    .filterItems.limited li:nth-of-type(1n+6) {
        display: none;
    }

    ul.filterItems .active {
        background-color: lightgray;
    }

    /*ul.filterItems li.hover{
    background-color: yellow;   
    }*/

    /*ul.filterItems li.hover .firstLabel,
    ul.filterItems li.hover a{   
     color: #0099cc;
     cursor:pointer;
    }*/

    /*ul.filterItems span.hover {   
     color: #0099cc;
     cursor:pointer;
    }*/
    ul.filterItems li a:hover {
        color: #0099cc;
        cursor: pointer;
    }

    /* Relativer Tooltip */
    .tooltip {
        position: relative;
        text-decoration: none;
        font-weight: bolder;
        color: #444444;
        cursor: pointer;
        overflow: visible;
        font-size: 8px;
    }





    .sidebar.left {
        background-color: rgb(244, 244, 244);
        float: left;
        border-left: none;
        width: 250px;
        height: 100%;
        overflow-y: auto;
    }

    /* #left-bar {
        position:absolute;
    height:auto;
    width:auto;
    bottom:0;
    top:0;
    left:0;
    right:0;
        margin: 15px;
    } */

    .sidebar.right {
        border: none;
        margin-left: 250px;
        background-color: white;
    }



    #left-bar .indexheader {
        color: #a9a9a9;
        font-weight: bold;

        display: block;
    }

    #left-bar .filterViewModelName {
        color: black;
        margin-top: 10px;
        margin-bottom: 2px;

        /* font-size: 12px; */
        display: block;
        font-weight: bold;
    }




    .sidebar-simplesearch {
        position: relative;
        margin-bottom: 2.5em;
        white-space: nowrap;
    }

    .sidebar-simplesearch input[type=text] {
        padding: 0.25em 0.3em;
        color: #666;
    }

    .sidebar-simplesearch button {
        padding: 0.25em 0.3em;
        border: none;
        background: none;
        position: absolute;
        right: 0.25em;
        color: #666;
    }
</style>

@endsection