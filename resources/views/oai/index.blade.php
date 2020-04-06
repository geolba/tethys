@extends('layouts.app')

{{-- @section('title', Lang::get('resources.home_about_pagetitle')) --}}

@section('content')

<!-- Introduction -->
<section data-sr id="oai" class="oai u-full-width">
    <div class="container">
        <div class="row">
            <div class="twelve columns">
                <h5>OAI</h5>
                <p class="paragraph-justify">
                    Die Open Archives Initiative (OAI; http://www.openarchives.org) dient der Entwicklung und Förderung
                    von Interoperabilitätsstandards für die Auffindbarkeit von elektronischen Publikationen im Internet.
                    Das dazu entwickelte Protokoll OAI-PMH (OAI Protocol for Metadata Harvesting) wird zum globalen
                    „Ernten“ der durch Metadaten beschriebenen Publikationen im Internet herangezogen.
                    Eine Liste aller OAI-Provider wird unter folgender Adresse zur Verfügung gestellt:
                    <a href="https://www.openarchives.org/Register/BrowseSites"
                        target=_blank>www.openarchives.org/Register/BrowseSites</a>
                </p>
                <h5>OAI-PMH</h5>
                <p class="paragraph-justify">
                    Die OAI-PMH Basis URL für das Research Data Repository der Geologischen Bundesanstalt lautet
                    <a href="https://tethys.at/oai">https://tethys.at/oai</a> <br />
                    Derzeit unterstützt das OAI-Service den Metadatenstandard DC (OAI-PMH Dublin Core) und eingeschränkt
                    den Standard DataCite (aktuell in dieser Testkonfiguration noch ohne Vergabe einer im Schema
                    verpflichtenden DOI).
                </p>
                <ul class="quicklinks">
                    <li><a href="?verb=Identify">Identify</a> | </li>
                    <li><a href="?verb=ListRecords&amp;metadataPrefix=oai_dc">ListRecords</a> | </li>
                    <li><a href="?verb=ListSets">ListSets</a> | </li>
                    <li><a href="?verb=ListMetadataFormats">ListMetadataFormats</a> | </li>
                    <li><a href="?verb=ListIdentifiers&amp;metadataPrefix=oai_dc">ListIdentifiers</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>

@endsection