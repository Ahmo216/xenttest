<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Models;

use App\Modules\Accounting\DTO\Address;
use App\Modules\Accounting\Models\Casts\OrderDeliveryAddressCast;
use App\Modules\Accounting\Models\Casts\OrderInvoiceAddressCast;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property bool $abweichendelieferadresse
 * @property string $liefername
 * @property Collection $items
 * @property Collection $deliveryNotes
 * @property Address $invoiceAddress
 * @property Address $deliveryAddress
 * @property string $ustid
 * @property string $name
 * @property string $strasse
 * @property string $addresszusatz
 * @property string $ort
 * @property string $plz
 * @property string $bundesstaat
 * @property string $land
 * @property string $lieferstrasse
 * @property string $lieferaddresszusatz
 * @property string $lieferort
 * @property string $lieferplz
 * @property string $lieferbundesstaat
 * @property string $lieferland
 * @property int $adresse
 * @property int $ust_befreit
 * @property float $umsatz_netto
 * @property float $gesamtsumme
 * @property float $steuersatz_normal
 * @property float $steuersatz_ermaessigt
 */
class Order extends Model
{
    public $timestamps = false;

    protected $table = 'auftrag';

    protected $casts = [
        'abweichendelieferadresse' => 'boolean',
        'invoiceAddress' => OrderInvoiceAddressCast::class,
        'deliveryAddress' => OrderDeliveryAddressCast::class,
        'ustid' => 'string',
        'adresse' => 'int',
        'ust_befreit' => 'int',
        'gesamtsumme' => 'float',
        'steuersatz_normal' => 'float',
        'steuersatz_ermaessigt' => 'float',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'auftrag');
    }

    public function deliveryNotes(): HasMany
    {
        return $this->hasMany(DeliveryNote::class, 'auftragid');
    }

    public function hasDifferentDeliveryAddress(): bool
    {
        return $this->abweichendelieferadresse && $this->liefername !== '';
    }
}
