<?php

declare(strict_types=1);

namespace Xentral\Modules\CarrierSelect\Data\Comparator;


final class ValueBetweenComparator implements FilterComparatorInterface
{

    public function getName(): string
    {
        return 'between';
    }

    /**
     * @param array $filterValues
     * @param array $values
     *
     * @return bool
     */
    public function matches(array $filterValues, array $values): bool
    {
        $sum = 0;
        foreach ($values as $value) {
            $sum += (float)$value;
        }
        $filterValuesKey = array_keys($filterValues);
        $filterValue = (float)reset($filterValues);
        if (count($filterValuesKey) > 1) {
            $firstValue = (float)$filterValues[$filterValuesKey[0]];
            $secondValue = (float)$filterValues[$filterValuesKey[1]];
            if ($firstValue > $secondValue) {
                return $sum >= $secondValue && $sum <= $firstValue;
            }
            if ($firstValue < $secondValue) {
                return $sum >= $firstValue && $sum <= $secondValue;
            }

            return round($sum, 4) === round($firstValue, 4);
        }
        if (count($filterValues) === 0) {
            return $sum >= 0;
        }

        return $sum >= $filterValue;
    }
}
