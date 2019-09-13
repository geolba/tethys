@extends('layouts.app') 
@section('content')

<section class="normal dataset u-full-width">
    <div class="container">
        <div class="blog-meta">
            created: <?= $dataset->created_at->toDayDateTimeString()  ?>
        </div>
        <div class="post-description">
            {{-- @foreach ($dataset->authors as $author)
            <em>Author: {{ $author->full_name }}</em>
            <br /> 
            @endforeach  --}}
            @foreach ($dataset->titles as $title)
            <em>Main Title: {{ $title->value }}</em>
            <br /> 
            @endforeach
            @foreach ($dataset->abstracts as $abstract)
            <em>Abstract: {{ $abstract->value }}</em>
            <br /> 
            @endforeach
            @foreach ($authors as $author)
            <em>Author: {{ $author->full_name }}</em>
            <br /> 
            @endforeach
            @foreach ($contributors as $contributor)
            <em>Contributor: {{ $contributor->full_name }}</em>
            <br /> 
            @endforeach
            @foreach ($submitters as $submitter)
            <em>Submitter: {{ $submitter->full_name }}</em>
            <br /> 
            @endforeach

            <table id="items" class="pure-table pure-table-horizontal">
                <thead>
                    <tr>
                        <th>Path Name</th>
                        <th>Label</th>
                    </tr>
                </thead>    
                <tbody>
                    @foreach($dataset->files as $key => $file)
                    <tr>
                        <td>
                            @if($file->exists() === true)
                            <a href="{{ route('file.download', ['id' => $file->id]) }}"> {{ $file->path_name }} </a> 
                            @else
                            <span class="alert">missing file: {{ $file->path_name }}</span> 
                            @endif
                        </td>
                        <td> {{ $file->label }} </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

</section>

@stop