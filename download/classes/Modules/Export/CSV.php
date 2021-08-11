<?php
namespace Xentral\Modules\Export;

abstract class CSV {

    /**
     * Returns header for the CSV
     * @return string
     */
    abstract function getHeader(): string;

    /**
     * Returns single CSV formatted row
     */
    abstract function getRow(array $namesValues = array()): string;

}
