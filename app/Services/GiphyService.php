<?php

namespace App\Services;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Http;

class GiphyService
{
    private const SEARCH_URI = '/v1/gifs/search';
    private const FIND_URI = '/v1/gifs';

    private string $apiKey;
    private string $apiEndpoint;

    public function __construct()
    {
        $this->apiKey = env('GIPHY_API_KEY');
        $this->apiEndpoint = env('GIPHY_API_ENDPOINT');
    }

    public function search(array $request)
    {
        try {
            $uri = isset($request['id']) ? self::FIND_URI . '/' . $request['id'] : self::SEARCH_URI;
            $response = $this->makeRequest($uri, $request);

            if (empty($response)) {
                throw new Exception('No results found');
            }

            return $response;
        } catch (Exception $e) {
            throw new HttpException(400, $e->getMessage());
        }
    }

    private function makeRequest(string $uri, array $request)
    {
        $response = Http::get($this->apiEndpoint . $uri, $this->prepareRequestData($request));

        if (!$response->successful()) {
            throw new Exception('Error al obtener los datos de Giphy');
        }

        return $response->json()['data'] ?? [];
    }

    private function prepareRequestData(array $request): array
    {
        return array_filter([
            'api_key' => $this->apiKey,
            'q' => $request['query'] ?? null,
            'offset' => $request['offset'] ?? null,
            'limit' => $request['limit'] ?? null
        ], fn($value) => !is_null($value));
    }
}
