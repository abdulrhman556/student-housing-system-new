<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFavoriteRequest;
use App\Http\Resources\FavoriteResource;
use App\Models\Favorite;
use App\Services\FavoriteService;
use Illuminate\Http\JsonResponse;

class FavoriteController extends Controller
{
    public function __construct(
        protected FavoriteService $favoriteService
    ) {
    }

    public function index()
    {
        return FavoriteResource::collection(
            $this->favoriteService->index()
        );
    }

    public function store(StoreFavoriteRequest $request): JsonResponse
    {
        try {

            $favorite = $this->favoriteService->store(
                $request->validated()
            );

            return response()->json([
                'success' => true,
                'message' => 'Property added to favorites.',
                'data' => new FavoriteResource($favorite),
            ], 201);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);

        }
    }

    public function destroy(Favorite $favorite): JsonResponse
    {
        $this->favoriteService->destroy($favorite);

        return response()->json([
            'success' => true,
            'message' => 'Property removed from favorites.',
        ]);
    }
}
