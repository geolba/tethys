@extends('settings.layouts.app') 
@section('content')
<div class="header">
	<h3 class="header-title">
		<i class="fa fa-archive"></i> Collection Roles
	</h3>
</div>


<div class="pure-g box-content">

	<div class="pure-u-1 pure-u-md-3-3">
		{{-- <a href="{{ route('settings.collectionrole.create') }}" class="pure-button button-small is-primary">
			<i class="fa fa-plus-circle"></i>ADD NEW COLLECTION ROLE
		</a> --}}
		<br><br>

		<table class="pure-table pure-table-horizontal">

			<thead>
				<th>Collection</th>
				<th>Count Of Nested Collections</th>
				<th>Options</th>
				<th>Visible</th>
			</thead>

			<tbody>
				@foreach($collectionroles as $collectionrole)
				<tr>
					<td>
						<a class="show" href="{{ route('settings.collectionrole.show', $collectionrole) }}">
							<span">{{ $collectionrole->name .' ('. $collectionrole->collections->count() .')' }}</span>
						</a>
					</td>
					<td>
						{{-- @foreach ($collection->documents as $document)
						<p>document id: {{ $document->id }}</p>
						@endforeach --}}
						{{ $collectionrole->collections->count() }}
						{{-- <a href="" class="pure-button button-small is-success">Show Collections</a>  --}}

					</td>
					<td>
						<a class="edit" href="{{ route('settings.collectionrole.edit', $collectionrole->id) }}"><span aria-hidden="true">Edit</span></a>						
						{{-- <a class="delete" href="{{ route('settings.collection.delete', $collection->id) }}"><span aria-hidden="true"></span></a> --}}
					</td>
					<td>
						@if($collectionrole->visible == 1)
						<a href="{{ route('settings.collectionrole.hide', $collectionrole->id) }}" class="pure-button button-small is-warning">Hide</a>						
						@else
						<a href="{{ route('settings.collectionrole.up', $collectionrole->id) }}" class="pure-button button-small is-success">Unhide</a> 
						@endif
					</td>
				</tr>
				@endforeach
			</tbody>

		</table>
	</div>

	<div class="pure-u-1 pure-u-md-3-3">
		{{-- {{ $collectionroles->links('vendor.pagination.default') }} --}}
	</div>

</div>

@stop