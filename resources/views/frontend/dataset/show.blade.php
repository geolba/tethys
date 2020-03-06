@extends('layouts.app') 
@section('content')


<section class="normal dataset u-full-width">
<div id="app"></div>
</section>



@stop

@section('after-scripts')
<script>
    window.Laravel = <?php echo json_encode([
            'csrf_token' => csrf_token(),
            'id' => $id,           
        ]); ?>        
</script>
<script src="{{  asset('js/landingpage/main.js') }}"></script>
@stop