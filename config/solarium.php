<?php

return [
    'endpoint' => [
        'repository' => [
            'host' => env('SOLR_HOST', 'repository.geologie.ac.at'),
            'port' => env('SOLR_PORT', '8983'),
            'path' => env('SOLR_PATH', '/solr/'),
            'core' => env('SOLR_CORE', 'rdr_data')
        ]
        ],
        'xsltfile' => "public/solr.xslt"
];
