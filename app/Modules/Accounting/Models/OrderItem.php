<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Models;

use App\Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $artikel
 * @property int $auftrag
 * @property float|null $steuersatz
 * @property float $menge
 * @property float $preis
 * @property string $nummer
 * @property string $bezeichnung
 * @property string $umsatzsteuer
 * @property Product $product
 */
class OrderItem extends Model
{
    protected $table = 'auftrag_position';

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'artikel');
    }
}
