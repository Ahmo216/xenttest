<?php

declare(strict_types=1);

namespace Xentral\Modules\CarrierSelect\Data\Comparator;


final class ValueIsNotEmptyComparator implements FilterComparatorInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'not_empty';
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
                return true;
            }
        }

        return false;
    }
}
