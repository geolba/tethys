<div class="form-group"> 
	{!! Form::label('title', 'Title..') !!}
	{!! Form::text('title', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group"> 
	{!! Form::label('author', 'Author..') !!}
	{!! Form::text('author', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group"> 
	{!! Form::label('year', 'Year..') !!}
	{!! Form::select('year', $years, null, ['id' => 'year', 'class' => 'form-control', 'placeholder' => 'None']) !!}
</div>

<div class="form-group"> 
	{!! Form::label('stock', 'Stock..') !!}
	{!! Form::text('stock', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group"> 
	{!! Form::label('project_id', 'Project..') !!}
	{!! Form::select('project_id', $categories, null, ['id' => 'project_id', 'class' => 'form-control']) !!}
</div>

{{-- <div class="form-group"> 
	{!! Form::label('shelf_id', 'Shelf..') !!}
	{!! Form::select('shelf_id', $shelves, null, ['id' => 'shelf_id', 'class' => 'form-control']) !!}
</div>  --}}

<div class="form-group"> 
	{!! Form::submit($submitButtonText, ['class' => 'btn btn-primary form-control']) !!}
</div>

@include('errors._errors')