<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\GifSearchRequest;
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
        $request = $request->validated();

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
