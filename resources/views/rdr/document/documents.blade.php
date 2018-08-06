@extends('layouts.app')

@section('content')

	<h1 class="title">Documents</h1>

	<br><br>

	<table class="pure-table pure-table-horizontal">

		<thead>
			<th>id</th>
			<th>document type</th>
			<!-- <th>Category</th>
			<th>Shelf</th> -->

		</thead>

		<tbody>
			
			@foreach($documents as $document)
			
				<tr>					
					<td>{{ $document->id }}</td>
					<td>{{ $document->type }}</td>
					<!-- <td>
						if($book->stock > 0)
							Available
						elseif($book->stock == 0)
							-
						endif
					</td>				 -->	
				</tr>

			@endforeach	

		</tbody>
		
	</table>
		

	

@stop