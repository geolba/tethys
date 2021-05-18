<?php
return [
    'repoid' => 3156505,
    'ccBaseuri' => 'https://creativecommons.org/licenses/|/3.0/',
    
    'datacite_environment' => env('DATACITE_ENVIRONMENT'),

    'datacite_username' => env('DATACITE_USERNAME'),
    'datacite_test_username' => env('DATACITE_TEST_USERNAME'),
    
    'datacite_password' => env('DATACITE_PASSWORD'),
    'datacite_test_password' => env('DATACITE_TEST_PASSWORD'),
    
    'datacite_prefix' => env('DATACITE_PREFIX'),
    'datacite_test_prefix' => env('DATACITE_TEST_PREFIX'),

    'datacite_service_url' => env('DATACITE_SERVICE_URL'),
    'datacite_test_service_url' => env('DATACITE_TEST_SERVICE_URL'),

    'base_domain' => env('BASE_DOMAIN', 'https://tethys.at'),
    'test_base_domain' => env('TEST_BASE_DOMAIN')
];
