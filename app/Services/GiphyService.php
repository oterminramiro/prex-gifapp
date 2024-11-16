<?php

namespace App\Services;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Http;

class GiphyService
{
    private const SEARCH_URI = '/v1/gifs/search';

    public function search(array $request)
    {
        try {
            $response = $this->makeRequest($request);
    
            return $response->json()['data'];
        } catch (Exception $e) {
            throw new HttpException(400, $e->getMessage());
        }
    }

    private function makeRequest(array $request)
    {
        $data = [
            'api_key' => env('GIPHY_API_KEY'),
            'q' => $request['query'],
            'offset' => $request['offset'] ?? null,
            'limit'  => $request['limit'] ?? null
        ];

        $data = array_filter($data, fn($value) => !is_null($value));

        return Http::get(env('GIPHY_API_ENDPOINT') . self::SEARCH_URI, $data);
    }
}
