@extends ('settings.layouts.app') 
@section ('title', trans('labels.backend.pages.management') . ' | ' . trans('labels.backend.pages.edit'))
{{-- 
@section('page-header')
<h1>
    {{ trans('labels.backend.pages.management') }}
    <small>{{ trans('labels.backend.pages.edit') }}</small>
</h1>
@endsection
 --}} 
@section('content') 
{!! Form::model($page, ['method' => 'PATCH', 'route' => ['settings.page.update', $page->id],
'class' => 'pure-form pure-form-aligned', 'enctype' => 'multipart/form-data']) !!}

<div class="box box-info">

    <div class="header">
        <h3 class="header-title">{{ trans('labels.backend.pages.edit') }}
        </h3>
        <div class="box-tools pull-right">
            {{--
    @include('backend.pages.partials.pages-header-buttons') --}}
        </div>
    </div>

    <div class="box-body box-content">

        <!-- Nav tabs -->
        <input type="radio" id="english-option" name="nav-tab" type="hidden">
        <input type="radio" id="german-option" name="nav-tab" type="hidden">   
       
        <div class="tabs is-boxed">
            <ul>
                <li class="english-link">
                    <label for="english-option"><a class="remove-check">EN</a></label>
                </li>
                <li class="german-link">
                    <label for="german-option"><a class="remove-check">DE</a></label>
                </li>
            </ul>
    	</div>

        <!-- Tab panes -->
        <div class="tab-content">

            <div class="tab-pane content-pic" id="english-form">
                <div class="pure-control-group">
                    {!! Form::label('en_title', trans('validation.attributes.backend.pages.title'), ['class' => 'col-lg-2 control-label required'])
                    !!}
                    <div class="col-lg-10">
                        {!! Form::text('en_title', $page->translate('en')->title, ['class' => 'form-control box-size', 'placeholder' => trans('validation.attributes.backend.pages.title'),
                        'required' => 'required', 'readonly' => 'true']) !!}
                    </div>
                </div>
            
                <div class="pure-control-group">
                    {!! Form::label('en_description', trans('validation.attributes.backend.pages.description'), ['class' => 'col-lg-2 control-label
                    required']) !!}
                    <div class="col-lg-10">
                        {!! Form::textarea('en_description', $page->translate('en')->description, ['class' => 'form-control description', 'placeholder' => trans('validation.attributes.backend.pages.description')])
                        !!}
                    </div>
                </div>
            </div>

            <div class="tab-pane content-music" id="german-form">
                <div class="pure-control-group">
                    {!! Form::label('de_title', trans('validation.attributes.backend.pages.title'), ['class' => 'col-lg-2 control-label required'])
                    !!}
                    <div class="col-lg-10">
                        {!! Form::text('de_title', $page->translate('de')->title, ['class' => 'form-control box-size', 'placeholder' => trans('validation.attributes.backend.pages.title'),
                        'required' => 'required', 'readonly' => 'true']) !!}
                    </div>
                </div>            
                <div class="pure-control-group">
                    {!! Form::label('de_description', trans('validation.attributes.backend.pages.description'), ['class' => 'col-lg-2 control-label
                    required']) !!}
                    <div class="col-lg-10">
                        {!! Form::textarea('de_description', $page->translate('de')->description, ['class' => 'form-control description', 'placeholder' => trans('validation.attributes.backend.pages.description')])
                        !!}
                    </div>
                </div>
            </div>
        </div>

       <div class="pure-control-group">
            {!! Form::label('status', trans('validation.attributes.backend.pages.is_active'), ['class' => 'col-lg-2 control-label']) !!}   
            <div class="control-group">
                <label class="control control--checkbox">
                    {!! Form::checkbox('status', 1, ($page->status == 1) ? true : false ) !!}                                            
                </label>
            </div>           
        </div>  

        <div class="pure-controls">
            {{ link_to_route('settings.page.index', trans('buttons.general.cancel'), [], ['class' => 'pure-button button-small is-warning']) }} 
            {!! Form::submit(trans('buttons.general.crud.update'), ['class' => 'pure-button button-small is-primary']) !!}           
        </div>
      
    </div>
    <!-- /.box-body -->
</div>


@include('errors._errors') 
{!! Form::close() !!}
@endsection
 
@section('styles')
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.2/css/bulma.min.css"/> --}}
<style type="text/css">

input[type="radio"], .tab-pane { display: none; }

#english-option:checked ~ .tab-content > .content-pic,
#german-option:checked ~ .tab-content > .content-music,
#video:checked ~ .tab-content > .content-video,
#doc:checked ~ .tab-content > .content-doc {
  display: block;
}

#english-option:checked ~ .tabs .english-link a,
#german-option:checked ~ .tabs .german-link a {
    /* border-bottom-color: #3273dc;
    color: #3273dc; */
    background-color: #3273dc;
    border-color: #3273dc;
    color: #fff;
    z-index: 1;
   
}


</style>

@endsection 

@section("after-scripts")
<script type="text/javascript">
    // Backend.Pages.init();

</script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.10/summernote-lite.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.10/summernote-lite.js"></script>
<script>
    $(document).ready(function() {
            // $('#description').summernote();
            $('.description').summernote({
                height: "300px",
                callbacks: {
                    // onImageUpload: function(files, editor, welEditable) {
                    //     app.sendFile(files[0], editor, welEditable);
                    // }
                }
            });
            document.getElementById("english-option").checked = true;
            
            // var $englishForm = $('#english-form');
            // var $germanForm = $('#german-form');
            // var $englishLink = $('#english-link');
            // var $germanLink = $('#german-link');
            // $englishLink.click(function() {
            //     $englishLink.toggleClass('bg-aqua-active');
            //     $englishForm.toggleClass('hidden');
            //     $germanLink.toggleClass('bg-aqua-active');
            //     $germanForm.toggleClass('hidden');
            // });
            // $germanLink.click(function() {
            //     $englishLink.toggleClass('bg-aqua-active');
            //     $englishForm.toggleClass('hidden');
            //     $germanLink.toggleClass('bg-aqua-active');
            //     $germanForm.toggleClass('hidden');
            // });

        });

</script>
@endsection