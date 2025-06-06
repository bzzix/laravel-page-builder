<?php
return [
    'middleware'      => ['web'],
    'create_route'    => 'grapesjs/new',
    'update_route'    => 'grapesjs/update/{slug}',
    
    // روابط ملفات CSS
    'styles' => [
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
    ],

    // روابط ملفات JavaScript
    'scripts' => [
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js',
    ],

    // الإضافات (plugins)
    'plugins' => [
        [
            'name' => 'gjs-preset-webpage',
            'enabled' => true,
            'style' => '',
            'script' => 'https://unpkg.com/grapesjs-preset-webpage',
            'options' => []
        ],
        [
            'name' => 'grapesjs-plugin-forms',
            'enabled' => false, 
            'style' => '',
            'script' => 'https://unpkg.com/grapesjs-plugin-forms',
            'options' => []
        ],
        [
            'name' => 'grapesjs-navbar',
            'enabled' => false,
            'style' => '',
            'script' => 'https://unpkg.com/grapesjs-navbar',
            'options' => []
        ],
        [
            'name' => 'grapesjs-custom-code',
            'enabled' => false,
            'style' => '',
            'script' => 'https://unpkg.com/grapesjs-custom-code',
            'options' => []
        ],
        [
            'name' => 'grapesjs-tabs',
            'enabled' => false,
            'style' => 'https://unpkg.com/grapesjs-tabs/dist/grapesjs-tabs.min.css',
            'script' => 'https://unpkg.com/grapesjs-tabs',
            'options' => [
                'tabsBlock' => []
            ]
        ],
        [
            'name' => 'gjs-blocks-basic',
            'enabled' => true,
            'style' => '',
            'script' => 'https://unpkg.com/grapesjs-blocks-basic',
                'options' => [
                'blocks' => ['text', 'link', 'image', 'video', 'map'],
            ]
        ]
    ],

];