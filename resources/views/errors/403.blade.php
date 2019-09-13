{{-- \resources\views\errors\403.blade.php --}}
@extends('layouts.app')

@section('content')
<section class="normal dataset u-full-width">
    <div class="container">

        <h1>
            <center>403<br>
                ACCESS DENIED</center>
        </h1>
        <a href="{{ URL::previous() }}" class="btn btn-default">Back</a>
        <h2>{{ $exception->getMessage() }}</h2>

    </div>
</section>

@endsection