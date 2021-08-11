<?php

declare(strict_types=1);

namespace Xentral\Modules\CarrierSelect\Data\Comparator;


final class ValueIsEqualComparator implements FilterComparatorInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return '=';
    }

    /**
     * @param array $filterValues
     * @param array $values
     *
     * @return bool
     */
    public function matches(array $filterValues, array $values): bool
    {
        $firstValue = (string)reset($filterValues);
        foreach ($values as $value) {
            if ((string)$value !== $firstValue) {
                return false;
            }
        }

        return true;
    }
}
