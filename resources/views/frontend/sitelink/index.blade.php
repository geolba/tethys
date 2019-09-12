@extends('layouts.app')
@section('content')

<section id="sitelinks" class="normal u-full-width">
<div class="container">
    <div class="row">
        <div class="twelve columns">
            <div class="content">
                <h1 class="title">Sitelinks for Web Crawlers</h1>

                <span>
                    <?php foreach ($years as $year) : ?>
                    <a title="datasets published in year <?= $year ?>"
                        href="{{ URL::route('frontend.sitelinks.list',['year' => $year]) }}"><?= $year ?>
                    </a>
                    <?php endforeach; ?>
                </span>

                <div class="posts">
                    <ol>
                        <?php foreach ($documents as $document) : ?>
                        <li>
                            <div class="post">
                                <header class="post-header">
                                    <h2 class="post-title">
                                        <a href="{{ URL::route('frontend.dataset.show',['id' =>$document->id]) }}"><?= $document->type;  $document->id; ?>
                                        </a>
                                    </h2>
                                </header>
                                <div class="blog-meta">
                                    <?= $document->server_date_published->toDayDateTimeString()  ?>
                                </div>
                                <div class="post-description">
                                    @foreach ($document->authors as $author)
                                    <em>Author: {{ $author->full_name }}</em>
                                    <br />
                                    @endforeach
                                    @foreach ($document->titles as $title)
                                    <em>Main Title: {{ $title->value }}</em>
                                    <br />
                                    @endforeach
                                </div>

                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ol>
                </div>


            </div>
        </div>
    </div>
</div>
</section>

@endsection