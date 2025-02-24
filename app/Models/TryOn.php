<?php

namespace TryOn\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TryOn extends Model
{
    use HasFactory;

    protected $table = 'try_on';

    protected $fillable = [
        "uuid", "user_id", "model_file_id", "garment_file_id",
        "category", "send_images_result", "send_images_status_code",
        "status_result", "status_request_status_code",
        "generate_result", "generate_response_status_code"
    ];

    protected $casts = [
        'send_images_result' => 'json',
        'status_result' => 'json',
        'generate_result' => 'json',
    ];
}
