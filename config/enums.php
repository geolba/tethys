<?php

return [
    'publication_states' => [
        "draft" => "draft",
        "accepted" => "accepted",
        'submitted' => 'submitted',
        'published' => 'published',
        'updated' => 'updated',
    ],
    'server_states' => [
        "audited" => "audited",
        "published" => "published",
        'restricted' => 'restricted',
        'inprogress' => 'inprogress',
        'unpublished' => 'unpublished',
        'deleted' => 'deleted',
        'temporary' => 'temporary',
        'created' => 'created',
    ],
    'mimetypes_allowed' => [
        "pdf" => "application/pdf",
        "txt|asc|c|cc|h|srt" => "text/plain",
        "htm|html"    => "text/html",
        "png" => "image/png",
        "jpg|jpeg|jpe"  => "image/jpeg",
    ],
    'max_filesize' => 5120
];
