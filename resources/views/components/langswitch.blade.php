{{-- <div class="langswitch-wrapper">
  <a class="dropdown-toggle langswitch-toggle uppercase triangle-top-left selected" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
    aria-expanded="false">
          {{ $currentLocale }} <span class="caret"></span>
        </a>
  <ul class="dropdown-menu">
    @foreach($localesOrdered as $localeCode => $properties) @if ($localeCode != $currentLocale)
    <li>
      <a class="uppercase" rel="alternate" hreflang="{{$localeCode}}" href="{{ $localizedURLs[$localeCode] }}">
                {{ $localeCode }}
              </a>
    </li>
    @endif @endforeach
  </ul>
</div> --}}

<div class="langswitch-wrapper dropdown">
  <a class="dropdown-toggle langswitch-toggle uppercase triangle-top-left selected" data-toggle="dropdown" href="#" role="button"
    aria-haspopup="true" aria-expanded="false">
    {{ $currentLocale }} <span class="caret"></span>
  </a>
  <ul id="lang-switch" class="nav dropdown-menu" title="Choose your language">
    @foreach($localesOrdered as $localeCode => $properties) 
    @if ($localeCode != $currentLocale) 
    
    <li>
      <a title="documents" href="{{ URL::route('setlocale',['lang' => $localeCode]) }}">{{ $localeCode }}</a>
    </li>
 
    @endif 
    @endforeach
  </ul>
</div>


