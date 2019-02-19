<div class="sidebar-simplesearch">
    {!! Form::open(array('route' => 'frontend.queries','method' => 'POST', 'class'=>'pure-form')) !!}
  
     
           
            {!! Form::text('query', isset($filter) ? $filter : '', array('class'=>'pure-input-1', 'placeholder'=>'Search for a dataset...')) !!}
           
            <!--<div id="edit-submit-search-wrapper" class="form-item">
                <span class="form-submit-wrapper">
                    <input type="submit" id="edit-submit-search" class="form-submit" value="Search" />
                </span>
            </div>-->
            <button type="submit">
                <i class="fa fa-search"></i>
            </button>  
       
        <p class="footer-link">            
            <?php
            $searchAllDocsText =  Illuminate\Support\Facades\Lang::get('resources.solrsearch_title_alldocs');
            if (isset($totalNumOfDocs))
            {
            $searchAllDocsText = $searchAllDocsText . '&nbsp;(<span id="solrsearch-totalnumofdocs">' . $totalNumOfDocs . '</span>)';
            }
            ?>

            <a id="link-solrsearch-all-documents" class="link" href="{{ URL::route('frontend.queries1',['searchtype' => 'all']) }}"><?= $searchAllDocsText; ?>
            </a>
            <!--<a class="link" href="">'resources.solrsearch_title_latest'</a>-->

            <a href="" class="rss" type="application/rss+xml">
                <img src="{{ URL::asset('/img/feed_small.png' )}}" width="12" height="12" alt="{{ Lang::get('resources.rss_icon')}}" title="@lang('resources.rss_title')" />
            </a>
        </p>

        <input type="hidden" name="searchtype" id="searchtype" value="simple" />
        <input type="hidden" name="start" id="start" value="0" />
        <input type="hidden" name="sortfield" id="sortfield" value="score" />
        <input type="hidden" name="sordorder" id="sortorder" value="desc" />
   
    {!! Form::close() !!}  
</div>

<style>
    .sidebar-simplesearch {
        position: relative;
        margin-bottom: 2.5em;
        white-space: nowrap;
    }

        .sidebar-simplesearch input[type=text] {
            padding: 0.25em 0.3em;
            color: #666;
        }

        .sidebar-simplesearch button {
            padding: 0.25em 0.3em;
            border: none;
            background: none;
            position: absolute;
            right: 0.25em;
            color: #666;
        }
</style>