<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Models;

use App\Modules\Accounting\DTO\Address;
use App\Modules\Accounting\Models\Casts\CreditNoteInvoiceAddressCast;
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
 * @property float $umsatz_netto
 * @property float $soll
 * @property float $steuersatz_normal
 * @property float $steuersatz_ermaessigt
 */
class CreditNote extends Model
{
    public $timestamps = false;

    protected $table = 'gutschrift';

    protected $casts = [
        'invoiceAddress' => CreditNoteInvoiceAddressCast::class,
        'umsatz_netto' => 'float',
        'soll' => 'float',
        'steuersatz_normal' => 'float',
        'steuersatz_ermaessigt' => 'float',
        'ustid' => 'string',
        'adresse' => 'int',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'id', 'rechnungid');
    }

    public function items(): HasMany
    {
        return $this->hasMany(CreditNoteItem::class, 'gutschrift');
    }
}
