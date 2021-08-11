<?php

declare(strict_types=1);

namespace Xentral\Modules\CarrierSelect\Data\Comparator;


final class ValueSmallerThanEqualComparator implements FilterComparatorInterface
{

    public function getName(): string
    {
        return '<=';
    }

    /**
     * @param array $filterValues
     * @param array $values
     *
     * @return bool
     */
    public function matches(array $filterValues, array $values): bool
    {
        $filterValue = (float)reset($filterValues);

        $sum = 0;
        foreach ($values as $value) {
            $sum += (float)$value;
        }

        return $sum <= $filterValue;
    }
}
