<?php

declare(strict_types=1);

namespace Xentral\Modules\CarrierSelect\Data\Comparator;

interface FilterComparatorInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param array $filterValues
     * @param array $values
     *
     * @return bool
     */
    public function matches(array $filterValues, array $values): bool;
}
