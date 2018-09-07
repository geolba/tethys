@extends('layouts.app')

@section('title', Lang::get('resources.solrsearch_title_simple'))

@section('content')




<div class="pure-g">

    <div class="pure-u-1 pure-u-md-2-3">
        <div class="content">
            <h1>Suche</h1>
            @include('frontend.partials.simpleSearchForm')

            <div id="searchbar">
                @if (isset($results))
                <div id="search_results" class="plugin-simplesearch-result search_results">
                    @foreach($results as $result)
                    <div class="result">
                        <dt class="results_title">
                            @if (!is_null($result->getAsset( 'abstract_output' )))
                            <img src="../img/theme/icon-on-off.png" alt="results_hideabstract_alt" onclick="$(function(){$('#abstractText_{{ $result->getId() }}').toggle();});" />
                            @endif

                            @if (!is_null($result->getAsset('title_output')))
                            <a href="{{ route('frontend.dataset.show', $result->getId()) }}">
                                {{ $result->getAsset('title_output') }}
                            </a>
                            @else
                            <a>results_missingtitle</a>
                            @endif

                            @if ($result->getAsset('year'))
                            <span>( {{ $result->getAsset('year') }} )</span>
                            @endif
                        </dt>


                        @if (!is_null($result->getAsset('author')))
                        <dt class="results_author">
                            @foreach($result->getAsset('author') as $authorIndex => $author)
                            <a>{{ htmlspecialchars($author)}}</a>
                            @endforeach
                        </dt>
                        @endif

                        <dt class="abstractText" id="abstractText_{{ $result->getId() }}">
                            {{ htmlspecialchars($result->getAsset('abstract_output')) }}
                        </dt>

                    </div>
                    @endforeach
                </div>
                @endif

                @include('frontend.solrsearch.pagination')
            </div>

        </div> 
    </div>

</div>

@endsection


@section('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        $('.abstractText').hide();
    });
</script>
 @endsection
