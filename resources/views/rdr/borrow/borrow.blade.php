@extends('layouts.app')

@section('content')

	<h1 class="title">Borrow</h1>

	<div class="col-md-8">
			
			{!! Form::open(['route' => 'borrow.post']) !!}
				
				<div class="form-group"> 
					{!! Form::label('student_id', 'Student..') !!}

					<!--third parameter is default option
					name of select is 'student_id'
					second parameter is the associative array -->
					{!! Form::select('person_id', $persons, null, ['id' => 'person_id', 'class' => 'form-control']) !!}
				</div>
				
				<div class="form-group"> 
					{!! Form::label('project', 'Project..') !!}
					<!--{!! Form::select('project', $categories, null, ['id' => 'project', 'class' => 'form-control']) !!} -->
					<select id="project" name="project" class="form-control" >
					
						@foreach($categories as $project)
							<option value="{{ $project->id }}" >{{ $project->name }}</option>
						@endforeach
					
					</select>

				</div>				

				<div class="form-group"> 
					{!! Form::label('book_id', 'Book..') !!}
					<!--{!! Form::select('book_id', [], null, ['id' => 'book_id', 'class' => 'form-control']) !!} -->
					<select id="book_id" name="book_id" class="form-control">
						<option>-- Choice The Book --</option>
					</select>		
				</div>

				<div class="form-group"> 
					{!! Form::submit('Borrow', ['class' => 'btn btn-primary form-control']) !!}
				</div>


			{!! Form::close() !!}
			

	</div>
@stop

@section('scripts')

	<script type="text/javascript">
		$('#project').on('change', function(e){
			console.log(e);
			var project_id = e.target.value;
			//ajax
			$.get('api/dropdown/borrow/' + project_id, function(data){
				
				//if success data				
				$('#book_id').empty();
				$.each(data, function(index, booksObj){

					$('#book_id').append('<option value="' +booksObj.id+ '">' +booksObj.title+ '</option>');

				});

			}); 

		});
	</script>

@stop