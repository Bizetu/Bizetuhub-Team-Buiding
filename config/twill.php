<?php

use A17\Twill\Services\MediaLibrary\Local;

return [
    'block_editor' => [
        'crops' => [
            'image' => [
                'desktop' => [
                    [
                        'name' => 'desktop',
                        'ratio' => 16 / 9,
                    ],
                ],
            ],
            'background' => [
                'default' => [
                    [
                        'name' => 'default',
                        'ratio' => 2 / 1,
                    ],
                ],
            ],
            'logo' => [
                'default' => [
                    [
                        'name' => 'default',
                        'ratio' => 1,
                    ],
                ],
            ],
        ],
    ],
    'media_library' => [
        'image_service' => env(
            'MEDIA_LIBRARY_IMAGE_SERVICE',
            Local::class
        ),
    ],
    'file_library' => [
        'allowed_extensions' => ['mp4', 'webm', 'mov'],
    ],
];
