@extends('layouts.app')

{{-- @section('title', Lang::get('resources.home_about_pagetitle')) --}}

@section('content')


<!-- Help -->
<section data-sr id="help" class="help u-full-width">
    <div class="container">
        <div class="row">
            <div class="twelve columns">
                <h3 class="separator">Hilfe</h3>
                <p>                   
                </p>
            </div>
        </div>
        <div class="row">
            <ul class="help">
                <li class="four columns">
                    <div class="help-image">
                        <img src="images/help/secure_data.svg">
                    </div>
                    <h5>Über Tethys RDR</h5>
                    <p class="paragraph-justify">
                        Tethys RDR ist ein Datenverlag der Geologischen Bundesanstalt (GBA), 
                        der ausschließlich an der GBA generierte geowissenschaftliche Forschungsdaten publiziert. 
                        Die Datenpublikationen können sowohl in deutscher, als auch in englischer Sprache publiziert werden. 
                        Durch die Bereitstellung der Datenpublikation zusammen mit Metadaten nach standardisierten Schemata 
                        werden die Publikationen auffindbar und zitierbar.
                    </p>
                </li>
                <li class="four columns">
                    <div class="help-image">
                        <img src="images/help/specs.svg">
                    </div>
                    <h5>Voraussetzungen für das Publizieren von Daten</h5>
                    
                    <p class="paragraph-justify">GBA-Angehörigkeit oder in Kooperation mit der GBA gemeinsame Publikation der Daten.</p>
                    <p class="paragraph-justify">Im Besitz eines eigenen Zugangscodes zu sein bzw. um den Zugangscode anzufragen (<a href="/contact" target=_blank>Kontakt</a>).</p>
                    <p class="paragraph-justify">Die Datenpublikationsrichtlinien gelesen, verstanden und akzeptiert zu haben.</p>
                    <p class="paragraph-justify">Die Datensätze vollständig und fachlich korrekt hochladen.</p>
                    
                </li>
                <li class="four columns">
                    <div class="help-image">
                        <img src="images/help/upload.svg">
                    </div>
                    <h5>Upload Data</h5>
                    <p class="paragraph-justify">
                        Wird eine Datenpublikation erwünscht, so kann der Verfasser der Publikation sich direkt bei Tethys RDR einloggen 
                        und den Publikationsablauf starten. Wurde noch kein Account angelegt, steht das Tethys RDR-Team bereit, 
                        um die weiteren Schritte zu klären (<a href="/contact" target=_blank>Kontakt</a>). 
                    </p>
                </li>
            </ul>
        </div>
    </div>
</section>

@endsection