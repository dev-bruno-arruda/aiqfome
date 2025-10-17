<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FakeStoreApiService
{
    private const BASE_URL = 'https://fakestoreapi.com';
    private const TIMEOUT = 30;

    /**
     * Get all products from FakeStore API
     *
     * @return array
     */
    public function getAllProducts(): array
    {
        try {
            $response = Http::timeout(self::TIMEOUT)
                ->get(self::BASE_URL . '/products');

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Failed to fetch products from FakeStore API', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [];
        } catch (\Exception $e) {
            Log::error('Exception while fetching products from FakeStore API', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [];
        }
    }

    /**
     * Get a specific product by ID from FakeStore API
     *
     * @param int $productId
     * @return array|null
     */
    public function getProductById(int $productId): ?array
    {
        try {
            $response = Http::timeout(self::TIMEOUT)
                ->get(self::BASE_URL . "/products/{$productId}");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Failed to fetch product from FakeStore API', [
                'product_id' => $productId,
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Exception while fetching product from FakeStore API', [
                'product_id' => $productId,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return null;
        }
    }

    /**
     * Validate if a product exists in FakeStore API
     *
     * @param int $productId
     * @return bool
     */
    public function validateProduct(int $productId): bool
    {
        $product = $this->getProductById($productId);
        return $product !== null;
    }

    /**
     * Sync product data from FakeStore API to local database
     *
     * @param int $productId
     * @return Product|null
     */
    public function syncProduct(int $productId): ?Product
    {
        $productData = $this->getProductById($productId);
        
        if (!$productData) {
            return null;
        }

        return Product::updateOrCreate(
            ['external_id' => $productId],
            [
                'title' => $productData['title'],
                'price' => $productData['price'],
                'description' => $productData['description'],
                'category' => $productData['category'],
                'image' => $productData['image'],
                'rating_rate' => $productData['rating']['rate'] ?? null,
                'rating_count' => $productData['rating']['count'] ?? null,
            ]
        );
    }
}
