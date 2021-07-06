<?php

declare(strict_types=1);

namespace App\Modules\Product\Repositories;

use App\Modules\Product\Exceptions\ProductNotFoundException;
use App\Modules\Product\Exceptions\UnableToSaveProductException;
use App\Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\Collection;

/**
 * Provides CRUD functionalities for Product objects.
 */
class ProductRepository implements ProductRepositoryInterface
{
    /**
     * Get a single product by its id.
     *
     * @param int $id
     *
     * @throws ProductNotFoundException
     *
     * @return Product
     */
    public function getById(int $id): Product
    {
        $product = Product::query()
            ->where(['id' => $id])
            ->first();

        if ($product === null) {
            throw new ProductNotFoundException("A product with the id ${id} was not found.");
        }

        return $product;
    }

    /**
     * Create or update the product.
     *
     * This is just a convenience wrapper for the Eloquent model to allow writing unit tests
     * that need a mock of the CRUD functionalities instead of an actual working model.
     *
     * @param Product $product
     *
     * @throws \Exception
     *
     * @return Product
     */
    public function save(Product $product): Product
    {
        $success = $product->save();

        if (!$success) {
            throw new UnableToSaveProductException("Unable to save the product {$product->getName()}.");
        }

        return $product;
    }

    /**
     * Delete the product.
     *
     * This is just a convenience wrapper for the Eloquent model to allow writing unit tests
     * that need a mock of the CRUD functionalities instead of an actual working model.
     *
     * @param Product $product
     *
     * @return bool|null
     */
    public function delete(Product $product): ?bool
    {
        return $product->delete();
    }

    /**
     * Get all non-deleted products.
     *
     * @param int|null $offset
     * @param int|null $limit
     *
     * @return Collection
     */
    public function getList(?int $offset = null, ?int $limit = null): Collection
    {
        $query = Product::query()
            ->where(['geloescht' => 0]);

        if ($offset !== null) {
            $query->offset($offset);
        }

        if ($limit !== null) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * Get total count of non-deleted products.
     *
     * @return int
     */
    public function getTotalCount(): int
    {
        return Product::query()
            ->where(['geloescht' => 0])
            ->count();
    }
}
