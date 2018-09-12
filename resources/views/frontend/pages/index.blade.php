@extends('layouts.app')

@section('content')
<div class="content">           
    <h1>
        {!! $page->title !!} 
    </h1>

    <div>
        <p>
            {!! $page->description !!}             
        </p>
    </div>
</div>

@endsection