<?php

return [
    'endpoint' => [
        'localhost' => [
            'host' => env('SOLR_HOST', 'zontik.gba.geolba.ac.at'),
            'port' => env('SOLR_PORT', '8983'),
            'path' => env('SOLR_PATH', '/solr/'),
            'core' => env('SOLR_CORE', 'opus4')
        ]
        ],
        'xsltfile' => "solr.xslt"
];
