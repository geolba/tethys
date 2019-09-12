@extends('layouts.app')

{{-- @section('title', Lang::get('resources.home_about_pagetitle')) --}}

@section('content')

<!-- Help -->
<section data-sr id="help" class="help u-full-width">
    <div class="container">
        <div class="row">
            <div class="twelve columns">
                <h3 class="separator">HELP</h3>
            </div>
        </div>

        <div class="row">
            <ul class="help-items">
                <li class="four columns">
                        About RDR
                </li>
                <li class="four columns">
                        Requirements for the Use of RDR
                </li>
                <li class="four columns">
                        Upload Data
                </li>
            </ul>
        </div>
    </div>
</section>

@endsection