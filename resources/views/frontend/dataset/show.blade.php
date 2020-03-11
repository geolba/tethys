@extends('layouts.app')
@section('content')


<section class="normal dataset u-full-width">
  <div id="app">
    <main>

      <div class="content">

        <div class="box" v-if="dataset">
          <div class="dataset_heaader">
            <div class="dataset__blog-meta">published: {{ $dataset->server_date_published->toDayDateTimeString() }}
            </div>
            <h1 class="dataset__title">{{ $dataset->mainTitle()->value }}</h1>

            <p class="dataset__id">{{ $dataset->id }}</p>
          </div>
          <div class="dataset">

            <!-- Nav tabs -->
            {{-- <input type="radio" id="metadata-option" name="nav-tab" type="hidden">
            <input type="radio" id="file-option" name="nav-tab" type="hidden"> --}}

            <div class="row">
              <!-- HTML markup for the tab navigation: -->
              <div class="twelve columns">
                <ul class="tab-nav">
                  <li class="metadata-link">
                    <span class="remove-check button active" name="#one">Metadaten</span>
                  </li>
                  <li class="file-link">
                    <span class="remove-check button" name="#two">Inhalt</span>
                  </li>
                  <li class="file-link">
                    <span class="remove-check button" name="#three">Technische Metadaten</span>
                  </li>
                </ul>

                <!-- HTML markup for tab content: Tab panes -->
                <div class="tab-content">

                  <div class="tab-pane content-metadata active" id="one">

                    @if($dataset->additionalTitles()->exists())
                    <p class="dataset__abstract">
                      Zusätzliche Titel:
                      <ul>
                        @foreach ($dataset->additionalTitles as $title)
                        <li>{{ $title->type }}: {{ $title->value }}</li>
                        <br />
                        @endforeach
                      </ul>
                    </p>
                    @endif

                    <p class="dataset__abstract">{{ $dataset->mainAbstract()->value }}</p>

                    @if($dataset->authors()->exists())
                    <p class="dataset__abstract" v-if="dataset.subject && dataset.subject.length > 0">
                      Ersteller/Autor: {{ $dataset->authors->implode('full_name', ', ')  }}
                    </p>
                    @endif

                    @if($dataset->contributors()->exists())
                    <p class="dataset__abstract" v-if="dataset.subject && dataset.subject.length > 0">
                      Beitragende: {{ $dataset->contributors->implode('full_name', ', ')  }}
                    </p>
                    @endif

                    @if($dataset->subjects()->exists())
                    <p class="dataset__abstract" v-if="dataset.subject && dataset.subject.length > 0">
                      Schlüsselwörter: {{ $dataset->subjects->implode('value', ', ')  }}
                    </p>
                    @endif

                    @if($dataset->references()->exists())
                    <p class="dataset__abstract">
                      Referenzen:
                      <ul>
                        @foreach ($dataset->references as $reference)
                        <li>{{ $reference->value }}</li>
                        <br />
                        @endforeach
                      </ul>
                    </p>
                    @endif

                    <p class="dataset__abstract">Erstellungsjahr: {{ $dataset->server_date_published->year }}</p>
                    <p class="dataset__abstract">Sprache: {{ $dataset->language }}</p>
                    <p class="dataset__abstract">Objekttyp: {{ $dataset->type }}</p>
                    <p class="dataset__abstract">Lizenz: {{ $dataset->license()->name_long }}</p>
                    <p class="dataset__abstract">Coverage: {{ $dataset->geoLocation() }}</p>

                  </div>

                  <div class="tab-pane content-file" id="two">
                    @if($dataset->embargo_date != null)
                    <p class="dataset__abstract">Ende des Embargo-Zeitraums:
                      {{ $dataset->embargo_date->toDateString() }}</p>
                    @else
                    <p class="dataset__abstract">Ende des Embargo-Zeitraums: - </p>
                    @endif

                    @if($dataset->hasEmbargoPassed() == true)
                    <table id="items" class="pure-table pure-table-horizontal">
                      <thead>
                        <tr>
                          <th>Path Name</th>
                          <th>File Size</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($dataset->files as $key => $file)
                        <tr>
                          <td>
                            @if($file->exists() === true)
                            <a href="{{ route('file.download', ['id' => $file->id]) }}"> {{ $file->label }} </a>
                            @else
                            <span class="alert">missing file: {{ $file->path_name }}</span>
                            @endif
                          </td>
                          <td>
                            <span>{{ $file->formatSize(2) }}</span>
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                    @else
                    <span>Datensatz hat noch ein gültiges Embargo-Datum.</span>
                    @endif
                  </div>

                  <div class="tab-pane content-technical-metadata" id="three">
                    <p class="dataset__abstract">Persistenter Identifikator:
                      {{ "http://www.tethys.at/" . $dataset->id }}</p>
                    <p class="dataset__abstract">Status: {{ $dataset->server_state }}</p>
                    <p class="dataset__abstract">Eingestellt von: {{ $dataset->user->login }}</p>
                    <p class="dataset__abstract">Erstellt am: {{ $dataset->created_at->toDateString() }}</p>
                    <p class="dataset__abstract">Herausgeber: {{ $dataset->creating_corporation }}</p>
                    <p class="dataset__abstract">Publisher: {{ $dataset->publisher_name }}</p>

                  </div>

                </div>
              </div>
            </div> <!--  end row -->

          </div>

        </div>
      </div>

    </main>
  </div>
</section>



@stop

@section('head')

<style>
  ul.tab-nav {
    list-style: none;
    border-bottom: 1px solid #bbb;
    padding-left: 5px;
  }

  ul.tab-nav li {
    display: inline;
  }

  ul.tab-nav li span.button {
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
    margin-bottom: -1px;
    border-bottom: none;
  }

  ul.tab-nav li span.active.button {
    border-bottom: 0.175em solid #fff;
    border-bottom-color: #00bfffcc;
    color: #00bfffcc;
    /* background-color: #6c6e6b; */
  }

  .tab-content .tab-pane {
    display: none;
    /* visibility: hidden; */
  }

  .tab-content .tab-pane.active {
    display: block;
    /* visibility: visible; */
  }



  /* body {
    margin: 0;
    padding: 0;
  } */

  #app {
    /* font-family: "Avenir", Helvetica, Arial, sans-serif; */
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    color: #2c3e50;
  }

  h1,
  h2 {
    font-weight: normal;
  }

  main {
    display: flex;
    /* height: calc(100vh - 90px); */
    max-width: 1200px;
    margin-top: 90px;
    margin-left: auto;
    margin-right: auto;
    overflow: hidden;
  }

  aside {
    flex: 1 0 30%;
    height: 100%;
    overflow-y: auto;
    width: 30%;
    padding: 50px 30px;
    box-sizing: border-box;
    border-right: 1px solid #42b983;
  }

  .content {
    /* // flex: 1 1 80%;
    // display: flex; */
    align-items: center;
    justify-content: center;

  }

  .box {
    margin: 0 auto;
    padding: 100px 20px 70px;
    /* // background-color: salmon; */


  }

  .dataset_heaader {
    /* //  background-color: lightgray; */
    position: relative;
  }

  .dataset {
    /* // max-width: 500px; */
  }

  .dataset__title {
    position: relative;
    text-transform: uppercase;
    z-index: 1;
  }

  .dataset__abstract {
    position: relative;
    z-index: 1;
  }

  .dataset__id {
    position: absolute;
    font-size: 250px;
    bottom: -40px;
    margin: 0;
    color: #eeeeee;
    right: -20px;
    line-height: 1;
    font-weight: 900;
    z-index: 0;
  }

  .dataset__blog-meta {
    padding: 10px 0 20px;
    color: #c2c2c2;
    // font-size: 0.8em;
    margin-top: -1.7em;
  }
</style>
@endsection


@section('after-scripts')
<script type="text/javascript">
  (function() {
    function main() {
        var tabButtons = [].slice.call(document.querySelectorAll('ul.tab-nav li span.button'));

        tabButtons.map(function(button) {
            button.addEventListener('click', function() {
                document.querySelector('li span.active.button').classList.remove('active');
                button.classList.add('active');

                document.querySelector('.tab-pane.active').classList.remove('active');
                document.querySelector(button.getAttribute('name')).classList.add('active');
            })
        })
    }

    if (document.readyState !== 'loading') {
        main();
    } else {
        document.addEventListener('DOMContentLoaded', main);
    }
})();
</script>
@stop