<?php

return [
    'debug' => true,
    'panel' => [
        'install' => true,
        'language' => 'de'
    ],
    'languages' => false,
    'home' => 'home',

    // Redirect old .html URLs to clean URLs
    'routes' => [
        [
            'pattern' => 'index.html',
            'action'  => function () {
                go('/', 301);
            }
        ],
        [
            'pattern' => '(:any).html',
            'action'  => function ($page) {
                go($page, 301);
            }
        ]
    ]
];
