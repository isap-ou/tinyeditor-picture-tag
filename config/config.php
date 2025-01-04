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
            'position' => 1
        ],
        [
            'name' => 'sm',
            'width' => 410,
            'position' => 2
        ],
        [
            'name' => 'lg_webp',
            'width' => 1200,
            'format' => 'webp',
            'min-width' => 576,
            'position' => 3
        ],
        [
            'name' => 'lg',
            'width' => 1200,
            'min-width' => 576,
            'position' => 4
        ],
    ]
];
