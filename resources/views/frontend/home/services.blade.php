@extends('layouts.app')

{{-- @section('title', Lang::get('resources.home_about_pagetitle')) --}}

@section('content')

<!-- Work -->
<section data-sr id="work" class="work u-full-width">
    <div class="container">
        <div class="row">
            <div class="twelve columns">
                <h3 class="separator">Tethys SERVICES</h3>
            </div>
        </div>

        <div class="row">
            <ul class="work-items isotope js-isotope u-cf">
                <li class="four columns isotope-item design ui">
                    <!-- <img src="images/portfolio/work_1.svg"> -->
                    <div class="work-front">
                        <div class="vertical-centered">
                            <i class="fa fa-hdd icon" aria-hidden="true"></i>
                            <h3>Datenarchvierung</h3>
                        </div>
                    </div>
                    <div class="work-detail">
                        <div class="vertical-centered">
                            <p class="separator orange">Datenarchvierung</p>
                                <p class="paragraph-justify">
                                    Tethys RDR publiziert und archiviert nach den FAIR Prinzipien*
                                    nachhaltig so wie sicher geowissenschaftliche Datensätze in offenen, frei lesbaren
                                    Formaten.
                                    * <a href="https://en.wikipedia.org/wiki/FAIR_data" target=_blank>FAIR data</a>

                                </p>
                        </div>
                    </div>
                </li>
                <li class="four columns isotope-item branding web-design">
                    <!-- <img src="images/portfolio/work_2.svg"> -->
                    <div class="work-front">
                        <div class="vertical-centered">
                            <i class="fas fa-share-alt  icon"></i>
                            <h3>Datenpublikation</h3>
                        </div>
                    </div>
                    <div class="work-detail">
                        <div class="vertical-centered">
                            <p class="separator orange">Datenpublikation</p>
                            <p  class="paragraph-justify">
                                Die Datensätze werden mit standardisierten Metadatenschemen publiziert 
                                und sind somit nicht nur auffindbar, wiederverwendbar und auch maschinenlesbar, 
                                sondern können dadurch auch einfach zitiert werden.
                            </p>
                        </div>
                    </div>
                </li>
                <li class="four columns isotope-item mobile ui branding">
                    <!-- <img src="images/portfolio/work_3.svg"> -->
                    <div class="work-front">
                        <div class="vertical-centered">
                            <i class="fas fa-user-edit icon"></i>
                            <h3>Begutachtung</h3>
                        </div>
                    </div>
                    <div class="work-detail">
                        <div class="vertical-centered">
                            <p class="separator orange">Begutachtung</p>
                            <p class="paragraph-justify">
                                Alle im Tethys RDR publizierten Datensätze werden auf technische sowie inhaltliche Vollständigkeit geprüft 
                                und werden bei Bedarf auch einer fachlichen Begutachtung unterworfen. 
                                Ein klassischer Peer Review-Prozess ist in Vorbereitung.
                            </p>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</section>

@endsection