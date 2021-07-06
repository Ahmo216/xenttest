<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Regions\EU\Models;

use App\Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TurnoverThresholdProduct extends Model
{
    public $timestamps = false;

    protected $table = 'lieferschwelle_artikel';

    protected $attributes = [
        'artikel' => 0,
        'empfaengerland' => '',
        'steuersatz' => 0,
        'bemerkung' => '',
        'revenue_account' => '',
        'aktiv' => true,
    ];

    protected $casts = [
        'artikel' => 'int',
        'empfaengerland' => 'string',
        'steuersatz' => 'float',
        'bemerkung' => 'string',
        'revenue_account' => 'string',
        'aktiv' => 'bool',
    ];

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'artikel');
    }

    public function getProductId(): int
    {
        return $this->getAttribute('artikel');
    }

    public function setProductId(int $productId): self
    {
        return $this->setAttribute('artikel', $productId);
    }

    public function getDeliveryCountry(): string
    {
        return $this->getAttribute('empfaengerland');
    }

    public function setDeliveryCountry(string $country): self
    {
        return $this->setAttribute('empfaengerland', $country);
    }

    public function getTaxRate(): float
    {
        return $this->getAttribute('steuersatz');
    }

    public function setTaxRate(float $taxRate): self
    {
        return $this->setAttribute('steuersatz', $taxRate);
    }

    public function getDescription(): string
    {
        return $this->getAttribute('bemerkung');
    }

    public function setDescription(string $description): self
    {
        return $this->setAttribute('bemerkung', $description);
    }

    public function isActive(): bool
    {
        return $this->getAttribute('aktiv');
    }

    public function setActive(bool $active): self
    {
        return $this->setAttribute('aktiv', $active);
    }

    public function setRevenueAccount(string $revenueAccount): self
    {
        return $this->setAttribute('revenue_account', $revenueAccount);
    }
}
