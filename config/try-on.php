<?php


return [
    'api_key' => env('SPEEECH_TEXTER_X_API_KEY'),
    'voice_api' => env('SPEEECH_TEXTER_VOICE_API'),
    'user_model' => env('SPEEECH_TEXTER_USER_MODEL', 'App\Models\User'),
    'file_model' => env('SPEEECH_TEXTER_FILE_MODEL', 'App\Models\File'),
    'prefix' => 'speeech-texter',
];