@extends('settings.layouts.app') 
@section('content')
<div class="header">
    <h3 class="header-title">
        Edit Collection "{{ $collection->name }}"
    </h3>
</div>

<div class="box-content">
    @if($collection->parent()->exists())
    <a href="{{ route('settings.collection.show',  $collection->parent) }}" class="pure-button button-small">
        <i class="fa fa-chevron-left"></i>
        <span>BACK</span>
    </a>
    @else
    <a href="{{ route('settings.collectionrole.show', $collection->collectionrole) }}" class="pure-button button-small">
        <i class="fa fa-chevron-left"></i>
        <span>BACK</span>
    </a> 
    @endif      
  
    <div>
        {!! Form::model($collection, ['method' => 'PATCH', 'route' => ['settings.collection.update', $collection->id], 'class' =>
        'pure-form pure-form-aligned']) !!}
        <fieldset>
            <div class="pure-control-group">
                {{ Form::label('name', 'collection name') }} 
                {{ Form::text('name', null, ['class' => 'form-control']) }}
                <em>*</em>
            </div>
            <div class="pure-control-group">
                {{ Form::label('number', 'number') }} 
                {{ Form::text('number', null, ['class' => 'form-control']) }}
                <small id="numberHelp" class="pure-form-message-inline">number is optional</small>
            </div>

            <div class="pure-control-group pure-div">
                {!! Form::label('role_id', 'Collection Role..') !!} 
                {!! Form::select('role_id', $collectionroles, null, ['id' => 'role_id', 'placeholder' => '-- no role selected --']) !!}

                <em>*</em>
            </div>

            {{ Form::submit("Submit", ['class' => 'pure-button button-small']) }}
        </fieldset>
    @include('errors._errors') 
    {!! Form::close() !!}
    </div>
</div>
@endsection