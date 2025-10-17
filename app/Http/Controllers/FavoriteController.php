<?php

namespace App\Http\Controllers;

use App\Services\FavoriteService;
use App\Http\Requests\FavoriteRequest;
use App\Http\Resources\FavoriteResource;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class FavoriteController extends Controller
{
    protected $favoriteService;

    public function __construct(FavoriteService $favoriteService)
    {
        $this->favoriteService = $favoriteService;
    }

    public function index(int $customerId): JsonResponse
    {
        try {
            $favorites = $this->favoriteService->getCustomerFavorites($customerId);
            return ApiResponse::success('favorites.list.success', [
                'favorites' => FavoriteResource::collection($favorites)
            ]);
        } catch (\Exception $e) {
            return ApiResponse::error('favorites.list.error');
        }
    }

    public function store(FavoriteRequest $request, int $customerId): JsonResponse
    {
        try {
            $favorite = $this->favoriteService->addToFavorites(
                $customerId,
                $request->validated()['product_id']
            );

            return ApiResponse::success('favorites.create.success', [
                'favorite' => new FavoriteResource($favorite)
            ], 201);
        } catch (\Exception $e) {
            return ApiResponse::error('favorites.create.error', [], 422);
        }
    }

    public function show(int $customerId, int $productId): JsonResponse
    {
        try {
            $favorite = $this->favoriteService->getFavorite($customerId, $productId);

            if (!$favorite) {
                return ApiResponse::error('favorites.show.not_found', [], 404);
            }

            return ApiResponse::success('favorites.show.success', [
                'favorite' => new FavoriteResource($favorite)
            ]);
        } catch (\Exception $e) {
            return ApiResponse::error('favorites.show.error');
        }
    }

    public function destroy(int $customerId, int $productId): JsonResponse
    {
        try {
            $deleted = $this->favoriteService->removeFromFavorites($customerId, $productId);

            if (!$deleted) {
                return ApiResponse::error('favorites.delete.not_found', [], 404);
            }

            return ApiResponse::success('favorites.delete.success');
        } catch (\Exception $e) {
            return ApiResponse::error('favorites.delete.error');
        }
    }

    public function check(int $customerId, int $productId): JsonResponse
    {
        try {
            $isFavorited = $this->favoriteService->isFavorited($customerId, $productId);

            return ApiResponse::success('favorites.check.success', [
                'is_favorited' => $isFavorited
            ]);
        } catch (\Exception $e) {
            return ApiResponse::error('favorites.check.error');
        }
    }
}
