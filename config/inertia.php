<?php

return [
    'ssr' => ['enabled' => false],

    'testing' => [
        'ensure_pages_exist' => true,
        'page_paths' => [
            resource_path('js/admin/Pages'),
            resource_path('views/Pages'),
        ],
        'page_extensions' => ['js', 'jsx', 'svelte', 'ts', 'tsx', 'vue'],
    ],

    'history' => ['encrypt' => false],
];
