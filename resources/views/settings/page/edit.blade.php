@extends ('settings.layouts.app')

@section ('title', trans('labels.backend.pages.management') . ' | ' . trans('labels.backend.pages.edit'))

{{-- @section('page-header')
    <h1>
        {{ trans('labels.backend.pages.management') }}
        <small>{{ trans('labels.backend.pages.edit') }}</small>
    </h1>
@endsection --}}

@section('content')

    {{ Form::model($page, ['method' => 'PATCH', 'route' => ['settings.page.update', $page], 'class' => 'pure-form pure-form-aligned', 'id' => 'edit-role', 'enctype' => 'multipart/form-data']) }}

        <div class="box box-info">          

            <div class="header">
                <h3 class="header-title">{{ trans('labels.backend.pages.edit') }}
                </h3>
                <div class="box-tools pull-right">
                    {{-- @include('backend.pages.partials.pages-header-buttons') --}}
                </div>
            </div>	

            <div class="box-body box-content">

                <div class="pure-control-group">
                    {{ Form::label('title', trans('validation.attributes.backend.pages.title'), ['class' => 'col-lg-2 control-label required']) }}
                    <div class="col-lg-10">
                        {{ Form::text('title', null, ['class' => 'form-control box-size', 'placeholder' => trans('validation.attributes.backend.pages.title'), 'required' => 'required']) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="pure-control-group">
                    {{ Form::label('description', trans('validation.attributes.backend.pages.description'), ['class' => 'col-lg-2 control-label required']) }}
                    <div class="col-lg-10">
                        {{ Form::textarea('description', null,['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.pages.description')]) }}
                    </div><!--col-lg-3-->
                </div><!--form control-->

                <div class="pure-control-group">
                    {{ Form::label('cannonical_link', trans('validation.attributes.backend.pages.cannonical_link'), ['class' => 'col-lg-2 control-label']) }}
                    <div class="col-lg-10">
                        {{ Form::text('cannonical_link', null, ['class' => 'form-control box-size', 'placeholder' => trans('validation.attributes.backend.pages.cannonical_link')]) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="pure-control-group">
                    {{ Form::label('seo_title', trans('validation.attributes.backend.pages.seo_title'), ['class' => 'col-lg-2 control-label']) }}
                    <div class="col-lg-10">
                        {{ Form::text('seo_title', null, ['class' => 'form-control box-size', 'placeholder' => trans('validation.attributes.backend.pages.seo_title')]) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="pure-control-group">
                    {{ Form::label('seo_keyword', trans('validation.attributes.backend.pages.seo_keyword'), ['class' => 'col-lg-2 control-label']) }}
                    <div class="col-lg-10">
                        {{ Form::text('seo_keyword', null, ['class' => 'form-control box-size', 'placeholder' => trans('validation.attributes.backend.pages.seo_keyword')]) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="pure-control-group">
                    {{ Form::label('seo_description', trans('validation.attributes.backend.pages.seo_description'), ['class' => 'col-lg-2 control-label']) }}
                    <div class="col-lg-10">
                        {{ Form::textarea('seo_description', null,['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.pages.seo_description')]) }}
                    </div><!--col-lg-3-->
                </div><!--form control-->

                <div class="pure-control-group">
                    {{ Form::label('status', trans('validation.attributes.backend.pages.is_active'), ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        <div class="control-group">
                            <label class="control control--checkbox">
                                {{ Form::checkbox('status', 1, ($page->status == 1) ? true : false ) }}
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                    </div><!--col-lg-3-->
                </div><!--form control-->

                <div class="pure-controls">
                    {{ link_to_route('settings.page.index', trans('buttons.general.cancel'), [], ['class' => 'pure-button button-small is-warning']) }}
                    {{ Form::submit(trans('buttons.general.crud.update'), ['class' => 'pure-button button-small is-primary']) }}
                    <div class="clearfix"></div>
                </div>

            </div><!-- /.box-body -->
        </div><!--box-->
    {{ Form::close() }}
@endsection

@section("after-scripts")
    <script type="text/javascript">
        // Backend.Pages.init();
    </script>
@endsection