<?php

namespace Xentral\Modules\Shopimport\Traits;

use Illuminate\Support\Arr;

trait HasSettings
{
    /** @var int */
    protected $rootCategoryId;

    /**
     * Extract shop root category id from $settings array.
     */
    protected function setupRootCategoryIdFrom(array $settings): void
    {
        $this->rootCategoryId = (int)Arr::get(
            $settings,
            'felder.category_root_id',
            // Try to extract in old way to keep legacy support
            Arr::get($settings, 'felder.rootcategoryid', 0)
        );
    }
}
