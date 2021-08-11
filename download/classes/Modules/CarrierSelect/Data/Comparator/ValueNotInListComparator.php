<?php

declare(strict_types=1);

namespace Xentral\Modules\CarrierSelect\Data\Comparator;


final class ValueNotInListComparator implements FilterComparatorInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'not_in';
    }

    /**
     * @param array $filterValues
     * @param array $values
     *
     * @return bool
     */
    public function matches(array $filterValues, array $values): bool
    {
        foreach ($values as $value) {
            if (in_array($value, $filterValues, false)) {
                return false;
            }
        }

        return true;
    }
}
