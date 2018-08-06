@extends('layouts.app')

@section('title', Lang::get('resources.home_about_pagetitle'))

@section('content')

<div class="pure-g">

    <div class="pure-u-1 pure-u-md-2-3">
        <div class="content">           
            <h1>
                @lang('resources.home_about_title')
            </h1>

            <div>
                <p>
                    @lang('resources.home_about_content')
                </p>
            </div>
        </div>
    </div>

</div>
@endsection