<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Regions\EU\Models;

use Illuminate\Database\Eloquent\Model;

class TurnoverThreshold extends Model
{
    public const NO_STORAGE = 0;

    public const STORAGE = 1;

    public const MAIN_STORAGE = 2;

    public $timestamps = false;

    protected $table = 'lieferschwelle';

    protected $attributes = [
        'ursprungsland' => '',
        'empfaengerland' => '',
        'lieferschwelleeur' => 0,
        'ustid' => '',
        'steuersatznormal' => 0,
        'steuersatzermaessigt' => 0,
        'steuersatzspezial' => 0,
        'steuersatzspezialursprungsland' => 0,
        'erloeskontonormal' => '',
        'erloeskontoermaessigt' => '',
        'erloeskontobefreit' => '',
        'ueberschreitungsdatum' => null,
        'aktuellerumsatz' => 0,
        'current_revenue_in_eur' => 0,
        'preiseanpassen' => false,
        'verwenden' => false,
        'jahr' => '',
        'use_storage' => 0,
        'currency' => null,
    ];

    protected $casts = [
        'ursprungsland' => 'string',
        'empfaengerland' => 'string',
        'lieferschwelleeur' => 'float',
        'ustid' => 'string',
        'steuersatznormal' => 'float',
        'steuersatzermaessigt' => 'float',
        'steuersatzspezial' => 'float',
        'steuersatzspezialursprungsland' => 'float',
        'erloeskontonormal' => 'string',
        'erloeskontoermaessigt' => 'string',
        'erloeskontobefreit' => 'string',
        'ueberschreitungsdatum' => 'string',
        'aktuellerumsatz' => 'float',
        'current_revenue_in_eur' => 'float',
        'preiseanpassen' => 'bool',
        'verwenden' => 'bool',
        'jahr' => 'string',
        'currency' => 'string',
        'use_storage' => 'int',
    ];

    public function getTaxRateNormal(): ?float
    {
        $taxRate = $this->getAttribute('steuersatznormal');

        return $taxRate < 0 ? null : $taxRate;
    }

    public function setTaxRateNormal(?float $taxRate): self
    {
        return $this->setAttribute('steuersatznormal', $taxRate === null ? -1 : $taxRate);
    }

    public function setTaxRateReduced(?float $taxRate): self
    {
        return $this->setAttribute('steuersatzermaessigt', $taxRate === null ? -1 : $taxRate);
    }

    public function setTaxRateSpecial(?float $taxRate): self
    {
        return $this->setAttribute('steuersatzspezial', $taxRate === null ? -1 : $taxRate);
    }

    public function addToCurrentRevenue(float $revenue, float $exchangeRate = 0): self
    {
        $revenueInEUR = $exchangeRate > 0 ? $revenue / $exchangeRate : $revenue;
        $currentRevenueInEUR = $this->getAttribute('current_revenue_in_eur') + $revenueInEUR;
        $currentRevenue = $this->getAttribute('aktuellerumsatz') + $revenue;
        $this->setAttribute('aktuellerumsatz', $currentRevenue);
        $this->setAttribute('current_revenue_in_eur', $currentRevenueInEUR);

        return $this;
    }
}
