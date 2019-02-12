@extends('settings.layouts.app')

@section('content')		
<div class="header">
	<h3 class="header-title">
		<i class="fa fa fa-database"></i> Datasets
	</h3>
</div>		
	
    
<div class="pure-g box-content">


	<div class="sidebar-simplesearch pure-u-1">
		{!! Form::open(array('route' => 'settings.document','method' => 'GET', 'class'=>'pure-form')) !!}
				<p>              
					{!! Form::text('filter', (isset($filter))  ? $filter : '', array('class'=>'pure-u-1 pure-u-md-1-2', 'placeholder'=>'filter for the title...')) !!}
		
					<div class="select pure-u-1 pure-u-md-1-2">
					{!! Form::select('state', Config::get('enums.server_states'), (isset($state))  ? $state : 'published', ['class' => 'pure-u-1', 'onchange' => "this.form.submit()"]) !!}
					</div>

				<div class="pure-u-1 pure-u-md-1-2">
				{{-- <button type="submit">
					<i class="fa fa-search"></i>
				</button>  --}}
				</div>
			</p>          
				<input type="hidden" name="searchtype" id="searchtype" value="simple" />         
	</div>

	<div class="pure-u-1">
		<div class="panel-heading">Page {{ $documents->currentPage() }} of {{ $documents->lastPage() }}</div>
		<table class="pure-table pure-table-horizontal">
			<thead>	
				<tr>
					<th>id</th>
					<th>Document Type</th>
					<th>Project</th>
					<th>Titles</th>
					<th>Options</th>
				</tr>
			</thead>
			<tbody>						
				@foreach($documents as $document)
				
					<tr>
					
						<td>{{ $document->id }}</td>
						<td>{{ $document->type }}</td>
						@if($document->hasProject())
							<td>{{ $document->project->name }}</td>
						@else
							<td>--</td>
						@endif

						<td> 
						@foreach ($document->titles as $title)
							<p>title: {{ $title->value }}</p>
						@endforeach
						
						</td>				

						{{-- <td> 
						@foreach ($document->collections as $collection)
							<p>in collection: {{ $collection->name }}</p>
						@endforeach --}}
						
						</td>				
						<td>
							<a class="view" href="{{ route('settings.document.show', $document->id) }}"><span>&nbsp;</span></a>
							<a class="edit" href="{{ route('settings.document.edit', $document->id) }}"><span>&nbsp;</span></a> 
							<!--<a class="delete" href="{{ route('settings.book.delete', $document->id) }}"><span>&nbsp;</span></a>-->
						</td>

					</tr>

				@endforeach	

			</tbody>				
		</table>	
	</div>

	{!! Form::close() !!} 
	
	<div class="pure-u-1">
		{{  $documents
			->appends(Input::except('page'))
			->links('vendor.pagination.default') }}
	</div>



</div>

@stop