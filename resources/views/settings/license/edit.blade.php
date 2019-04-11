@extends('settings.layouts.app')

@section('content')
<div class="header">
    <h3 class="header-title">
            Edit Your License
    </h3>
</div>

<div class="pure-g  box-content">

    <div class="pure-u-1 pure-u-md-1">
        <div>
            <a href="{{ route('settings.license') }}" class="pure-button button-small">
                <i class="fa fa-chevron-left"></i>
                <span>BACK</span>
            </a>
        </div>   
        <div>
            {!! Form::model($license, ['method' => 'PATCH', 'route' => ['settings.license.update', $license->id], 'class' => 'pure-form', 'enctype' => 'multipart/form-data']) !!}
                @include('settings/license/_form', ['submitButtonText' => 'Edit License', 'daysLabel' => 'Days..', 'finesLabel' => 'Licenses..'])              
            {!! Form::close() !!}
        </div>      
    </div>

</div>
@stop

@section("after-scripts")
    <script type="text/javascript">
        // Backend.Pages.init();
    </script>
     <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.10/summernote-lite.css" rel="stylesheet">
     <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.10/summernote-lite.js"></script>
     {{-- <script src="https://cloud.tinymce.com/5/tinymce.min.js"></script> --}}
     <script>
        $(document).ready(function() {
            $('#desc_markup').summernote();
            // tinymce.init({ 
            //     selector: "#desc_markup",
            //     plugins: "code",
            //     // toolbar: "code",
            //     menubar: "tools"
            // });
        });
      </script>
@endsection