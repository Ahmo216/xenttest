<?php

declare(strict_types=1);

namespace Xentral\Modules\CarrierSelect\Data\Comparator;


final class ValueIsEmptyComparator implements FilterComparatorInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'empty';
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
            if (!empty($value)) {
                return false;
            }
        }

        return true;
    }
}
