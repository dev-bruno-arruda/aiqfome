<?php

namespace App\Services;

use App\Models\Favorite;
use App\Models\Customer;
use App\Models\Product;
use App\Contracts\BaseServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class FavoriteService
{

    /**
     * Get all favorites for a customer
     *
     * @param int $customerId
     * @return Collection
     */
    public function getCustomerFavorites(int $customerId): Collection
    {
        return Favorite::with('product')
            ->where('customer_id', $customerId)
            ->get();
    }

    /**
     * Add a product to customer's favorites
     *
     * @param int $customerId
     * @param int $productId
     * @return Favorite|null
     * @throws \Exception
     */
    public function addToFavorites(int $customerId, int $productId): ?Favorite
    {
        // Validate customer exists
        $customer = Customer::find($customerId);
        if (!$customer) {
            throw new \Exception('Cliente não encontrado.');
        }

        // Validate product exists in external API and sync to local database
        $fakeStoreService = app(FakeStoreApiService::class);
        $product = $fakeStoreService->syncProduct($productId);
        
        if (!$product) {
            throw new \Exception('Produto não encontrado na API externa.');
        }

        // Check if already favorited
        $existingFavorite = Favorite::where('customer_id', $customerId)
            ->where('product_id', $product->id)
            ->first();

        if ($existingFavorite) {
            throw new \Exception('Produto já está nos favoritos deste cliente.');
        }

        return Favorite::create([
            'customer_id' => $customerId,
            'product_id' => $product->id,
        ]);
    }

    /**
     * Remove a product from customer's favorites
     *
     * @param int $customerId
     * @param int $productId
     * @return bool
     */
    public function removeFromFavorites(int $customerId, int $productId): bool
    {
        $favorite = Favorite::where('customer_id', $customerId)
            ->where('product_id', $productId)
            ->first();

        if (!$favorite) {
            return false;
        }

        return $favorite->delete();
    }

    /**
     * Check if a product is favorited by customer
     *
     * @param int $customerId
     * @param int $productId
     * @return bool
     */
    public function isFavorited(int $customerId, int $productId): bool
    {
        return Favorite::where('customer_id', $customerId)
            ->where('product_id', $productId)
            ->exists();
    }

    /**
     * Get favorite by customer and product
     *
     * @param int $customerId
     * @param int $productId
     * @return Favorite|null
     */
    public function getFavorite(int $customerId, int $productId): ?Favorite
    {
        return Favorite::where('customer_id', $customerId)
            ->where('product_id', $productId)
            ->first();
    }

    /**
     * Get customers who favorited a specific product
     *
     * @param int $productId
     * @return Collection
     */
    public function getCustomersWhoFavorited(int $productId): Collection
    {
        return Favorite::with('customer')
            ->where('product_id', $productId)
            ->get()
            ->pluck('customer');
    }
}
