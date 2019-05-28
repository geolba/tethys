@extends('settings.layouts.app') 
@section('content')
<div class="header">
    <h3 class="header-title">
        <i class="fa fa-share"></i> Publish reviewed dataset
    </h3>
</div>

<div class="pure-g  box-content">

    <div class="pure-u-1 pure-u-md-1">
        <div>
            <a href="{{ route('publish.workflow.editor.index') }}" class="pure-button button-small">
                <i class="fa fa-chevron-left"></i>
                <span>BACK</span>
            </a>
        </div>
        <div id="app1">

            {!! Form::model($dataset, [ 'method' => 'POST', 'route' => ['publish.workflow.editor.publishUpdate', $dataset->id],
            'id' => 'publishForm', 'class' => 'pure-form', 'enctype' => 'multipart/form-data', 'v-on:submit.prevent' => 'checkForm'])
            !!}
            <fieldset id="fieldset-General">

               
                <h3>Selected Dataset</h3>
                <table style="margin-left: 2em">
                    <tbody>
                        <tr>
                            <td style="vertical-align: top; padding-right: 1em">{{ $dataset->id }}</td>
                            <td>
                                @foreach($dataset->titles as $title)
                                <div class="title" style="font-weight: bold">
                                    {{ $title->value }}
                                </div>
                                @endforeach
                                <div class="authors">
                                        @foreach($dataset->persons as $author)
                                       
                                            {{ $author->full_name }}
                                        
                                        @endforeach                            
                                </div>
                                <input type="hidden" name="selected[]" value="49">
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="instruction">
                        Are you sure you want to publish the selected dataset?  
                    </div>
                    <table>
                            <tbody><tr>
                                <td>&nbsp;</td>
                                <td>
                                        <button type="submit" class="pure-button">
                                            <i class="fa fa-share"></i>
                                            <span>Set published</span>
                                        </button>
                                    {{-- <input type="submit" name="sureno" value="No"> --}}
                                </td>
                            </tr>
                        </tbody>
                    </table>

            </fieldset>

            <br />
            {{-- <div class="pure-controls">
                <button type="submit" class="pure-button">
                    <i class="fa fa-share"></i>
                    <span>Set published</span>
                </button>
            </div> --}}

            {!! Form::close() !!}
        </div>
    </div>

</div>


@stop 
@section('after-scripts') 
@stop