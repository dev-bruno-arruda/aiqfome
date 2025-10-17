<?php

namespace App\Http\Controllers;

use App\Services\FavoriteService;
use App\Http\Requests\FavoriteRequest;
use App\Http\Resources\FavoriteResource;
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

            return response()->json([
                'message' => 'Favoritos listados com sucesso',
                'status' => 'success',
                'data' => FavoriteResource::collection($favorites)
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao listar favoritos',
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(FavoriteRequest $request, int $customerId): JsonResponse
    {
        try {
            $favorite = $this->favoriteService->addToFavorites(
                $customerId,
                $request->validated()['product_id']
            );

            return response()->json([
                'message' => 'Produto adicionado aos favoritos com sucesso',
                'status' => 'success',
                'data' => new FavoriteResource($favorite)
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao adicionar favorito',
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function show(int $customerId, int $productId): JsonResponse
    {
        try {
            $favorite = $this->favoriteService->getFavorite($customerId, $productId);

            if (!$favorite) {
                return response()->json([
                    'message' => 'Favorito não encontrado',
                    'status' => 'error'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'message' => 'Favorito encontrado com sucesso',
                'status' => 'success',
                'data' => new FavoriteResource($favorite)
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar favorito',
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(int $customerId, int $productId): JsonResponse
    {
        try {
            $deleted = $this->favoriteService->removeFromFavorites($customerId, $productId);

            if (!$deleted) {
                return response()->json([
                    'message' => 'Favorito não encontrado',
                    'status' => 'error'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'message' => 'Produto removido dos favoritos com sucesso',
                'status' => 'success'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao remover favorito',
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function check(int $customerId, int $productId): JsonResponse
    {
        try {
            $isFavorited = $this->favoriteService->isFavorited($customerId, $productId);

            return response()->json([
                'message' => 'Status do favorito verificado com sucesso',
                'status' => 'success',
                'data' => [
                    'is_favorited' => $isFavorited
                ]
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao verificar favorito',
                'status' => 'error',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
