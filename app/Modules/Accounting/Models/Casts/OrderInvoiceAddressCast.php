<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Models\Casts;

use App\Modules\Accounting\DTO\Address;
use App\Modules\Accounting\Exceptions\InvalidArgumentException;
use App\Modules\Accounting\Models\Order;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class OrderInvoiceAddressCast implements CastsAttributes
{
    /**
     * @param Model  $model
     * @param string $key
     * @param mixed  $value
     * @param array  $attributes
     *
     * @return Address
     */
    public function get($model, string $key, $value, array $attributes): Address
    {
        if (!$model instanceof Order) {
            throw new InvalidArgumentException();
        }

        return (new Address())
            ->setName($model->name)
            ->setCity($model->ort)
            ->setStreet($model->strasse)
            ->setZip($model->plz)
            ->setStateCode($model->bundesstaat)
            ->setAdditionalInformation($model->addresszusatz)
            ->setCountryCode($model->land)
            ->setVatId($model->ustid);
    }

    /**
     * @param Model   $model
     * @param string  $key
     * @param Address $value
     * @param array   $attributes
     *
     * @return array
     */
    public function set($model, string $key, $value, array $attributes): array
    {
        if (!$value instanceof Address) {
            throw new InvalidArgumentException();
        }
        if (!$model instanceof Order) {
            throw new InvalidArgumentException();
        }

        if ($model->hasDifferentDeliveryAddress()) {
            return [
                'liefername' => $value->getName(),
                'lieferort' => $value->getCity(),
                'lieferstrasse' => $value->getStreet(),
                'lieferplz' => $value->getZip(),
                'lieferbundesstaat' => $value->getStateCode(),
                'lieferland' => $value->getCountryCode(),
                'lieferaddresszusatz' => $value->getAdditionalInformation(),
                'ustid' => $value->getVatId(),
            ];
        }

        return [
            'name' => $value->getName(),
            'ort' => $value->getCity(),
            'strasse' => $value->getStreet(),
            'plz' => $value->getZip(),
            'bundesstaat' => $value->getStateCode(),
            'land' => $value->getCountryCode(),
            'addresszusatz' => $value->getAdditionalInformation(),
            'ustid' => $value->getVatId(),
        ];
    }
}
