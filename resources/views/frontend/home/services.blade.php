@extends('layouts.app')

{{-- @section('title', Lang::get('resources.home_about_pagetitle')) --}}

@section('content')

<!-- Work -->
<section data-sr id="work" class="work u-full-width">
    <div class="container">
        <div class="row">
            <div class="twelve columns">
                <h3 class="separator">RDR SERVICES</h3>
            </div>
        </div>

        <div class="row">
            <ul class="work-items isotope js-isotope u-cf">
                <li class="four columns isotope-item design ui">
                    <!-- <img src="images/portfolio/work_1.svg"> -->
                    <div class="work-front">
                        <div class="vertical-centered">
                            <h3>Data Archival</h3>
                        </div>
                    </div>
                    <div class="work-detail">
                        <div class="vertical-centered">
                            <p class="separator orange">Data Archival</p>
                            <p>
                                RDR provides format-independent archiving services for data protection.
                            </p>
                        </div>
                    </div>
                </li>
                <li class="four columns isotope-item branding web-design">
                    <!-- <img src="images/portfolio/work_2.svg"> -->
                    <div class="work-front">
                        <div class="vertical-centered">
                            <h3>Data Publication</h3>
                        </div>
                    </div>
                    <div class="work-detail">
                        <div class="vertical-centered">
                            <p class="separator orange">Data Publication</p>
                            <p>
                                With RDR you can publish research data,
                                so that your data is citable and reusable.
                            </p>
                        </div>
                    </div>
                </li>
                <li class="four columns isotope-item mobile ui branding">
                    <!-- <img src="images/portfolio/work_3.svg"> -->
                    <div class="work-front">
                        <div class="vertical-centered">
                            <h3>Peer Review</h3>
                        </div>
                    </div>
                    <div class="work-detail">
                        <div class="vertical-centered">
                            <p class="separator orange">Peer Review</p>
                            <p>
                                All RDR datasets undergo a full, efficient peer review process.
                            </p>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</section>

@endsection