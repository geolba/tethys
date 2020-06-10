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
                <h4>Tethys geht online!</h4>
                <p>
                    Ab 15. Juni 2020 steht das Forschungsdatenrepositorium Tethys für die Publikation von geowissenschaftlichen Forschungsdaten bereit.
                    Noch wurden keine Forschungsdaten publiziert und der Publikationszähler steht daher auch noch auf Null. 
                    Sind dann die ersten Forschungsdaten und ihre dazugehörigen Metadaten erfolgreich publiziert worden, können diese Daten angesehen,
                    heruntergeladen und lizenzkonform verwertet werden. Sobald sich mehrere Datenpublikationen auf Tethys befinden, 
                    können diese problemlos gesucht bzw. nach verschiedenen Kriterien gefiltert werden. 
                    Noch ist Tethys leer, aber nicht mehr lange!
                </p>                
            </div>
        </div>

    </div>
</section>

@endsection