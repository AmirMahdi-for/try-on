<?php

namespace TryOn\Repositories\v2;


use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use TryOn\Repositories\v2\Interfaces\TryOnRepositoryInterface;

class TryOnRepository implements TryOnRepositoryInterface
{
    public function __construct() {
        $this->tryOnServiceToken = config('try-on.try-on-token-v2');
        $this->tryOnServiceApi = config('try-on.try-on-api-v2');
    }

    /**
    * Process try-on for a user with given parameters.
    *
    * @param int $userId
    * @param array $parameters
    * @return mixed
    */
    public function tryOn(int $userId, array $parameters)
    {
        try {
            $modelImage = $parameters['message']['image'];
            $garmentImage = $parameters['message']['productImage'];

            $response = Http::withHeaders([
                'Authorization' => 'Key ' . $this->tryOnServiceToken,
                'Content-Type'  => 'application/json',
            ])->post($this->tryOnServiceApi, [
                'model_image'        => $modelImage,        
                'garment_image'      => $garmentImage,      
                'category'           => 'auto',
                'mode'               => 'balanced',         
                'garment_photo_type' => 'auto',             
                'moderation_level'   => 'permissive',       
                'seed'               => 42,                 
                'num_samples'        => 1,                  
                'segmentation_free'  => true,               
                'output_format'      => 'png',              
            ]);

            $statusCode = $response->status();
            $responseBody = json_decode($response->body(), true);

            $uuid = Str::uuid()->toString();

            $tryOnData = [
                "uuid"   => $uuid,
                "user_id" => $userId,
                "model_file_id" => null, 
                "garment_file_id" => null,
                "send_images_result" => $responseBody ?? null,
                "send_images_status_code" => $statusCode,
            ];

            $statusResult = $this->getImageStatus($tryOnData);
            
            if ($statusResult['status'] == 'COMPLETED') {
                $tryOn = $this->getGenerateImage($tryOnData);
                return $tryOn;
            }
            
        } catch (\Exception $e) {
            Log::error("Error in tryOn method: " . $e->getMessage());
            return null;
        }
    }

    /**
    * Retrieve category for a given product name.
    *
    * @param string $productName
    * @return string
    */

    public function getImageStatus($tryOn) 
    {
        try {
            $maxTime = 600;
            $interval = 3; 
            $elapsedTime   = 0;

            $requestId = $tryOn['send_images_result']['request_id'];
            
            while ($elapsedTime < $maxTime) {
                $response = Http::withHeaders([
                    'Authorization' => 'Key ' . $this->tryOnServiceToken,
                ])->get("https://queue.fal.run/fal-ai/fashn/requests/$requestId/status");

                $body = $response->json();
                if (isset($body['status']) && $body['status'] === 'COMPLETED') {
                    return $body;
                }

                sleep($interval);
                $elapsedTime += $interval;
            }
        } catch (\Exception $e) {
            Log::error("Error in getImageStatus: " . $e->getMessage());
            return null;
        }
    }

    public function getGenerateImage($tryOn) 
    {
        $requestId = $tryOn['send_images_result']['request_id'];
        try {

            $response = Http::withHeaders([
                'Authorization' => 'Key ' . $this->tryOnServiceToken,
                'Content-Type'  => 'application/json',
            ])->get("https://queue.fal.run/fal-ai/fashn/requests/$requestId");

            $statusCode = $response->status();
            $responseBody = json_decode($response->body(), true);
         
            return $responseBody;
        } catch (\Exception $e) {
            Log::error("Error in getGenerateImage: " . $e->getMessage());
            return null;
        }
    }
}
