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
        <div class="row">

            <div class="twelve columns">
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
            </div>
        </div>

        @if (isset($resultset))
        <header>
            <p>Your search yielded <strong>{{ $resultset->getNumFound() }}</strong> results:</p>
            <hr />
        </header>

        <div class="row">
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


                        <div class="post-description">

                            @if (is_array($document->author))
                            <em>Author: {{ implode(', ', $document->author) }}</em>
                            @endif

                            <p>
                                <em>Abstract: {{ $document->abstract_output }}</em>
                            </p>
                        </div>


                    </div>
                </li>

                @endforeach
            </ul>
        </div>
        @endif


    </div>
</section>

@endsection


@section('head')

<style>
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

