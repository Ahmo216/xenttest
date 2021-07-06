<?php

declare(strict_types=1);

namespace App\Modules\Product\Repositories;

use App\Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\Collection;

/**
 * Repository interface that defines CRUD functionalities for Product objects.
 */
interface ProductRepositoryInterface
{
    /**
     * Get a single product by its id.
     */
    public function getById(int $id): Product;

    /**
     * Create or update the product.
     */
    public function save(Product $product): Product;

    /**
     * Delete the product.
     */
    public function delete(Product $product): ?bool;

    /**
     * Get all non-deleted products.
     */
    public function getList(?int $offset = null, ?int $limit = null): Collection;

    /**
     * Get total count of non-deleted products.
     */
    public function getTotalCount(): int;
}
