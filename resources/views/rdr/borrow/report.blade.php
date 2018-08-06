@extends('layouts.app')

@section('content')

	<h1 class="title">Bericht</h1>

	<div class="col-md-12">
			
		<table class="table table-striped table-bordered">

			<thead>
					
					<th>Person</th>
					<th>Book</th>
					<th>Borrowed At</th>					
					<th colspan="2"><center>What You Gonna Do</center></th>

			</thead>

			<tbody>
				
				@foreach($transactions as $transaction)
					
					<tr>
					
						<td>{{ $transaction->student->last_name }}</td>
						<td>{{ $transaction->book->title }}</td>
						<td>{{ date('d-M-y', $transaction->borrowed_at) }}</td>					
						<td><a href="{{ route('borrow.pengembalian', $transaction->id) }}"><span class="glyphicon glyphicon-resize-small" aria-hidden="true"></span>&nbsp; return</a></td>
						<td><a href="{{ route('borrow.perpanjang', $transaction->id) }}"><span class="glyphicon glyphicon-resize-full" aria-hidden="true"></span>&nbsp; extend borrow time</a></td>

					</tr>

				@endforeach	

			</tbody>
			
		</table>

	</div>
@stop