<?php

namespace TryOn\Repositories;

use App\Jobs\CreateTryOnDataJob;
use App\Jobs\UpdateTryOnDataJob;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use TryOn\Http\Requests\VoiceFileRequest;
use TryOn\Repositories\Interfaces\TryOnRepositoryInterface;

class TryOnRepository implements TryOnRepositoryInterface
{
    public function __construct() {
        $this->categoryIdentifierApi = config('try-on.cateogry-indetifier-api');
        $this->cateogryIndetifierToken = config('try-on.cateogry-indetifier-token');    
        $this->tryOnServiceToken = config('try-on.try-on-token');
        $this->tryOnServiceApi = config('try-on.try-on-api');
    }

    public function getCategory(string $productName)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->cateogryIndetifierToken,
                'Content-Type' => 'application/json',
            ])->post($this->cateogryIndetifierToken, [
                'model' => 'gpt-4o-mini',
                'messages' => [
                [
                    'role' => 'system',
                    'content' => "You are an AI assistant that categorizes clothing items into one of three categories: 'tops', 'bottoms', or 'one-pieces'. Only return one of these three words as the response."
                ],
                [
                    'role' => 'user',
                    'content' => "Classify the clothing item: '$productName'"
                ]
                ]
            ]);

            return $response->json()['choices'][0]['message']['content'];

        } catch (\Exception $e) {
            Log::error("Error in getCategory: " . $e->getMessage());
            return "unknown";
        }
    }

    public function tryOn(int $userId, array $parameters)
    {
        try {
            $category = $this->getCategory($parameters['message']['productTitle']);
            
            $modelFile = File::where('url', $parameters['message']['image'])->first();
            $garmentFile = File::where('url', $parameters['message']['productImage'])->first();
            
            if (!$modelFile) {
                $modelFile = File::create([
                "user_id" => $userId,
                "url"     => $parameters['message']['image'],
                "format"  => pathinfo(parse_url($parameters['message']['image'], PHP_URL_PATH), PATHINFO_EXTENSION),
                "type"    => 'image',
                "size"    => null,
                ]);
            }

            if (!$garmentFile) {
                $garmentFile = File::create([
                "user_id" => $userId,
                "url"     => $parameters['message']['productImage'],
                "format"  => pathinfo(parse_url($parameters['message']['productImage'], PHP_URL_PATH), PATHINFO_EXTENSION),
                "type"    => 'image',
                "size"    => null,
                ]);
            }

            $response = Http::withHeaders([
                'Authorization' => 'Key ' . $this->tryOnServiceToken,
                'Content-Type'  => 'application/json',
            ])->post($this->tryOnServiceApi, [
                'model_image'       => $modelFile['url'],
                'garment_image'     => $garmentFile['url'],
                'category'          => $category,
                'garment_photo_type'=> 'auto',
                'nsfw_filter'       => true,
                'guidance_scale'    => 2,
                'timesteps'         => 50,
                'seed'              => 42,
                'num_samples'       => 1,
            ]);

            $statusCode = $response->status();
            $responseBody = json_decode($response->body(), true);

            $uuid = Str::uuid()->toString();

            $tryOnData = [
                "uuid"   => $uuid,
                "user_id" => $userId,
                "model_file_id" => $modelFile->id,
                "garment_file_id" => $garmentFile->id,
                "category" => $category ?? null,
                "send_images_result" => $responseBody ?? null,
                "send_images_status_code" => $statusCode,
            ];

            CreateTryOnDataJob::dispatch($tryOnData);

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

    public function getImageStatus($tryOn) 
    {
        try {
            $maxTime = 600;
            $interval = 3; 
            $elapsedTime = 0;

            $statusUrl = $tryOn['send_images_result']['status_url'];
            
            while ($elapsedTime < $maxTime) {
                $response = Http::withHeaders([
                'Authorization' => 'Key ' . $this->tryOnServiceToken,
                'Content-Type'  => 'application/json',
                ])->get($statusUrl);

                $statusCode = $response->status();
                $responseBody = json_decode($response->body(), true);
                
                if (isset($responseBody['status']) && $responseBody['status'] === 'COMPLETED') {
                UpdateTryOnDataJob::dispatch($tryOn['uuid'], [
                    "status_result" => $responseBody ?? null,
                    "status_request_status_code" => $statusCode,
                ]);
                
                return $responseBody;
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
        try {
            $responseUrl = $tryOn['send_images_result']['response_url'];
            $response = Http::withHeaders([
                'Authorization' => 'Key ' . $this->tryOnServiceToken,
                'Content-Type'  => 'application/json',
            ])->get($responseUrl);

            $statusCode = $response->status();
            $responseBody = json_decode($response->body(), true);

            UpdateTryOnDataJob::dispatch($tryOn['uuid'], [
                "generate_result" => $responseBody ?? null,
                "generate_response_status_code" => $statusCode,
            ]);

            return $tryOn;
        } catch (\Exception $e) {
            Log::error("Error in getGenerateImage: " . $e->getMessage());
            return null;
        }
    }
}
