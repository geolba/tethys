<?php

return [
    'endpoint' => [
        'repository' => [
            'host' => env('SOLR_HOST', 'repository.geologie.ac.at'),
            'port' => env('SOLR_PORT', '8983'),
            'path' => env('SOLR_PATH', '/'),
            'core' => env('SOLR_CORE', 'rdr_data')
        ]
        ],
        'xsltfile' => "solr.xslt"
];
