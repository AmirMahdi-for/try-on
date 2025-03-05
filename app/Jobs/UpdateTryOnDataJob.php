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

class UpdateTryOnDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $uuid;

    /**
     * Create a new job instance.
     */
    public function __construct(string $uuid, array $data)
    {
        $this->data = $data;
        $this->uuid = $uuid;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        TryOn::where('uuid', $this->uuid)->update($this->data);
    }

}
