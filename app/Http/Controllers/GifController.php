<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\GifFavoriteRequest;
use App\Http\Requests\GifFindRequest;
use App\Http\Requests\GifSearchRequest;
use App\Models\UserFavoriteGif;
use App\Services\GiphyService;
use Exception;

class GifController extends Controller
{
    private GiphyService $giphyService;

    public function __construct(GiphyService $giphyService)
    {
        $this->giphyService = $giphyService;
    }

    public function search(GifSearchRequest $request)
    {
        return $this->handleGifRequest($request->validated());
    }

    public function find(GifFindRequest $request)
    {
        return $this->handleGifRequest($request->validated());
    }

    public function favorite(GifFavoriteRequest $request)
    {
        return response()->json([
            'status' => true,
            'data' => UserFavoriteGif::create($request->validated()),
        ], 201);
    }

    private function handleGifRequest(array $request)
    {
        try {
            return response()->json([
                'status' => true,
                'data' => $this->giphyService->search($request),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'data' => $e->getMessage(),
            ], 400);
        }
    }
}
