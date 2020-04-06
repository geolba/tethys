@extends('layouts.app')

@section('hero')
<!-- Hero -->
<section id="hero" class="hero u-full-width">
    <div class="hero-image"></div>
    <div class="container centered">
        <div class="twelve columns">
            <h1 class="main-heading">Tethys</h1>
            <h2 class="separator">Research Data Repository</h1>
                <h2>
                    Data Publisher for Geoscience Austria
                </h2>
        </div>
    </div>
</section>
@endsection

@section('content')
<!-- Introduction -->
<section data-sr id="introduction" class="introduction u-full-width">
    <div class="container">
        <div class="row">
            <div class="twelve columns">
                <h3 class="separator">Einleitung</h3>
                <p class="paragraph-justify">
                    Tethys RDR ist ein publizierendes Forschungsdatenrepositorium der Geologischen Bundesanstalt (GBA),
                    das an der GBA generierte geowissenschaftliche Forschungsdaten veröffentlicht.
                    Zum derzeitigen Stand wird Tethys RDR in deutscher Sprache bereitgestellt.
                    Die englische Version befindet sich in Vorbereitung und wird sukzessive ebenfalls zur Verfügung
                    gestellt.
                    Die Forschungsdatenpublikationen und die dazugehörigen Metadaten können in Deutsch und in Englisch
                    veröffentlicht werden.
                    Tethys RDR hat den Anspruch, publizierte Datensätze unverändert, langfristig und nachhaltig
                    bereitzustellen.
                    Dadurch ist Tethys ein Forschungsdatenrepositorium, das ein permanentes Referenzieren ermöglicht und
                    somit die darin publizierten Datensätze zitierfähig macht.
                </p>
                <p class="paragraph-justify">
                    Der Name Tethys kommt ursprünglich aus der griechischen Mythologie und benennt eine Titanin und
                    Meeresgöttin.
                    Ende des 19. Jahrhunderts entdeckte Eduard Suess (1831–1914), ein bedeutender österreichischer
                    Geologe,
                    den mesozoischen Ozean und benannte ihn nach dieser Tethys.
                </p>
                <p class="paragraph-justify">
                    Die teilweise sehr mächtigen Tethys-Sedimente aus den Schelfregionen wurden teilweise in spätere
                    Gebirgsbildungen einbezogen
                    und stellen dadurch leicht zugängliche Archive da,
                    die auch in Österreich geo- und umweltwissenschaftlich wertvolle Auskunft über die damaligen
                    Umweltbedingungen geben können.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Work -->
<section data-sr id="work" class="work u-full-width">
    <div class="container">
        <div class="row">
            <div class="twelve columns">
                <h3 class="separator">TETHYS SERVICES</h3>
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

<!-- Help -->
<section data-sr id="help" class="help u-full-width featured-bg-image">
    <h4 class="centered">
        <a class="button inverted" href="{{ URL::route('frontend.home.help') }}">We're here to help!</a>
    </h4>
</section>

<!-- Clients -->
<section data-sr id="clients" class="clients u-full-width">
    <div class="container">
        <div class="row">
            <h3 class="separator">Cooperation Partners</h3>
        </div>
        <ul class="clients u-full-width u-cf">
            <li>
                <img src="images/clients/logo_zamg.png" alt="Zamg Client">
            </li>
        </ul>
    </div>
</section>

<!-- About -->
<section data-sr id="about" class="about u-full-width">
    <div class="container">
        <div class="row">
            <div class="twelve columns">
                <h3 class="separator">About us</h3>
                <h4>
                    TETHYS focuses on disciplines who do not have a tradition
                    of data sharing thus ensuring better availability,
                    sustainable preservation and (independent) publication
                    capacity of their research data.
                </h4>
            </div>
        </div>
    </div>
</section>

<!-- Why us -->
{{-- <section data-sr id="why-us" class="why u-full-width"> --}}
<section data-sr id="benefits" class="why u-full-width">
    <div class="container">
        <div class="row">
            <div class="twelve columns">
                <h3 class="separator">Why choose us</h3>
                <h4>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                    labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                    laboris.</h4>
            </div>
        </div>
        <div class="row">
            <ul class="services">
                <li class="four columns">
                    <div class="service-image">
                        <img src="images/services/iphone5c.png">
                    </div>
                    <h5>App Design</h5>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                        ilabore et dolore.</p>
                </li>
                <li class="four columns">
                    <div class="service-image">
                        <img src="images/services/ipad_air.png">
                    </div>
                    <h5>Responsive Layout</h5>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                        ilabore et dolore.</p>
                </li>
                <li class="four columns">
                    <div class="service-image">
                        <img src="images/services/macbook_pro.png">
                    </div>
                    <h5>Pixel Perfect design</h5>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                        ilabore et dolore.</p>
                </li>
            </ul>
        </div>
    </div>
</section>

<!-- Contact -->
<section data-sr id="contact" class="contact u-full-width">
    <div class="container">
        <div class="row">
            <div class="twelve columns">
                <h3 class="separator">Get in touch</h3>
                <h4>
                    Want to keep updated or need further information?
                </h4>
            </div>
        </div>
    </div>
</section>

<!-- Contact Us -->
<section data-sr class="container contact-us u-full-width u-max-full-width">
    <div class="row">
        <div class="four columns contact-us-details">
            <h3>Our Location</h3>
            <h5>
                Neulinggasse 38 <br />
                1030 Wien <br />
                +43-1-7125674 <br />
            </h5>
            <ul class="social-links">
                <li>
                    <a href="https://twitter.com/GeologischeBA" target="_blank">
                        <i class="fab fa-twitter"></i>
                    </a>
                </li>
                <li>
                    <a href="https://www.facebook.com/geologie.ac.at" target="_blank">
                        <i class="fab fa-facebook"></i>
                    </a>
                </li>

            </ul>
        </div>
        {{-- <div class="eight columns contact-us-form">
            <form>
                <div class="row">
                    <div class="six columns">
                        <input class="u-full-width" type="text" placeholder="Name" id="nameInput">
                    </div>
                    <div class="six columns">
                        <input class="u-full-width" type="email" placeholder="Email" id="emailInput">
                    </div>
                </div>
                <textarea class="u-full-width" placeholder="Message" id="messageInput"></textarea>
                <input class="button u-pull-right" type="submit" value="Send">
            </form>

        </div> --}}
    </div>
</section>

@endsection