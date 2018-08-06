<?php

return [
   'msg' => 'Laravel Internationalization example.',

   'home_index_label' => 'Home',
   'home_index_pagetitle' => 'Home',
   'home_index_title' => 'Publication Server of XYZ University',
   'home_index_welcome' => 'The library offers to publish electronically generated and
        qualified documents on its online publication system. This service is
        for university members only and free of charge. After publication, the
        texts are available worldwide on the Internet and will be archived
        permanently by the library. The documents are indexed and made
        accessible in library catalogues and Web search engines.',
     'home_index_instructions' => 'If you want to search for documents of the university, please
        choose the menu "Search" where you will find several search options. If
        you want to publish a document, please select the menu "Publish"; here
        you can submit your document to the publication server in just a few
        steps.',

     'home_index_imprint_pagetitle' => 'Imprint',
     'home_index_imprint_title' => 'Legal notice according to  &#xA7; 5 E-Commerce-Gesetz (Austria)',
     'help_content_imprint' => Illuminate\Support\Facades\File::get(resource_path() . '/lang/en/imprint.en.txt'),

     'home_index_contact_pagetitle' => 'Contact',
     'home_index_contact_title' => 'Contact us...',

      'home_about_pagetitle' => 'About Us',
      'home_about_title' => 'About us...',
      'home_about_content' => 'RDR (Research Data Repository) is an interdisciplinary digital data repository for the archival and publication of research data resulting from completed scientific studies and projects. RDR focuses on disciplines who do not have a tradition of data sharing thus ensuring better availability, sustainable preservation and (independent) publication capacity of their research data.',

       'home_news_pagetitle' => 'News',


     'solrsearch_title_simple' => 'Search',
     'solrsearch_title_advanced' => 'Advanced Search',
     'solrsearch_title_alldocs' => 'All datasets',
     'solrsearch_searchaction' => 'Search',
     'solrsearch_title_latest' => 'Latest Documents',

     'rss_icon' =>'subscribe to RSS feed',
     'rss_title' => 'subscribe to RSS feed',

      'default_auth_index' => 'Login',
      'auth_pagetitle' => 'User Login',
];
?>