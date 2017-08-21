<?php

return [
    'projectName' => 'Despark',
    'defaultFormView' => 'ignicms::admin.formElements.defaultForm',
    'paginateLimit' => 15,
    'paths' => [
        'model' => app_path('Models'),
        'request' => app_path('Http/Requests/Admin'),
        'controller' => app_path('Http/Controllers/Admin'),
        'migration' => base_path('database/migrations'),
        'config' => base_path('config/admin'),
        'routes' => base_path('routes'),
    ],
    'files' => [
        // No leading slash
        'temporary_directory' => 'temp_uploads',
    ],
    'images' => [
        // Retina factor. User null or false if you don't want retina images to be generated.
        'retina_factor' => 2,
        'max_upload_size' => 5000,
        'admin_thumb_width' => 200,
        'admin_thumb_height' => 200,
        'admin_thumb_type' => 'fit',
        'disable_alt_title_fields' => false,
        'require_alt_title_fields' => true,
    ],
    'admin_assets' => [
        'js' => [
            'js/admin.js',
        ],
        'css' => [
            //'css/styles.css',
            '/css/admin.css',
        ],
    ],
];