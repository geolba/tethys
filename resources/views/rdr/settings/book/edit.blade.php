@extends('layouts.app')

@section('content')

	<h1 class="title">Edit Your Book</h1>

	<div class="col-md-4">
		
		<a href="{{ route('settings.book') }}" class="btn btn-danger">
			<span class="glyphicon glyphicon-chevron-left" ></span> BACK
		</a>
		
	</div>

		<div class="col-md-4" >
		
			{!! Form::model($book, ['method' => 'PATCH', 'route' => ['settings.book.update', $book->id]]) !!}

				@include('rdr/settings/book/_form', ['submitButtonText' => 'Edit Book', 'bookLabel' => 'Edit Book.'])

			{!! Form::close() !!}

		</div>
		
		
		

@stop