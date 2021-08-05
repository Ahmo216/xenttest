<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Models;

use App\Modules\Accounting\DTO\Address;
use App\Modules\Accounting\Models\Casts\DeliveryNoteDeliveryAddressCast;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property Collection $items
 * @property Order $order
 * @property Address $deliveryAddress
 * @property string $ustid
 * @property string $name
 * @property string $strasse
 * @property string $addresszusatz
 * @property string $ort
 * @property string $plz
 * @property string $bundesstaat
 * @property string $land
 * @property int $adresse
 * @property int $ust_befreit
 */
class DeliveryNote extends Model
{
    public $timestamps = false;

    protected $table = 'lieferschein';

    protected $casts = [
        'deliveryAddress' => DeliveryNoteDeliveryAddressCast::class,
        'ustid' => 'string',
        'ust_befreit' => 'int',
        'umsatz_netto' => 'float',
        'soll' => 'float',
        'steuersatz_normal' => 'float',
        'steuersatz_ermaessigt' => 'float',
        'adresse' => 'int',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'auftragid');
    }

    public function items(): HasMany
    {
        return $this->hasMany(DeliveryNoteItem::class, 'lieferschein');
    }
}
