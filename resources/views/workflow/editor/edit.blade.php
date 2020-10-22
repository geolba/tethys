@extends('settings.layouts.app')

@section('content')
<div class="header">
    <h3 class="header-title">
        <span>Correct Dataset</span>
    </h3>
</div>

@if (count($errors) > 0)
  <div class="alert alert-danger">
    <strong>Whoops!</strong> There were some problems with your input.<br><br>
    <ul>
       @foreach ($errors->all() as $error)
         <li>{{ $error }}</li>
       @endforeach
    </ul>
  </div>
@endif
    
<div class="pure-g box-content">

    <div class="pure-u-1 pure-u-md-3-3">        
        <div>
            <a href="{{ route('publish.workflow.editor.index') }}" class="pure-button button-small">
                <i class="fa fa-chevron-left"></i>
                <span>BACK</span>
            </a>              
        </div>
        <div id="app1">
            {!! Form::model($dataset, ['method' => 'POST', 'route' => ['publish.workflow.editor.update', $dataset->id], 'class' => 'pure-form', 'enctype' => 'multipart/form-data' ]) !!}
                @include('workflow/editor/_form', ['submitButtonText' => 'Edit Dataset', 'bookLabel' => 'Edit Dataset.'])
                {{-- @include('errors._errors') --}}

                <!-- client errors -->
                <div v-if="errors.items.length > 0">
                    <b>Please correct the following error(s):</b>
                    <ul class="alert validation-summary-errors">
                        <li style="margin-left:5px;" v-for="error in errors.items">@{{ error.msg }}</li>
                    </ul>
                </div>

            {!! Form::close() !!}
        </div>           
    </div>

</div>
@stop

@section('after-scripts')
<script>
    window.Laravel = <?php echo json_encode([
            'csrf_token' => csrf_token(),
            'form' => $dataset,
            'titleTypes' => $titleTypes,
            'descriptionTypes' => $descriptionTypes,
            'contributorTypes'=> $contributorTypes,
            'nameTypes' => $nameTypes,
            'languages' => $languages,
            'messages' => $messages,
            'projects' => $projects,
            'licenses'  => $licenses,
            'checkeds' => $checkeds,
            'referenceTypes' => $referenceTypes,
            'relationTypes' => $relationTypes
        ]); ?>        
</script>

<script type="text/javascript" src="{{  asset('backend/publish/mainEditDataset.js') }}"></script>
@stop