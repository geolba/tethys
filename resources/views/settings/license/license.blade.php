@extends('layouts.app')

@section('content')
	
    <div class="title">		
        <h2><i class="fa fa-file"></i> LICENSES</h2>
    </div>		

	<div class="col-md-8">
		

		<table class="pure-table pure-table-horizontal">

			<thead>				
				<th>Licence</th>
				<th></th>
			</thead>

			<tbody>
				
				@foreach($licenses as $license)
				
					<tr>											
						<td>{{ $license->name_long }}</td>						
								
						<td>
							<a class="edit" href="{{ route('settings.license.edit', $license->id) }}">
								<span>edit</span>
							</a>
						</td>
					</tr>

				@endforeach	

			</tbody>
			
		</table>
		
			

	</div>

@stop