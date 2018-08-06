@extends('layouts.app')

@section('content')

<div>
  <div id="titlemain-wrapper">
    <div class="frontdoor_pagination">
    {{-- <a id="pagination-link-hitlist" href="{{ route('settings.document') }}">BACK</a> --}}
		<a href="{{ route('documents') }}" class="pure-button">
			<span class="glyphicon glyphicon-chevron-left" ></span> BACK
		</a>		

    </div>
    @foreach ($document->titles as $title)
      <h2 class="titlemain"> {{ $title->value }}</h2>
    @endforeach
  </div>

  <div id="result-data">
    <div id="abstract">
      <ul>
        @foreach ($document->abstracts as $abstract)
            <li class="abstract preserve-spaces"> {{ $abstract->value }}</p>
        @endforeach
      </ul>
    </div>

  </div>


 </div>

@stop