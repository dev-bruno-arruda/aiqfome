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

    /**
     * Trata erros específicos de favoritos
     */
    private function handleFavoriteError(\Exception $e, string $operation): JsonResponse
    {
        $message = $e->getMessage();
        
        if ($this->isDuplicateFavoriteError($message)) {
            return ApiResponse::error('favorites.create.already_exists', [], 422);
        }
        
        if ($this->isCustomerNotFoundError($message)) {
            return ApiResponse::error('favorites.customer_not_found', [], 404);
        }
        
        if ($this->isProductNotFoundError($message)) {
            return ApiResponse::error('favorites.product_not_found', [], 404);
        }
        
        return ApiResponse::error("favorites.{$operation}.error", [], 422);
    }

    /**
     * Verifica se é erro de produto já favoritado
     */
    private function isDuplicateFavoriteError(string $message): bool
    {
        return str_contains($message, 'already_exists') || 
               str_contains($message, 'já está nos favoritos') ||
               str_contains($message, 'already in this customer');
    }

    /**
     * Verifica se é erro de cliente não encontrado
     */
    private function isCustomerNotFoundError(string $message): bool
    {
        return str_contains($message, 'customer_not_found') || 
               str_contains($message, 'Cliente não encontrado');
    }


    /**
     * @OA\Get(
     *     path="/customers/{customerId}/favorites",
     *     tags={"Favorites"},
     *     summary="Listar favoritos do cliente",
     *     description="Retorna uma lista de produtos favoritos de um cliente específico",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="customerId",
     *         in="path",
     *         description="ID do cliente",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Favoritos listados com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="object", @OA\Property(property="key", type="string"), @OA\Property(property="text", type="string")),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="favorites", type="array", @OA\Items(type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="customer_id", type="integer", example=1),
     *                     @OA\Property(property="product_id", type="integer", example=1),
     *                     @OA\Property(property="product", type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="title", type="string", example="Produto Exemplo"),
     *                         @OA\Property(property="price", type="number", format="float", example=29.99)
     *                     ),
     *                     @OA\Property(property="created_at", type="string", format="date-time")
     *                 ))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token inválido ou expirado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cliente não encontrado"
     *     )
     * )
     */
    public function index(int $customerId): JsonResponse
    {
        try {
            $favorites = $this->favoriteService->getCustomerFavorites($customerId);
            return ApiResponse::success('favorites.list.success', [
                'favorites' => FavoriteResource::collection($favorites)
            ]);
        } catch (\Exception $e) {
            return $this->handleFavoriteError($e, 'list');
        }
    }

    /**
     * @OA\Post(
     *     path="/customers/{customerId}/favorites",
     *     tags={"Favorites"},
     *     summary="Adicionar produto aos favoritos",
     *     description="Adiciona um produto à lista de favoritos de um cliente",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="customerId",
     *         in="path",
     *         description="ID do cliente",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"product_id"},
     *             @OA\Property(property="product_id", type="integer", example=1, description="ID do produto da API externa")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Produto adicionado aos favoritos com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="object", @OA\Property(property="key", type="string"), @OA\Property(property="text", type="string")),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="favorite", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="customer_id", type="integer", example=1),
     *                     @OA\Property(property="product_id", type="integer", example=1),
     *                     @OA\Property(property="product", type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="title", type="string", example="Produto Exemplo"),
     *                         @OA\Property(property="price", type="number", format="float", example=29.99)
     *                     ),
     *                     @OA\Property(property="created_at", type="string", format="date-time")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dados inválidos ou produto já favoritado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="object", 
     *                 @OA\Property(property="key", type="string", example="favorites.create.already_exists"),
     *                 @OA\Property(property="text", type="string", example="Produto já está nos favoritos deste cliente")
     *             ),
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="data", type="array", @OA\Items())
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token inválido ou expirado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cliente ou produto não encontrado"
     *     )
     * )
     */
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
            return $this->handleFavoriteError($e, 'create');
        }
    }

    /**
     * @OA\Get(
     *     path="/customers/{customerId}/favorites/{productId}",
     *     tags={"Favorites"},
     *     summary="Obter favorito específico",
     *     description="Retorna um produto favorito específico de um cliente",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="customerId",
     *         in="path",
     *         description="ID do cliente",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="productId",
     *         in="path",
     *         description="ID do produto",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Favorito encontrado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="object", @OA\Property(property="key", type="string"), @OA\Property(property="text", type="string")),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="favorite", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="customer_id", type="integer", example=1),
     *                     @OA\Property(property="product_id", type="integer", example=1),
     *                     @OA\Property(property="product", type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="title", type="string", example="Produto Exemplo"),
     *                         @OA\Property(property="price", type="number", format="float", example=29.99)
     *                     ),
     *                     @OA\Property(property="created_at", type="string", format="date-time")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Favorito não encontrado"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token inválido ou expirado"
     *     )
     * )
     */
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
            return $this->handleFavoriteError($e, 'show');
        }
    }

    /**
     * @OA\Delete(
     *     path="/customers/{customerId}/favorites/{productId}",
     *     tags={"Favorites"},
     *     summary="Remover produto dos favoritos",
     *     description="Remove um produto da lista de favoritos de um cliente",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="customerId",
     *         in="path",
     *         description="ID do cliente",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="productId",
     *         in="path",
     *         description="ID do produto",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produto removido dos favoritos com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/ApiResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Favorito não encontrado"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token inválido ou expirado"
     *     )
     * )
     */
    public function destroy(int $customerId, int $productId): JsonResponse
    {
        try {
            $deleted = $this->favoriteService->removeFromFavorites($customerId, $productId);

            if (!$deleted) {
                return ApiResponse::error('favorites.delete.not_found', [], 404);
            }

            return ApiResponse::success('favorites.delete.success');
        } catch (\Exception $e) {
            return $this->handleFavoriteError($e, 'delete');
        }
    }

    /**
     * @OA\Get(
     *     path="/customers/{customerId}/favorites/{productId}/check",
     *     tags={"Favorites"},
     *     summary="Verificar se produto é favorito",
     *     description="Verifica se um produto específico está na lista de favoritos de um cliente",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="customerId",
     *         in="path",
     *         description="ID do cliente",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="productId",
     *         in="path",
     *         description="ID do produto",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Status do favorito verificado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="object", @OA\Property(property="key", type="string"), @OA\Property(property="text", type="string")),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="is_favorited", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token inválido ou expirado"
     *     )
     * )
     */
    public function check(int $customerId, int $productId): JsonResponse
    {
        try {
            $isFavorited = $this->favoriteService->isFavorited($customerId, $productId);

            return ApiResponse::success('favorites.check.success', [
                'is_favorited' => $isFavorited
            ]);
        } catch (\Exception $e) {
            return $this->handleFavoriteError($e, 'check');
        }
    }
}
