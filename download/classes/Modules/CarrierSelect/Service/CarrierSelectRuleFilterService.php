<?php

declare(strict_types=1);

namespace Xentral\Modules\CarrierSelect\Service;

use Xentral\Components\Database\Database;
use Xentral\Modules\CarrierSelect\Data\CarrierSelectRuleFilter;
use Xentral\Modules\CarrierSelect\Exception\InvalidArgumentException;
use Xentral\Modules\CarrierSelect\Exception\RuntimeException;

final class CarrierSelectRuleFilterService
{
    /** @var Database $db */
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * @param int                     $carrierSelectRuleId
     * @param CarrierSelectRuleFilter $ruleFilter
     *
     * @return int
     */
    public function createRuleFilter(int $carrierSelectRuleId, CarrierSelectRuleFilter $ruleFilter): int
    {
        $this->ensureRuleExists($carrierSelectRuleId);
        $this->db->perform(
            'INSERT INTO `carrierselect_rule_filter` 
            (`carrierselect_rule_id`, `filter_field`, `filter_comparator`) 
            VALUES (:carrier_select_rule_id, :filter_field, :filter_comparator )',
            [
                'carrier_select_rule_id' => $carrierSelectRuleId,
                'filter_field'           => $ruleFilter->getField()->getName(),
                'filter_comparator'      => $ruleFilter->getComparator()->getName(),
            ]
        );

        $carrierSelectRuleFilterId = $this->db->lastInsertId();

        foreach ($ruleFilter->getValues() as $value) {
            $this->createFilterValues($carrierSelectRuleFilterId, $value);
        }

        return $carrierSelectRuleFilterId;
    }

    /**
     * @param CarrierSelectRuleFilter $ruleFilter
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function updateRuleFilter(CarrierSelectRuleFilter $ruleFilter): void
    {
        $ruleFilterId = $ruleFilter->getRuleFilterId();
        if ($ruleFilterId === null) {
            throw new RuntimeException('ruleFilterId must not be empty');
        }
        $this->get($ruleFilterId);
        $this->db->perform(
            'UPDATE `carrierselect_rule_filter` 
            SET `filter_field` = :filter_field,
            `filter_comparator` = :filter_comparator
            WHERE `id` = :carrier_select_rule_filter_id',
            [
                'carrier_select_rule_filter_id' => $ruleFilterId,
                'filter_field'                  => $ruleFilter->getField()->getName(),
                'filter_comparator'             => $ruleFilter->getComparator()->getName(),
            ]
        );

        $filterToIdInDb = $this->getValuesFromDbState($ruleFilterId);
        $filterInDb = array_map('strval', array_keys($filterToIdInDb));
        $values = $ruleFilter->getValues();
        foreach ($values as $value) {
            if (!in_array($value, $filterInDb)) {
                $this->createFilterValues($ruleFilterId, $value);
            }
        }
        $toDelete = array_diff($filterInDb, $values);
        foreach ($toDelete as $value) {
            $this->deleteFilterValue($filterToIdInDb[$value]);
        }
    }

    /**
     * @param int $carrierSelectRuleFilterId
     *
     * @return CarrierSelectRuleFilter
     */
    public function get(int $carrierSelectRuleFilterId): CarrierSelectRuleFilter
    {
        $carrierSelectRuleFilter = $this->db->fetchRow(
            'SELECT * FROM `carrierselect_rule_filter` WHERE `id` = :carrier_select_rule_filter_id',
            ['carrier_select_rule_filter_id' => $carrierSelectRuleFilterId]
        );
        if (empty($carrierSelectRuleFilter)) {
            throw new RuntimeException('Filter not found');
        }

        $carrierSelectRuleFilter['values'] = array_map(
            'strval',
            array_keys(
                $this->getValuesFromDbState($carrierSelectRuleFilterId)
            )
        );

        return CarrierSelectRuleFilter::fromDbState($carrierSelectRuleFilter);
    }

    /**
     * @param int $carrierSelectRuleId
     *
     * @return CarrierSelectRuleFilter[]
     */
    public function getRuleFiltersByRuleId(int $carrierSelectRuleId): array
    {
        $carrierSelectRuleFilterIds = $this->db->fetchCol(
            'SELECT `id`
            FROM `carrierselect_rule_filter` 
            WHERE `carrierselect_rule_id` = :carrier_select_rule_id',
            ['carrier_select_rule_id' => $carrierSelectRuleId]
        );
        $carrierSelectRuleFilters = [];
        foreach ($carrierSelectRuleFilterIds as $carrierSelectRuleFilterId) {
            $carrierSelectRuleFilters[] = $this->get($carrierSelectRuleFilterId);
        }

        return $carrierSelectRuleFilters;
    }

    /**
     * @param int $carrierSelectRuleFilterId
     */
    public function delete(int $carrierSelectRuleFilterId): void
    {
        $this->get($carrierSelectRuleFilterId);
        $this->db->perform(
            'DELETE FROM `carrierselect_rule_filter_value` WHERE `carrierselect_rule_filter_id` = :carrierselect_rule_filter_id',
            ['carrierselect_rule_filter_id' => $carrierSelectRuleFilterId]
        );
        $this->db->perform(
            'DELETE FROM `carrierselect_rule_filter` WHERE `id` = :carrierselect_rule_filter_id',
            ['carrierselect_rule_filter_id' => $carrierSelectRuleFilterId]
        );
    }

    /**
     * @param int $carrierSelectRuleFilterId
     *
     * @return array
     */
    private function getValuesFromDbState(int $carrierSelectRuleFilterId): array
    {
        return $this->db->fetchPairs(
            'SELECT `filter_value`, `id` FROM `carrierselect_rule_filter_value` 
            WHERE `carrierselect_rule_filter_id` = :carrierselect_rule_filter_id',
            ['carrierselect_rule_filter_id' => $carrierSelectRuleFilterId]
        );
    }

    /**
     * @param int    $carrierSelectRuleFilterId
     * @param string $filterValue
     *
     * @throws RuntimeException
     * @return int
     *
     */
    private function createFilterValues(int $carrierSelectRuleFilterId, string $filterValue): int
    {
        $this->get($carrierSelectRuleFilterId);

        $this->db->perform(
            'INSERT INTO `carrierselect_rule_filter_value` 
            (`carrierselect_rule_filter_id`, `filter_value`)
            VALUES (:carrierselect_rule_filter_id, :filter_value)',
            [
                'carrierselect_rule_filter_id' => $carrierSelectRuleFilterId,
                'filter_value'                 => $filterValue,
            ]
        );

        return $this->db->lastInsertId();
    }

    /**
     * @param int $carrierSelectRuleFilterValueId
     */
    private function deleteFilterValue(int $carrierSelectRuleFilterValueId): void
    {
        $this->db->perform(
            'DELETE FROM `carrierselect_rule_filter_value` WHERE `id` = :carrierselect_rule_filter_value_id',
            ['carrierselect_rule_filter_value_id' => $carrierSelectRuleFilterValueId]
        );
    }

    /**
     * @param int $carrierSelectRuleId
     *
     * @throws RuntimeException
     */
    private function ensureRuleExists(int $carrierSelectRuleId): void
    {
        if (
            false === $this->db->fetchValue(
                'SELECT `id` FROM `carrierselect_rule` WHERE `id` = :carrier_select_rule_id',
                ['carrier_select_rule_id' => $carrierSelectRuleId]
            )
        ) {
            throw new RuntimeException("CarrierSelectRule with id {$carrierSelectRuleId} not found");
        }
    }
}
