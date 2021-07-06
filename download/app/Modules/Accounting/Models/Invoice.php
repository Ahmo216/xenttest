<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Models;

use App\Modules\Accounting\DTO\Address;
use App\Modules\Accounting\Models\Casts\InvoiceInvoiceAddressCast;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property Collection $items
 * @property Order $order
 * @property Address invoiceAddress
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
 * @property float $umsatz_netto
 * @property float $soll
 * @property float $steuersatz_normal
 * @property float $steuersatz_ermaessigt
 */
class Invoice extends Model
{
    public $timestamps = false;

    protected $table = 'rechnung';

    protected $casts = [
        'invoiceAddress' => InvoiceInvoiceAddressCast::class,
        'ust_befreit' => 'int',
        'umsatz_netto' => 'float',
        'soll' => 'float',
        'steuersatz_normal' => 'float',
        'steuersatz_ermaessigt' => 'float',
        'ustid' => 'string',
        'adresse' => 'int',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class, 'rechnung');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'auftragid');
    }

    public function creditNotes(): HasMany
    {
        return $this->hasMany(CreditNote::class, 'rechnungid');
    }

    public function getNetShippingCosts(): float
    {
        return $this->items()
            ->join('artikel', 'artikel', '=', 'artikel.id')
            ->where('artikel.porto', '=', '1')
            ->get()
            ->reduce(function (float $result, InvoiceItem $item) {
                return $result + $item->umsatz_netto_einzeln;
            }, 0.0);
    }

    public function getNetTotal(): float
    {
        return $this->getAttribute('umsatz_netto');
    }
}
