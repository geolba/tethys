{{-- \resources\views\errors\403.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class='col-lg-4 col-lg-offset-4'>
        <h1><center>403<br>
        ACCESS DENIED</center></h1>
        <h2>{{ $exception->getMessage() }}</h2>
    </div>

@endsection
				

		
