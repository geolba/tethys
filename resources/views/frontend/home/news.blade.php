@extends('layouts.app')

{{-- @section('title', Lang::get('resources.home_news_pagetitle')) --}}

@section('content')

<section id="about" class="u-full-width">
    <div class="container">
        <div class="row">
            <div class="twelve columns">
                <h3 class="separator">
                    @lang('resources.home_news_pagetitle')
                </h3>
                <h4>Published: 27.07.2019</h4>
                <p>
                    re3data.org is a global registry of research data repositories. The registry covers research data
                    repositories from
                    different academic disciplines. re3data.org presents repositories for the permanent storage and
                    access to datasets
                    for researchers, funding bodies, publishers and academic institutions. re3data.org aims to promote a
                    culture of
                    sharing and increased access to research data.
                </p>
                <p>
                    re3data.org helps researchers to find appropriate repositories for the storage and access of
                    research data.
                    Further, it can be used by funding organisations to promote permanent access to research data from
                    their research projects.
                    In addition re3data.org offers publishers and academic institutions a tool for the identification of
                    research data repositories
                    where scientists can deposit their data.
                </p>
            </div>
        </div>

    </div>
</section>

@endsection