@extends('layouts.app')

@section('hero')
<div class="pure-g slider">
    <div class="container">
        <div class="pure-g">
            <div class="pure-u-1">
                <h2 class="brand-tagline">
                    <strong>RDR is a data repository</strong> for the research community with secure preservation and data publication.
                </h2>
            </div>
          
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="pure-g">
    <div class="pure-u-1 lead">
        <div class="content">
            <h1 class="title">DATA RESEARCH REPOSITORY</h1>
        </div>
        </div>
    </div>

<div class="pure-g content two-col">
    <div class="pure-u-1-2">
        <div class="box box-l">
            <!--<h2>Title</h2>-->
            <p> @lang('resources.home_index_welcome')</p>

        </div>
    </div>
    <div class="pure-u-1-2">
        <div class="box box-r">
            <p> @lang('resources.home_index_instructions')</p>
        </div>
    </div>
</div>

@endsection
