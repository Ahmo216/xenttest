<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Models;

use App\Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property DeliveryNote $deliveryNote
 * @property Product $product
 * @property OrderItem $orderItem
 * @property int $artikel
 * @property int $lieferschein
 * @property float $menge
 * @property string $nummer
 * @property string $bezeichnung
 * @property string $umsatzsteuer
 */
class DeliveryNoteItem extends Model
{
    protected $table = 'lieferschein_position';

    public function deliveryNote(): BelongsTo
    {
        return $this->belongsTo(DeliveryNote::class, 'lieferschein');
    }

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'artikel');
    }

    public function orderItem(): HasOne
    {
        return $this->hasOne(OrderItem::class, 'id', 'auftrag_position_id');
    }
}
