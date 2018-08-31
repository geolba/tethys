@extends('settings.layouts.app')

@section('title', 'Publish')

@section('content')
<div class="header">
    <h3 class="header-title">
        <i class="fa fa-upload"></i> add New Dataset - Step 32
    </h3>
</div>

<div class="box-content">

    <form action={{ route('dataset.store') }} method="post" class="pure-form" >
        {{ csrf_field() }}
        <table class="table">
        <tr>
                <td>Dataset Type:</td>
                <td><strong>{{ $dataset['Type'] }}</strong></td>
            </tr>
            <tr>
                <td>Creating Corporation:</td>
                <td><strong>{{ $dataset['CreatingCorporation'] or '' }}</strong></td>
            </tr>
            <tr>
                <td>Embargo Date:</td>
                <td><strong>{{ $dataset['EmbargoDate'] or '' }}</strong></td>
            </tr>

            <tr>
                <td>Main Title:</td>
                @if(isset($dataset['TitleMain']))
                    <td><strong>{{ $dataset['TitleMain']['Value'] }} </strong></td>               
                @endif
            </tr>

            <tr>
                <td>Main Abstract:</td>
                @if(isset($dataset['TitleAbstract']))             
                    <td><strong>{{ $dataset['TitleAbstract']['Value'] }} </strong></td>               
                @endif
            </tr>

            <tr>
                <td>Belongs To Bibliography:</td>
                <td><strong>{{ isset($dataset['BelongsToBibliography']) ? 'Yes' : 'No' }}</strong></td>
            </tr>
            <tr>
                    <td>Product Image:</td>
                    <td><strong><img alt="Product Image" src="/storage/files/{{$dataset['DatasetFile']}}"/></strong></td>
                </tr>
            
            {{-- <tr>
                <td>Product Image:</td>
                <td><strong><img alt="Product Image" src="/storage/productimg/{{$product->productImg}}"/></strong></td>
            </tr>  --}}
        </table>
        <div class="pure-controls">       
            <a type="button" href="{{ route('dataset.create1') }}" class="pure-button button-small is-warning">Back to Step 1</a>
            <a type="button" href={{ route('dataset.create2') }} class="pure-button button-small is-warning">Back to Step 2</a>
            <button type="submit" class="pure-button button-small">
                <i class="fa fa-save"></i>
                <span>Create Dataset</span>
            </button>
        </div>  
    </form>

</div>

@include('errors._errors')

@stop