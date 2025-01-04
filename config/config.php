<?php

return [
    'processed_fields' => ['content'],
    'storage_disk' => 'public',
    'driver' => 'non-translatable',
    'media_collection' => 'editor-collection',
    'media_conversions' => [
        [
            'name' => 'sm_webp',
            'width' => 410,
            'format' => 'webp',
        ],
        [
            'name' => 'sm',
            'width' => 410,
        ],
        [
            'name' => 'lg_webp',
            'width' => 1200,
            'format' => 'webp',
        ],
        [
            'name' => 'lg',
            'width' => 1200,
        ],
    ]
];
