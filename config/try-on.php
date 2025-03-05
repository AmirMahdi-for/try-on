<?php


return [
    'category-identifier-api' => env('CATEGORY_IDENTIFIER_API'),
    'category-identifier-token' => env('CATEGORY_IDENTIFIER_TOKEN'),
    'try-on-token' => env('TRY_ON_SERVICE_TOKEN'),
    'try-on-api' => env('TRY_ON_SERVICE_API'),
    'file_model' => env('SPEECH_TEXTER_FILE_MODEL', 'App\Models\File'),
];
