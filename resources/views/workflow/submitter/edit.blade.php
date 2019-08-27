@extends('settings.layouts.app')

@section('content')
<div class="header">
    <h3 class="header-title">
        <span>Edit Dataset</span>
    </h3>
</div>
    
<div class="pure-g box-content">

    <div class="pure-u-1 pure-u-md-3-3">        
        <div>
            <a href="{{ route('publish.workflow.submit.index') }}" class="pure-button button-small">
                <i class="fa fa-chevron-left"></i>
                <span>BACK</span>
            </a>              
        </div>
        <div>
            {!! Form::model($dataset, ['method' => 'POST', 'route' => ['publish.workflow.submit.update', $dataset->id], 'class' => 'pure-form', 'enctype' => 'multipart/form-data' ]) !!}
                @include('workflow/submitter/_form', ['submitButtonText' => 'Edit Dataset', 'bookLabel' => 'Edit Dataset.'])
                @include('errors._errors')
            {!! Form::close() !!}
        </div>           
    </div>

</div>
@stop