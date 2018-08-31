@if ($breadcrumbs)
    <div class="breadcrumb">
        <i class="fa fa-home"></i>
        <a href="{{route('settings.dashboard')}}" rel="Dashboard">Dashboard</a>
       
        @for($i = 1; $i < count($breadcrumbs); $i++)
            <?php
            $breadcrumb = $breadcrumbs[$i];         
            ?>   
            <i class="fa fa-angle-right"></i>
            <a href="{{ $breadcrumb->url }}" rel="Dashboard">{{ $breadcrumb->title }}</a>
        @endfor
        </div>
@endif