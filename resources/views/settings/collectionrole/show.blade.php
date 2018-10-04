@extends('settings.layouts.app')

@section('content')
<div class="header">
	<h3 class="header-title">
		<i class="fa fa-archive"></i> Top Level Collections
	</h3>
</div>	
    

<div class="pure-g box-content">

	<div class="pure-u-1 pure-u-md-3-3"> 
		{{-- <a href="{{ route('settings.collection.create') }}" class="pure-button button-small is-primary">
			<i class="fa fa-plus-circle"></i>ADD NEW COLLECTION
		</a>		 --}}
		<br><br>

		<table class="pure-table pure-table-horizontal">

			<thead>					
					<th>Collection (number of child collections)</th>
					<th>Collection Role</th>
					<th>Document id's</th>
					<th>Options</th>
			</thead>

			<tbody>
				
				@foreach($collections as $collection)
				
					<tr>					
						<td>							
							<a class="show" href="{{ route('settings.collection.show', $collection) }}">
								{{-- <span">{{ $collection->name }}</span> --}}
								<span">{{ $collection->name .' ('. $collection->children->count() .')' }}</span>
							</a>
						</td>
						<td>{{ $collection->collectionrole->name }}</td>
						<td> 
						@foreach ($collection->documents as $document)
							<p>document id: {{ $document->id }}</p>
						@endforeach
						
						</td>		
						<td>
							<a class="edit" href="{{ route('settings.collection.edit', $collection->id) }}"><span aria-hidden="true">edit collection</span></a> 
							{{-- <a class="delete" href="{{ route('settings.collection.delete', $collection->id) }}"><span aria-hidden="true"></span></a> --}}
						</td>
						</td>
					</tr>

				@endforeach	

			</tbody>
			
		</table>
	</div>

	<div class="pure-u-1 pure-u-md-3-3">
		{{ $collections->links('vendor.pagination.default') }}
	</div>

</div>
@stop