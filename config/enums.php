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
    'filetypes_allowed' => [
        "pdf", "txt", "html", "htm", "png", "jpeg",
    ],
    'max_filesize' => 5120,
];
