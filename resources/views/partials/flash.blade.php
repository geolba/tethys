@if(Session::has('flash_message'))

	<div class="alert summary-success">		
		{{ session('flash_message') }}
	</div>


@endif