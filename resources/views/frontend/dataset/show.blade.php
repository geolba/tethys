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

            <p class="dataset__abstract">Erstellungsjahr: {{ $dataset->server_date_published->year }}</p>
            <p class="dataset__abstract">Sprache: {{ $dataset->language }}</p>
            <p class="dataset__abstract">Objekttyp: {{ $dataset->type }}</p>

            <p class="dataset__abstract">Herausgeber: {{ $dataset->creating_corporation }}</p>
            <p class="dataset__abstract">Publisher: {{ $dataset->publisher_name }}</p>
            <p class="dataset__abstract">Coverage: {{ $dataset->geoLocation() }}</p>

            @if($dataset->hasEmbargoPassed() == true)
            <table id="items" class="pure-table pure-table-horizontal">
              <thead>
                <tr>
                  <th>Path Name</th>               
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
                </tr>
                @endforeach
              </tbody>
            </table>
            @else
            <span>Datensatz hat noch ein gültiges Embargo-Datum.</span>
            @endif

          </div>
        </div>
      </div>

    </main>
  </div>
</section>



@stop

@section('head')
<style>
  /* body {
    margin: 0;
    padding: 0;
  } */

  #app {
    font-family: "Avenir", Helvetica, Arial, sans-serif;
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
    // flex: 1 1 80%;
    // display: flex;
    align-items: center;
    justify-content: center;

  }

  .box {
    margin: 0 auto;
    padding: 100px 20px 70px;
    // background-color: salmon;


  }

  .dataset_heaader {
    //  background-color: lightgray;
    position: relative;
  }

  .dataset {
    // max-width: 500px;
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

@stop