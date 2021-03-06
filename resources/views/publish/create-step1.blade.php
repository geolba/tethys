@extends('settings.layouts.app')

@section('title', 'Publish')

@section('styles')
<style>
    .vue-tooltip.tooltip-custom {
        background-color: #00a9d8;
        z-index: 9999;
    }

    .vue-tooltip.tooltip-custom .tooltip-content {
        z-index: 9999;
    }
</style>
@stop

@section('content')
<div class="header">
    <h3 class="header-title">
        <i class="fas fa-plus-square"></i> Publish New Dataset
    </h3>
</div>

<div id="app" class="box-content" v-cloak>
    {{-- v-on:time-expire="handleTimeExpire" --}}
    @if (Auth::check())
    <vue-countdown v-on:time-expire="handleTimeExpire" :seconds="({{config('session.lifetime')}} * 60)"></vue-countdown>
    @endif
    {{--
    <form action={{ route( 'publish.dataset.store1') }} method="post" class="pure-form" enctype="multipart/form-data">
    --}}
    <main class="steps pure-form" enctype="multipart/form-data">
        {{ csrf_field() }}

        <div v-if="step === 0 && isInitial" data-vv-scope="step-0">

            <fieldset id="fieldset-language">
                <legend>Dataset Language</legend>
                <div class="pure-g">
                    <div class="pure-u-1 pure-u-md-1-2 pure-div">
                        <label for="language">
                            <span>
                                Language..
                                <i @click.prevent
                                    v-tooltip.click.manual="{ content: messages.dataset_language, class: 'tooltip-custom tooltip-other-custom' }"
                                    class="far fa-lg fa-question-circle"></i>
                            </span>
                        </label>
                        <div class="select  pure-u-23-24">
                            {!! Form::select('language', $languages, null, ['placeholder' => '[language]', 'v-model' =>
                            'dataset.language', "v-validate"
                            => "'required'", 'data-vv-scope' => 'step-0']) !!}
                        </div>
                        <small id="languageHelp" class="pure-form-message-inline">select dataset main language</small>
                    </div>
                </div>
            </fieldset>

            <br />
            <div class="pure-controls">
                <button @click.prevent="next('step-0')" class="pure-button button-small" :disabled="errors.any()">
                    <i class="fa fa-arrow-right"></i>
                    <span>Continue</span>
                </button>
            </div>
            <div v-if="errors.items.length > 0">
                <b>Please correct the following error(s):</b>
                <ul class="alert validation-summary-errors">
                    <li style="margin-left:5px;" v-for="error in errors.items">@{{ error.msg }}</li>
                </ul>
            </div>

        </div>

        <div v-if="step === 1 && isInitial" data-vv-scope="step-1">
            <h1>Step One: Mandatory Elements</h1>

            <fieldset class="fieldset-general">
                <legend>General</legend>
                <div class="description hint">
                    <p>Bitte wählen Sie einen Datensatztyp aus der Liste aus.</p>
                </div>
                <p></p>
                {{-- <div class="form-item">
                        <label for="documentType">Datensatztyp<span class="required" title="Dieses Feld muss ausgefüllt werden."> *</span></label>
                        <div class="select" style="width:300px" title="Bitte wählen Sie einen Datensatztyp aus der Liste aus.">
                            {!! Form::select('Type', Lang::get('doctypes'), null, ['id' => 'type', 'placeholder' => '-- select type --', 'v-model' =>
                            'dataset.type', "v-validate" => "'required'", 'data-vv-scope' => 'step-1']) !!}
                        </div>
                    </div> --}}
                <div class="pure-u-1 pure-u-md-1-2 pure-div">
                    <label for="documentType">Dataset Type<span class="required"
                            title="Dieses Feld muss ausgefüllt werden.">
                            * <i @click.prevent
                                v-tooltip.click="{ content: messages.dataset_type, class: 'tooltip-custom tooltip-other-custom' }"
                                class="far fa-lg fa-question-circle"></i>
                        </span>
                    </label>
                    <div class="select  pure-u-23-24" title="Bitte wählen Sie einen Datensatztyp aus der Liste aus.">
                        {!! Form::select('Type', Lang::get('doctypes'), null, ['id' => 'type', 'placeholder' => '--
                        select type --', 'v-model' =>
                        'dataset.type', "v-validate" => "'required'", 'data-vv-scope' => 'step-1']) !!}
                    </div>
                </div>


            </fieldset>

            <fieldset id="fieldset-titles">
                <legend>
                    Title(s)
                    {{-- <i  v-tooltip="{ content: messages.titles, class: 'tooltip-custom tooltip-other-custom' }" class="far fa-lg fa-question-circle"></i>  --}}
                </legend>
                <div class="pure-g">
                    <div class="pure-u-1 pure-u-md-1-2 pure-div">
                        <label for="TitleMain">
                            <span>
                                Main Title
                                <i @click.prevent
                                    v-tooltip.click="{ content: messages.main_title, class: 'tooltip-custom tooltip-other-custom' }"
                                    class="far fa-lg fa-question-circle"></i>
                            </span>
                        </label>
                        {!! Form::text('TitleMain[Value]', null, ['class' => 'pure-u-23-24', 'v-model'
                        => 'dataset.title_main.value', "v-validate" => "'required|min:4|max:255'", "data-vv-as" => "Main
                        Title", 'data-vv-scope' => 'step-1']) !!}
                    </div>
                    <div class="pure-u-1 pure-u-md-1-2 pure-div">
                        {!! Form::label('TitleLanguage', 'Title Language..') !!}
                        {{-- <div class="select pure-u-23-24">
                                {!! Form::select('TitleMain[Language]', $languages, null, ['placeholder' => '--no language--', 'v-model' => 'dataset.title_main.language',
                                "v-validate" => "'required'", "data-vv-as" => "Title Language", 'data-vv-scope' => 'step-1']) !!}
                            </div> --}}
                        {!! Form::text('TitleMain[Language]', null, ['class' => 'pure-u-23-24', 'v-model' =>
                        'dataset.title_main.language', 'readonly']) !!}
                    </div>
                </div>
                <div class="pure-u-1 pure-u-md-1-2 pure-div">
                    <label>
                        <span>
                            Add additional title(s) <i @click.prevent
                                v-tooltip.click="{ content: messages.additional_titles, class: 'tooltip-custom tooltip-other-custom' }"
                                class="far fa-lg fa-question-circle"></i>
                        </span>
                    </label>
                    <button class="pure-button button-small" @click.prevent="addTitle()">+</button>
                </div>
                <table class="pure-table pure-table-horizontal" v-if="dataset.titles.length">
                    <thead>
                        <tr>
                            <th style="width: 20px;">Title</th>
                            <th>Type</th>
                            <th>Language</th>
                            <th style="width: 130px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in dataset.titles">
                            <td>
                                <input name="Title" class="form-control" placeholder="[TITLE]" v-model="item.value"
                                    data-vv-as="Additional Title" v-validate="'required|min:4|max:255'"
                                    data-vv-scope="step-1" />
                            </td>
                            <td>
                                {!! Form::select('Title[Type]', $titleTypes, null,
                                ['placeholder' => '[titleType]', 'v-model' => 'item.type', "v-validate" => "'required'",
                                'data-vv-scope' => 'step-1']) !!}
                            </td>
                            <td>
                                {!! Form::select('Title[Language]', $languages, null,
                                ['placeholder' => '[language]', 'v-model' => 'item.language', "data-vv-as" =>
                                "Additional Title Language",
                                "v-validate" => "{required: true, translatedLanguage: [dataset.language, item.type]}",
                                'data-vv-scope' => 'step-1']) !!}
                            </td>
                            <td>
                                <button class="pure-button button-small is-warning" @click.prevent="removeTitle(index)">
                                    <i class="fa fa-trash"></i> </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </fieldset>

            <fieldset id="fieldset-description">
                <legend>Description</legend>
                <div class="pure-g">
                    <div class="pure-u-1 pure-u-md-1-2 pure-div">
                        <label for="TitleAbstract">
                            <span>
                                Main Abstract
                                <i @click.prevent
                                    v-tooltip.click="{ content: messages.main_abstract, class: 'tooltip-custom tooltip-other-custom' }"
                                    class="far fa-lg fa-question-circle"></i>
                            </span>
                        </label>
                        {{ Form::textarea('TitleAbstract[Value]', null, ['class' => 'pure-u-23-24',
                            'size' => '70x6', 'v-model' => 'dataset.abstract_main.value', "v-validate" => "'required|min:4|max:2500'",
                            "data-vv-as" => "Main Abstract", 'data-vv-scope' => 'step-1']) }}
                    </div>
                    <div class="pure-u-1 pure-u-md-1-2 pure-div">
                        {!! Form::label('AbstractLanguage', 'Abstract Language..') !!}
                        {{-- <div class="select pure-u-23-24">
                                {!! Form::select('TitleAbstract[Language]', $languages, null, ['placeholder' => '--no language--', 'v-model' => 'dataset.abstract_main.language',
                                "v-validate" => "'required'", "data-vv-as" => "Abstract Language", 'data-vv-scope' => 'step-1']) !!}                              
                            </div> --}}
                        {!! Form::text('TitleAbstract[Language]', null, ['class' => 'pure-u-23-24', 'v-model' =>
                        'dataset.abstract_main.language', 'readonly']) !!}
                    </div>
                    <div class="pure-u-1 pure-u-md-1-2 pure-div">
                        <label>
                            <span>
                                Add additional description(s) <i @click.prevent
                                    v-tooltip.click="{ content: messages.additional_descriptions, class: 'tooltip-custom tooltip-other-custom' }"
                                    class="far fa-lg fa-question-circle"></i>
                            </span>
                        </label>
                        <button class="pure-button button-small" @click.prevent="addDescription()">+</button>
                    </div>
                </div>
                <table class="pure-table pure-table-horizontal" v-if="dataset.abstracts.length">
                    <thead>
                        <tr>
                            <th style="width: 20px;">Description</th>
                            <th>Type</th>
                            <th>Language</th>
                            <th style="width: 130px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in dataset.abstracts">
                            <td>
                                <textarea rows="3" cols="40" name="Description[Value]" class="form-control"
                                    placeholder="[DESCRIPTION]" v-model="item.value" data-vv-as="Additional Description"
                                    v-validate="'required|min:4|max:2500'" data-vv-scope="step-1"></textarea>
                            </td>
                            <td>
                                {!! Form::select('Description[Type]', $descriptionTypes, null,
                                ['placeholder' => '[descriptionType]', 'v-model' => 'item.type', "v-validate" =>
                                "'required'", 'data-vv-scope' => 'step-1']) !!}
                            </td>
                            <td>
                                {!! Form::select('Description[Language]', $languages, null,
                                ['placeholder' => '[language]',
                                'v-model' => 'item.language',
                                "data-vv-as" => "Additional Description Language",
                                "v-validate" => "{required: true, translatedLanguage: [dataset.language, item.type]}",
                                'data-vv-scope' => 'step-1']) !!}
                            </td>
                            <td>
                                <button class="pure-button button-small is-warning"
                                    @click.prevent="removeDescription(index)"> <i class="fa fa-trash"></i> </button>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </fieldset>

            <fieldset id="fieldset-creator">
                <legend>
                    Creator
                    <i @click.prevent
                        v-tooltip.click="{ content: messages.creator, class: 'tooltip-custom tooltip-other-custom' }"
                        class="far fa-lg fa-question-circle"></i>
                </legend>
                <div class="pure-g">
                    <div class="pure-u-1 pure-u-md-1-2 pure-div">
                        <my-autocomplete title="searching active person table" v-on:person="onAddAuthor">
                        </my-autocomplete>
                        {{--
                            <my-autocomplete :items="[ 'Apple', 'Banana', 'Orange', 'Mango', 'Pear', 'Peach', 'Grape', 'Tangerine', 'Pineapple']"></my-autocomplete> --}}
                    </div>
                    {{-- <div class="pure-u-1 pure-u-md-1-2 pure-div">
                            <div class="pure-control-group checkboxlist">
                                <input name="persons" v-model="dataset.checkedAuthors" type="hidden" class="form-check-input" v-validate="'required'" data-vv-as="Creator" data-vv-scope="step-1">
                                <label v-for="(person, index) in dataset.persons" :for="person.id" class="pure-checkbox">                           
                                                        <input type="checkbox" name="persons" v-bind:value="person.id"  v-model="dataset.checkedAuthors"  class="form-check-input" data-vv-scope="step-1">
                                                        @{{ person.full_name }}
                    </label>
                    <br />
                </div>
        </div> --}}
</div>
<div class="pure-u-1 pure-u-md-1-2 pure-div">
    {!! Form::label('additionalCreators', 'Add additional creator(s) if creator is not in database') !!}
    <button class="pure-button button-small" @click.prevent="addNewAuthor()">+</button>
</div>
<input name="persons" v-model="dataset.persons" type="hidden" class="form-check-input" v-validate="'required'"
    data-vv-as="Creator" data-vv-scope="step-1">
<person-table name="persons" v-bind:messages="messages" v-bind:nametypes="nameTypes" v-bind:heading="'creator table'"
    v-bind:personlist="dataset.persons"></person-table>
</fieldset>

<fieldset id="fieldset-contributors">
    <legend>
        Contributor
        <i @click.prevent
            v-tooltip.click="{ content: messages.contributor, class: 'tooltip-custom tooltip-other-custom' }"
            class="far fa-lg fa-question-circle"></i>
    </legend>
    <div class="pure-g">
        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            <my-autocomplete title="searching active person table" @person="onAddContributor"></my-autocomplete>
        </div>
        {{-- <div class="pure-u-1 pure-u-md-1-2 pure-div">
                            <div class="pure-control-group checkboxlist">
                                <label v-for="(contributor, index) in dataset.contributors" :for="contributor.id" class="pure-checkbox">                           
                                                        <input type="checkbox" name="contributors" v-bind:value="contributor.id"  v-model="dataset.checkedContributors"  class="form-check-input" data-vv-scope="step-1">
                                                        @{{ contributor.full_name }}
        </label>
        <br />
        <span>Checked Contributors: @{{ dataset.checkedContributors }}</span>
    </div>
    </div> --}}
    </div>
    <div class="pure-u-1 pure-u-md-1-2 pure-div">
        {!! Form::label('additionalContributors', 'Add additional contributor(s) if contributor is not in database') !!}
        <button class="pure-button button-small" @click.prevent="addNewContributor()">+</button>
    </div>
    {{-- <h3>contributor table</h3> --}}
    <person-table name="contributors" v-bind:messages="messages" v-bind:nametypes="nameTypes" v-bind:contributortypes="contributorTypes" v-bind:heading="'contributor table'"
        v-bind:personlist="dataset.contributors"></person-table>
</fieldset>

<fieldset id="fieldset-publisher">
    <legend>Creating Corporation</legend>
    <div class="pure-u-1 pure-u-md-1-2 pure-div">
        <label for="CreatingCorporation">
            <span>
                Corporation Name
                <i @click.prevent
                    v-tooltip.click="{ content: messages.corporate_name, class: 'tooltip-custom tooltip-other-custom' }"
                    class="far fa-lg fa-question-circle"></i>
            </span>
        </label>
        {!! Form::text('CreatingCorporation', null, ['readonly', 'class' =>
        'pure-u-23-24', 'v-model' => 'dataset.creating_corporation', "v-validate" => "'required'", 'data-vv-scope' =>
        'step-1']) !!}
    </div>
</fieldset>

<div :class="{'form-group':true, 'has-error':errors.has('step-1.rights')}">
    <label for="rights">
        <input class="form-checkbox" name="rights" id="rights" type="checkbox" v-model="dataset.rights"
            v-validate="'required'" data-vv-scope="step-1">
        terms and conditions
    </label>

    <i class="fas fa-info-circle" @click="showModal" style="font-size:24px"></i>
    <Modal v-if="isModalVisible" @close="closeModal">
        <template slot="header">
            {!! trans('validation.attributes.backend.create-dataset.terms_and_conditions').' *' !!}
        </template>
        <template slot="body">
            Mit dem Setzen des Hakens bestätige ich hiermit
            <ol type="1">
                <li>
                die Data Policy von Tethys RDR sowie die Terms & Conditions von Tethys 
                gelesen und verstanden zu haben (<a href="{{ url('/docs/HandbuchTethys.pdf') }}" target=_blank>siehe hier</a>)
                </li>
                <li>das Einverständnis aller Co-Autoren über die bevorstehende Datenpublikation schriftlich eingeholt zu haben</li>
                <li>sowohl mit der Data Policy als auch mit den Terms & Conditions einverstanden zu sein</li>
            </ol> 
        </template>
    </Modal>

    <br />
    <i v-show="errors.has('step-1.rights')" class="fas fa-exclamation-circle"></i>
    <span v-show="errors.has('step-1.rights')" class="text-danger">@{{ errors.first('step-1.rights') }}</span>


    <span class="help-block">You must agree to continue</span>
</div>

<br />
<div class="pure-controls">
    <button @click.prevent="prev()" class="pure-button button-small">
        <i class="fa fa-arrow-left"></i>
        <span>Back</span>
    </button>
    <button @click.prevent="next('step-1')" class="pure-button button-small" :disabled="errors.any()">
        <i class="fa fa-arrow-right"></i>
        <span>Continue</span>
    </button>
</div>
<div v-if="errors.items.length > 0">
    <b>Please correct the following error(s):</b>
    <ul class="alert validation-summary-errors">
        <li style="margin-left:5px;" v-for="error in errors.items">@{{ error.msg }}</li>
    </ul>
</div>
</div>

<div v-if="step === 2 && isInitial" data-vv-scope="step-2">
    <h1>Step Two: Recommended Elements</h1>

    <fieldset id="fieldset-project">
        <legend>Project</legend>
        <div class="pure-g">

            <div class="pure-u-1 pure-u-md-1-2 pure-div">
                <label for="project_id">
                    <span>
                        Project..
                        <i @click.prevent
                            v-tooltip.click="{ content: messages.project, class: 'tooltip-custom tooltip-other-custom' }"
                            class="far fa-lg fa-question-circle"></i>
                    </span>
                </label>
                <div class="select pure-u-23-24">
                    {!! Form::select('project_id', $projects, null, ['id' => 'project_id', 'placeholder' => '--no
                    project--', 'v-model' => 'dataset.project_id', 'data-vv-scope' => 'step-2'])
                    !!}
                </div>
                <small id="projectHelp" class="pure-form-message-inline">project is optional</small>
            </div>

        </div>
    </fieldset>

    <fieldset id="fieldset-dates">
        <legend>Date(s)</legend>
        <div class="pure-u-1 pure-u-md-1-2 pure-div">
            <label for="EmbargoDate">
                <span>
                    Embargo Date..
                    <i @click.prevent
                        v-tooltip.click="{ content: messages.embargo_date, class: 'tooltip-custom tooltip-other-custom' }"
                        class="far fa-lg fa-question-circle"></i>
                </span>
            </label>
            {!! Form::date('EmbargoDate', null, ['placeholder' => date('y-m-d'), 'class'
            => 'pure-u-23-24', 'v-model' => 'dataset.embargo_date', 'data-vv-scope' => 'step-2']) !!}
            <small id="projectHelp" class="pure-form-message-inline">EmbargoDate is optional</small>
        </div>
    </fieldset>

    <fieldset id="fieldset-geolocation">
        <legend>
            Geo Location
            <i @click.prevent
                v-tooltip.click="{ content: messages.geolocation, class: 'tooltip-custom tooltip-other-custom' }"
                class="far fa-lg fa-question-circle"></i>
        </legend>
        <div class="pure-g">
            <div class="pure-u-1 pure-u-md-1 pure-u-lg-1 pure-div">
                <locations-map v-bind:geolocation="dataset.coverage"></locations-map>
            </div>
            {{-- <div class="pure-u-1 pure-u-md-1-2 pure-div">
                            {!! Form::label('xmin', 'xmin: ') !!} 
                            {!! Form::text('xmin', null, [
                                'class' => 'pure-u-23-24',
                                'v-model' => 'dataset.coverage.xmin',
                                "v-validate" => "'decimal'",
                                'data-vv-scope' => 'step-2'
                            ]) !!}
                        </div>
                        <div class="pure-u-1 pure-u-md-1-2 pure-div">
                            {!! Form::label('ymin', 'ymin: ') !!} 
                            {!! Form::text('ymin', null, ['class' => 'pure-u-23-24', 'v-model' => 'dataset.coverage.ymin', 'data-vv-scope' => 'step-2']) !!}
                        </div>

                        <div class="pure-u-1 pure-u-md-1-2 pure-div">
                            {!! Form::label('xmax', 'xmax: ') !!} 
                            {!! Form::text('xmax', null, ['class' => 'pure-u-23-24', 'v-model' => 'dataset.coverage.xmax', 'data-vv-scope' => 'step-2']) !!}
                        </div>
                        <div class="pure-u-1 pure-u-md-1-2 pure-div">
                            {!! Form::label('ymax', 'ymax: ') !!} 
                            {!! Form::text('ymax', null, ['class' => 'pure-u-23-24', 'v-model' => 'dataset.coverage.ymax', 'data-vv-scope' => 'step-2']) !!}
                        </div> --}}
        </div>
    </fieldset>

    <fieldset id="fieldset-coverage">
        <legend>
            Coverage
            <i @click.prevent
                v-tooltip.click="{ content: messages.coverage, class: 'tooltip-custom tooltip-other-custom' }"
                class="far fa-lg fa-question-circle"></i>
        </legend>
        <div class="pure-g">

            <div class="pure-u-1 pure-u-md-1-2">
                <div class="pure-u-1 pure-u-md-1">
                    <label for="elevation-option-one" class="pure-radio">
                        <input id="elevation-option-one" type="radio" v-model="elevation" value="absolut">
                        absolut elevation (m)
                    </label>
                    <label for="elevation-option-two" class="pure-radio">
                        <input id="elevation-option-two" type="radio" v-model="elevation" value="range">
                        elevation range (m)
                    </label>
                    <label for="elevation-option-three" class="pure-radio">
                        <input id="elevation-option-three" type="radio" v-model="elevation" value="no_elevation">
                        no elevation
                    </label>
                </div>
                <div v-show="elevation === 'absolut'" class="pure-u-1 pure-u-md-1">
                    {!! Form::label('elevation_absolut', 'elevation absolut: ') !!}
                    {!! Form::text('elevation_absolut', null,
                    ['class' => 'pure-u-23-24', 'v-model' => 'dataset.coverage.elevation_absolut', 'data-vv-scope' =>
                    'step-2', "v-validate" => "this.isElevationAbsolut ? 'required|integer' : '' " ]) !!}
                </div>
                <div v-show="elevation === 'range'" class="pure-u-1 pure-u-md-1">
                    {!! Form::label('elevation_min', 'elevation min: ') !!}
                    {!! Form::text('elevation_min', null,
                    ['class' => 'pure-u-23-24', 'v-model' => 'dataset.coverage.elevation_min', 'data-vv-scope' =>
                    'step-2',
                    "v-validate" => "this.isElevationRange ? 'required|integer' : '' "]) !!}
                </div>
                <div v-show="elevation === 'range'" class="pure-u-1 pure-u-md-1">
                    {!! Form::label('elevation_max', 'elevation max: ') !!}
                    {!! Form::text('elevation_max', null,
                    ['class' => 'pure-u-23-24', 'v-model' => 'dataset.coverage.elevation_max', 'data-vv-scope' =>
                    'step-2',
                    "v-validate" => "this.isElevationRange ? 'required|integer' : '' "]) !!}
                </div>
            </div>

            <div class="pure-u-1 pure-u-md-1-2">
                <div class="pure-u-1 pure-u-md-1">
                    <label for="depth-option-one" class="pure-radio">
                        <input id="depth-option-one" type="radio" v-model="depth" value="absolut">
                        absolut depth (m)
                    </label>
                    <label for="depth-option-two" class="pure-radio">
                        <input id="depth-option-two" type="radio" v-model="depth" value="range">
                        depth range (m)
                    </label>
                    <label for="depth-option-three" class="pure-radio">
                        <input id="depth-option-three" type="radio" v-model="depth" value="no_depth">
                        no depth
                    </label>
                </div>

                <div v-show="depth === 'absolut'" class="pure-u-1 pure-u-md-1">
                    {!! Form::label('depth_absolut', 'depth absolut: ') !!} {!! Form::text('depth_absolut', null,
                    ['class'
                    => 'pure-u-23-24',
                    'v-model' => 'dataset.coverage.depth_absolut', 'data-vv-scope' => 'step-2', "v-validate" =>
                    "this.isDepthAbsolut
                    ? 'required|integer' : '' " ]) !!}
                </div>
                <div v-show="depth === 'range'" class="pure-u-1 pure-u-md-1">
                    {!! Form::label('depth_min', 'depth min: ') !!} {!! Form::text('depth_min', null, ['class' =>
                    'pure-u-23-24', 'v-model' =>
                    'dataset.coverage.depth_min', 'data-vv-scope' => 'step-2', "v-validate" => "this.isDepthRange ?
                    'required|integer'
                    : '' "]) !!}
                </div>
                <div v-show="depth === 'range'" class="pure-u-1 pure-u-md-1">
                    {!! Form::label('depth_max', 'depth max: ') !!} {!! Form::text('depth_max', null, ['class' =>
                    'pure-u-23-24', 'v-model' =>
                    'dataset.coverage.depth_max', 'data-vv-scope' => 'step-2', "v-validate" => "this.isDepthRange ?
                    'required|integer'
                    : '' "]) !!}
                </div>
            </div>

            <div class="pure-u-1 pure-u-md-1-2">
                <div class="pure-u-1 pure-u-md-1">
                    <label for="time-option-one" class="pure-radio">
                        <input id="time-option-one" type="radio" v-model="time" value="absolut">
                        absolut time (yyyy-MM-dd HH:mm:ss)
                    </label>
                    <label for="time-option-two" class="pure-radio">
                        <input id="time-option-two" type="radio" v-model="time" value="range">
                        time range (yyyy-MM-dd HH:mm:ss)
                    </label>
                    <label for="time-option-three" class="pure-radio">
                        <input id="time-option-three" type="radio" v-model="time" value="no_time">
                        no time
                    </label>
                </div>

                <div v-show="time === 'absolut'" class="pure-u-1 pure-u-md-1">
                    {!! Form::label('time_absolut', 'time absolut: ') !!}
                    {{-- {!! Form::datetime('time_absolut', null, ['class' => 'pure-u-23-24', 'placeholder' => 'dd.MM.yyyy HH:mm',
                                            'v-model' => 'dataset.coverage.time_absolut', 'data-vv-scope' => 'step-2', 'format' => 'yyyy-MM-dd HH:mm',
                                            "v-validate" => "this.isTimeAbsolut ? 'required|date_format:dd.MM.yyyy HH:mm:ss' : '' " ]) !!} --}}
                    <datetime name="time_absolut"
                        v-validate="this.isTimeAbsolut ? 'required' : '' " format="YYYY-MM-DD h:i:s"
                        data-vv-scope="step-2" v-model='dataset.coverage.time_absolut'>
                    </datetime>
                    {{-- <datetime name="time_absolut" format="MM-DD-YYYY H:i:s" width="300px" v-model="dataset.coverage.time_absolut"></datetime> --}}
                </div>
                <div v-show="time === 'range'" class="pure-u-1 pure-u-md-1">
                    {!! Form::label('time_min', 'time min: ') !!}
                    {{-- {!! Form::datetimelocal('time_min', null, ['class' => 'pure-u-23-24', 'placeholder' => 'dd.MM.yyyy HH:mm:ss', 
                                            'v-model' => 'dataset.coverage.time_min', 'data-vv-scope' => 'step-2', 'step' => 1,
                                            "v-validate" => "this.isTimeRange ? 'required|date_format:dd.MM.yyyy HH:mm:ss' : '' "]) !!} --}}
                    <datetime name="time_min"
                        v-validate="this.isTimeRange ? 'required' : '' " format="YYYY-MM-DD h:i:s"
                        data-vv-scope="step-2" v-model='dataset.coverage.time_min'></datetime>
                </div>
                <div v-show="time === 'range'" class="pure-u-1 pure-u-md-1">
                    {!! Form::label('timemax', 'time max: ') !!}
                    {{-- {!! Form::datetimelocal('time_max', null, ['class' => 'pure-u-23-24', 'placeholder' => 'dd.MM.yyyy HH:mm:ss',
                                            'v-model' => 'dataset.coverage.time_max', 'data-vv-scope' => 'step-2', 'step' => 1,
                                            "v-validate" => "this.isTimeRange ? 'required|date_format:dd.MM.yyyy HH:mm:ss' : '' "]) !!} --}}
                    <datetime name="time_max"
                        v-validate="this.isTimeRange ? 'required' : '' " format="YYYY-MM-DD h:i:s"
                        data-vv-scope="step-2" v-model='dataset.coverage.time_max'></datetime>
                </div>
            </div>

        </div>
    </fieldset>

    <fieldset id="fieldset-references">
        <legend>
            Dataset References
            <i @click.prevent v-tooltip.click="{ content: messages.dataset_references, class: 'tooltip-custom tooltip-other-custom' }"
                class="far fa-lg fa-question-circle"></i>
        </legend>
        <button class="pure-button button-small" @click.prevent="addReference()">Add Reference</button>
        <table class="table table-hover" v-if="dataset.references.length">
            <thead>
                <tr>
                    <th style="width: 20px;">
                        <span>
                            Value of the identifier <i
                            @click.prevent v-tooltip.click="{ content: messages.reference_value, class: 'tooltip-custom tooltip-other-custom' }"
                                class="far fa-lg fa-question-circle"></i>
                        </span>
                    </th>
                    <th>
                        <span>
                            Type <i
                            @click.prevent v-tooltip.click="{ content: messages.reference_type, class: 'tooltip-custom tooltip-other-custom' }"
                                class="far fa-lg fa-question-circle"></i>
                        </span>
                    </th>
                    <th>Relation<i
                        @click.prevent v-tooltip.click="{ content: messages.reference_relation, class: 'tooltip-custom tooltip-other-custom' }"
                            class="far fa-lg fa-question-circle"></i>
                    </th>
                    <th>
                        <span>
                            Label <i
                            @click.prevent v-tooltip.click="{ content: messages.reference_label, class: 'tooltip-custom tooltip-other-custom' }"
                                class="far fa-lg fa-question-circle"></i>
                        </span>
                    </th>
                    <th style="width: 130px;"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in dataset.references">
                    <td>
                        <input name="Reference Value" class="form-control" placeholder="[RELATED IDENTIFIER]"
                            v-model="item.value" v-validate="'required'" data-vv-scope="step-2" />
                    </td>
                    <td>
                        {!! Form::select('Reference[Type]', $relatedIdentifierTypes, null,
                        ['placeholder' => '[relatedIdentifierType]', 'v-model' => 'item.type', "v-validate" =>
                        "'required'", 'data-vv-scope' => 'step-2']) !!}
                    </td>
                    <td>
                        {!! Form::select('Reference[Relation]', $relationTypes, null,
                        ['placeholder' => '[relationType]', 'v-model' => 'item.relation', 'data-vv-scope' => 'step-2'])
                        !!}
                    </td>
                    <td>
                        <input name="Reference Label" class="form-control" v-model="item.label" v-validate="'required'"
                            data-vv-scope="step-2" />
                    </td>
                    <td>
                        <button class="pure-button button-small is-warning" @click.prevent="removeReference(index)"> <i
                                class="fa fa-trash"></i> </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </fieldset>


    <fieldset id="fieldset-keywords">
        <legend>
            Dataset Keywords
            <i @click.prevent v-tooltip.click="{ content: messages.dataset_keywords, class: 'tooltip-custom tooltip-other-custom' }"
                class="far fa-lg fa-question-circle"></i>
        </legend>
        <input type="hidden" v-validate:keywords_length="'min_value:3'" data-vv-scope="step-2" data-vv-as="keyword list"
            name="keywords_list">
        <button class="pure-button button-small" @click.prevent="addKeyword()">Add Keyword</button>
        <a v-if="dataset.language == 'en'" class="pure-button button-small" target="_blank"
        href="https://resource.geolba.ac.at/PoolParty/sparql/keyword?query=prefix%20skos:%20%3Chttp://www.w3.org/2004/02/skos/core%23%3E%20%20%0Aselect%20distinct%20(str(%3Fk)%20as%20%3FKeyword)%20(coalesce((group_concat(%3Fc;separator%3D%22;%20%22)),%22%22)%20as%20%3FCategory)%20%3FURI%0Awhere%20%7B%20%20%0A%3FURI%20a%20skos:Concept;%20skos:prefLabel%20%3Fk%0Afilter(lang(%3Fk)%3D%22en%22)%20filter(!regex(%3Fk,%22category%22))%0Aoptional%7B%3FURI%20%3Chttp://dbpedia.org/ontology/category%3E%20%3Fc%7D%20%20%0A%7D%20%20%0Agroup%20by%20%3FURI%20%3Fk%0Aorder%20by%20%3FCategory%20%3FKeyword&format=text/html">
            Keyword Recommendation
        </a>
        <a v-else="dataset.language == 'de'" class="pure-button button-small"  target="_blank"
        href="https://resource.geolba.ac.at/PoolParty/sparql/keyword?query=prefix%20skos:%20%3Chttp://www.w3.org/2004/02/skos/core%23%3E%20%20%0Aselect%20distinct%20(str(%3Fk)%20as%20%3FSchlagwort)%20(coalesce((group_concat(%3Fc;separator%3D%22;%20%22)),%22%22)%20as%20%3FKategorie)%20%3FURI%0Awhere%20%7B%20%20%0A%3FURI%20a%20skos:Concept;%20skos:prefLabel%20%3Fk%0Afilter(lang(%3Fk)%3D%22de%22)%20%0Aoptional%7B%3FURI%20%3Chttp://dbpedia.org/ontology/category%3E%20%3Fc%7D%20%20%0A%7D%20%20%0Agroup%20by%20%3FURI%20%3Fk%0Aorder%20by%20%3FKategorie%20%3FSchlagwort&format=text/html">
        Schlüsselwörter Empfehlung
        </a>
        <table class="table table-hover" v-if="dataset.subjects.length">
            <thead>
                <tr>
                    <th style="width: 20px;">
                        <span>
                            Keyword Value <i
                            @click.prevent v-tooltip.click="{ content: messages.keyword_value, class: 'tooltip-custom tooltip-other-custom' }"
                                class="far fa-lg fa-question-circle"></i>
                        </span>
                    </th>
                    <th>
                        <span>
                            Keyword Type <i
                            @click.prevent v-tooltip.click="{ content: messages.keyword_type, class: 'tooltip-custom tooltip-other-custom' }"
                                class="far fa-lg fa-question-circle"></i>
                        </span>
                    </th>
                    <th>Language</th>
                    <th style="width: 130px;"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in dataset.subjects">
                    <td>
                        <input name="Keyword Value" class="form-control" placeholder="[KEYWORD VALUE]"
                            v-model="item.value"
                            v-validate="{required: true, unique: [dataset.subjects, index, 'value']}"
                            data-vv-scope="step-2" />
                    </td>
                    <td>
                        {{-- {!! Form::select('Keyword[Type]', $keywordTypes, null, ['placeholder' => '[keyword type]', 'v-model' =>
                                    'item.type', "v-validate" => "'required'", 'data-vv-scope' => 'step-2']) !!} --}}
                        <input name="Keyword Type" readonly class="form-control" placeholder="[KEYWORD TYPE]"
                            v-model="item.type" v-validate="'required'" data-vv-scope="step-2" />
                    </td>
                    <td>
                        <input name="Keyword Language" readonly class="form-control" placeholder="[KEYWORD LANGUAGE]"
                            v-model="item.language" v-validate="'required'" data-vv-scope="step-2" />
                    </td>
                    <td>
                        <button class="pure-button button-small is-warning" @click.prevent="removeKeyword(index)"> <i
                                class="fa fa-trash"></i> </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </fieldset>

    <br />

    <div class="pure-controls">
        <button @click.prevent="prev()" class="pure-button button-small">
            <i class="fa fa-arrow-left"></i>
            <span>Back</span>
        </button>

        <button @click.prevent="next('step-2')" class="pure-button button-small" v-bind:disabled="errors.any()">
            <i class="fa fa-arrow-right"></i>
            <span>Continue</span>
        </button>
    </div>
    <div v-if="errors.items.length > 0">
        <b>Please correct the following error(s):</b>
        <ul class="alert validation-summary-errors">
            <li style="margin-left:5px;" v-for="error in errors.items">@{{ error.msg }}</li>
        </ul>
    </div>
</div>

<div v-if="step === 3 && isInitial" data-vv-scope="step-3">
    <h1>Step 3: Other Elements</h1>

    <fieldset id="fieldset-licenses">
        <legend>
            Rights List
            <i @click.prevent v-tooltip.click="{ content: messages.rights_list, class: 'tooltip-custom tooltip-other-custom' }"
                class="far fa-lg fa-question-circle"></i>
        </legend>

        <div class="pure-control-group checkboxlist">
            @foreach ($licenses as $indexKey => $license)
            <label for={{ "license". $license->id }} class="pure-checkbox">
                @if ($loop->first)
                <input name="licenses" value={{ $license->id }} v-model="dataset.checkedLicenses" type="radio"
                    class="form-check-input" v-validate="'required'" data-vv-as="Licence" data-vv-scope="step-3">
                <a href="{{ $license->link_licence }}" target="_blank">{{ $license->name_long }}</a>
                @else
                <input name="licenses" value={{ $license->id }} v-model="dataset.checkedLicenses" type="radio"
                    class="form-check-input" data-vv-scope="step-3">
                <a href="{{ $license->link_licence }}" target="_blank">{{ $license->name_long }}</a>
                @endif
            </label>
            @endforeach
            <br>
            {{-- <span>Checked license: @{{ dataset.checkedLicenses }}</span> --}}
        </div>
    </fieldset>

    <br />
    <div class="pure-controls">
        <button @click.prevent="prev()" class="pure-button button-small">
            <i class="fa fa-arrow-left"></i>
            <span>Back</span>
        </button>

        <button @click.prevent="next('step-3')" class="pure-button button-small">
            <i class="fa fa-arrow-right"></i>
            <span>Continue</span>
        </button>
    </div>
    <div v-if="errors.items.length > 0">
        <b>Please correct the following error(s):</b>
        <ul class="alert validation-summary-errors">
            <li style="margin-left:5px;" v-for="error in errors.items">@{{ error.msg }}</li>
        </ul>
    </div>
</div>

<div v-if="step === 4 && (isInitial || isSaving)" data-vv-scope="step-4">
    <h1>
        File Upload
        <i @click.prevent v-tooltip.click="{ content: messages.file_upload, class: 'tooltip-custom tooltip-other-custom' }"
            class="far fa-lg fa-question-circle"></i>
    </h1>

    <div class="dropbox">
        <input type="hidden" v-validate:files_length="'min_value:1'" data-vv-scope="step-4" data-vv-as="files list"
            name="files_list">
        <input type="file" multiple name="files" v-bind:disabled="isSaving"
            @change="filesChange($event.target.name, $event.target.files)" class="input-file" data-vv-scope="step-4">
        <p v-if="isInitial">
            Drag your file(s) here to begin<br> or click to browse
        </p>
        <p v-if="isSaving">
            Uploading @{{ fileCount }} files...
        </p>
    </div>


    <table class="table table-hover" v-if="dataset.files.length">
        <thead>
            <tr>
                <th style="width: 20px;">Sorting</th>
                <th>File</th>
                <th>
                    <span>
                        Label <i
                        @click.prevent v-tooltip.click="{ content: messages.file_label, class: 'tooltip-custom tooltip-other-custom' }"
                            class="far fa-lg fa-question-circle"></i>
                    </span>
                </th>
                <th style="width: 130px;"></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(item, index) in dataset.files">
                <td>
                    @{{ index +1 }}
                </td>
                <td>
                    <input class="form-control" v-model="item.file.name" readonly />
                </td>
                <td>
                    <input class="form-control" v-model="item.label" />
                </td>
                <td>
                    <button class="pure-button button-small is-warning" @click.prevent="removeFile(index)"> <i
                            class="fa fa-trash"></i> </button>
                </td>
            </tr>
        </tbody>
    </table>
    <button @click.prevent="prev()" class="pure-button button-small">
        <i class="fa fa-arrow-left"></i>
        <span>Back</span>
    </button>
    <button @click.prevent="submit('step-4')" class="pure-button button-small" v-bind:disabled="errors.any()">
        <i class="fa fa-save"></i>
        <span>Save Dataset</span>
    </button>
    <div v-if="errors.items.length > 0">
        <b>Please correct the following error(s):</b>
        <ul class="alert validation-summary-errors">
            <li style="margin-left:5px;" v-for="error in errors.items">@{{ error.msg }}</li>
        </ul>
    </div>
    {{-- <button @click.prevent="submit('unpublished')" class="pure-button button-small">               
                    <i class="fa fa-upload"></i>
                    <span>Release Dataset</span>
                </button>               --}}

</div>

<!--SUCCESS-->
<div v-if="isSuccess">
    <h2>Uploaded @{{ dataset.files.length }} file(s) successfully.</h2>
    {{-- <p>
                <a href="javascript:void(0)" @click="reset()" class="pure-button button-small">Upload new Dataset</a>
                </p> --}}
    <p>
        {{-- <a href="javascript:void(0)" @click="editNewDataset()" class="pure-button button-small">@{{ redirectLink }}</a>
        --}}
        <a href="javascript:void(0)" @click="editNewDataset()" class="pure-button button-small">
            <i class="fa fa-edit"></i>
            <span>Edit</span>
        </a>
        <a href="javascript:void(0)" @click="releaseNewDataset()" class="pure-button button-small">
            <i class="fa fa-share"></i>
            <span>Release</span>
        </a>
        <i @click.prevent v-tooltip.click="{ content: messages.upload_successfull_release, class: 'tooltip-custom tooltip-other-custom' }"
            class="far fa-lg fa-question-circle"></i>
        <a href="javascript:void(0)" @click="deleteNewDataset()" class="pure-button button-small">
            <i class="fa fa-trash"></i>
            <span>Delete</span>
        </a>
    </p>

    <ul class="list-unstyled">
        {{-- <li v-for="item in uploadedFiles">
                    <img :src="item.url" class="img-responsive img-thumbnail" :alt="item.originalName">
                </li> --}}
    </ul>
</div>

<!--ERROR-->
<div v-if="isFailed">
    <h2>Uploaded failed.</h2>
    <p>
        <a href="javascript:void(0)" @click="retry()">Retry: Edit inputs</a>
    </p>
    <div v-if="serrors.length > 0">
        <b>Please correct the following server error(s):</b>
        <ul class="alert validation-summary-errors">
            <li style="margin-left:5px;" v-for="error in serrors">@{{ error }}</li>
        </ul>
    </div>
</div>


</main>
{{-- <br/><br/>Debug:@{{ dataset }} --}}
</div>


@stop
@section('styles')
<style type="text/css">
    /* main.steps article {
        display:block;
    } */

    .help-block {
        display: none;
        font-size: 0.8em;
    }

    .has-error .help-block {
        display: block;
    }
</style>


@stop
@section('after-scripts')
<script>
    window.Laravel = <?php echo json_encode([            
            'languages' => $languages,
            'messages' => $messages,   
            'contributorTypes' => $contributorTypes,
            'nameTypes' => $nameTypes,

        ]); ?>        
</script>
<script type="text/javascript" src="{{  asset('backend/publish/datasetPublish.js') }}"></script>

@stop