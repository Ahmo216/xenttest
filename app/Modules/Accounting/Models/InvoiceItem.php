<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Models;

use App\Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property Invoice $invoice
 * @property Product $product
 * @property OrderItem $orderItem
 * @property int $artikel
 * @property int $rechnung
 * @property float|null $steuersatz
 * @property float $menge
 * @property float $preis
 * @property string $nummer
 * @property string $bezeichnung
 * @property string $umsatzsteuer
 */
class InvoiceItem extends Model
{
    public $timestamps = false;

    protected $table = 'rechnung_position';

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'rechnung');
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
