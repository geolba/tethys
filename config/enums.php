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
        "deleted" => "deleted",
        "inprogress" => "inprogress",
        "published" => "published",
        "released" => "released",
        "editor_accepted" => "editor_accepted",
        "approved" => "approved",
        "rejected_reviewer" => "rejected_reviewer",
        "rejected_editor" => "rejected_editor",
        "reviewed" => "reviewed",
    ],
    'mimetypes_allowed' => [
        "pdf" => "application/pdf",
        "txt|asc|c|cc|h|srt" => "text/plain",
        "htm|html"    => "text/html",
        "png" => "image/png",
        "jpg|jpeg|jpe"  => "image/jpeg",
    ]
];
