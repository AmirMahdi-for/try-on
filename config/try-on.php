<?php


return [
    'category-identifier-api' => env('CATEGORY_IDENTIFIER_API'),
    'category-identifier-token' => env('CATEGORY_IDENTIFIER_TOKEN'),
    'try-on-token' => env('TRY_ON_SERVICE_TOKEN'),
    'try-on-api' => env('TRY_ON_SERVICE_API'),
    'file_model' => env('SPEECH_TEXTER_FILE_MODEL', 'App\Models\File'),
    'use_file_model' => env('USE_FILE_MODEL', false),

    //v2:
    'try-on-token-v2' => env('TRY_ON_SERVICE_TOKEN_V2'),
    'try-on-api-v2' => env('TRY_ON_SERVICE_API_V2'),

];
