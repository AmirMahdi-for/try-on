<?php 

namespace TryOn\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use TryOn\Models\TryOn;

class CreateTryOnDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        TryOn::create([
            'uuid' => $this->data['uuid'],
            'user_id' => $this->data['user_id'],
            'model_file_id' => $this->data['model_file_id'],
            'garment_file_id' => $this->data['garment_file_id'],
            'category' => $this->data['category'],
            'send_images_result' => $this->data['send_images_result'],
            'send_images_status_code' => $this->data['send_images_status_code'],
        ]);
    }

}
