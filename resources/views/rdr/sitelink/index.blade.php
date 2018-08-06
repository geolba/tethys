@extends('layouts.app')

@section('content')
<div class="pure-g">
    <div class="pure-u-1 pure-u-md-2-3">
        <div class="content">
            <h1 class="title">Sitelinks for Web Crawlers</h1>

            <span>
                <?php foreach ($years as $year) : ?>
                <a title="documents published in year <?= $year ?>" href="{{ URL::route('sitelinks.list',['year' => $year]) }}"><?= $year ?>
                </a>
                <?php endforeach; ?>
            </span>

            
            <div class="posts">
                <ol><?php foreach ($documents as $document) : ?>
                    <li>
                        <section class="post">
                            <header class="post-header">
                                <h2 class="post-title">
                                    <a href="{{ URL::route('document.show',['id' =>$document->id]) }}"><?= $document->type;  $document->id; ?>
                                    </a>
                                </h2>
                            </header>
                            <div class="blog-meta"><?= $document->server_date_published->toDayDateTimeString()  ?>
                            </div>
                            <div class="post-description">
                                @foreach ($document->authors as $author)
                                <em>Author: {{ $author->getFullName() }}</em>
                                <br />
                                @endforeach

                                @foreach ($document->titles as $title)
                                <em>Main Title: {{ $title->value }}</em>
                                <br />
                                @endforeach
                            </div>

                        </section>
                    </li><?php endforeach; ?>
                </ol>
            </div>
            

            </div>
        </div>        
</div>
        @endsection
