<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Models;

use App\Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $artikel
 * @property int $gutschrift
 * @property float|null $steuersatz
 * @property float $menge
 * @property float $preis
 * @property string $nummer
 * @property string $bezeichnung
 * @property string $umsatzsteuer
 * @property Product $product
 */
class CreditNoteItem extends Model
{
    public $timestamps = false;

    protected $table = 'gutschrift_position';

    public function creditNote(): BelongsTo
    {
        return $this->belongsTo(CreditNote::class, 'gutschrift');
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
