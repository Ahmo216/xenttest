<?php

declare(strict_types=1);

namespace Xentral\Modules\CarrierSelect\Service;

use Xentral\Components\Database\Database;
use Xentral\Modules\CarrierSelect\Data\CarrierSelectRule;
use Xentral\Modules\CarrierSelect\Data\CarrierSelectRuleFilter;
use Xentral\Modules\CarrierSelect\Exception\InvalidArgumentException;
use Xentral\Modules\CarrierSelect\Exception\RuntimeException;

final class CarrierSelectRuleService
{
    /** @var Database $db */
    private $db;

    /** @var CarrierSelectRuleFilterService $ruleFilterService */
    private $ruleFilterService;

    public function __construct(Database $db, CarrierSelectRuleFilterService $ruleFilterService)
    {
        $this->db = $db;
        $this->ruleFilterService = $ruleFilterService;
    }

    /**
     * @param $carrierSelectRule CarrierSelectRule
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     *
     * @return int
     */
    public function create(CarrierSelectRule $carrierSelectRule): int
    {
        if ($carrierSelectRule->getId() !== null) {
            throw new RuntimeException('entry has already an id');
        }
        $name = $carrierSelectRule->getName();
        if ($this->tryGetByName($name) !== null) {
            throw new RuntimeException("entry with name '{$name}' allready exists");
        }

        $this->db->perform(
            'INSERT INTO `carrierselect_rule` 
            (`name`, `active`, `priority`, `carrier`) 
            VALUES (:name, :active, :priority, :carrier)',
            [
                'name'     => $name,
                'active'   => (int)$carrierSelectRule->isActive(),
                'priority' => $carrierSelectRule->getPriority(),
                'carrier'  => $carrierSelectRule->getCarrier(),
            ]
        );

        $ruleId = $this->db->lastInsertId();

        foreach ($carrierSelectRule->getFilters() as $filter) {
            $this->ruleFilterService->createRuleFilter($ruleId, $filter);
        }

        return $ruleId;
    }

    /**
     * @param CarrierSelectRule $carrierSelectRule
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function update(CarrierSelectRule $carrierSelectRule): void
    {
        $carrierSelectRuleId = $carrierSelectRule->getId();
        $name = $carrierSelectRule->getName();
        if ($carrierSelectRuleId === null) {
            throw new RuntimeException('entry has no id');
        }
        if ($this->existsOtherCarrierWithName($name, $carrierSelectRuleId)) {
            throw new RuntimeException("entry with name '{$name}' allready exists");
        }

        $this->db->perform(
            'UPDATE `carrierselect_rule` 
            SET `name` = :name,
                `active` = :active,
                `priority` = :priority,
                `carrier` = :carrier
            WHERE `id` = :carrier_rule_id',
            [
                'name'            => $name,
                'active'          => (int)$carrierSelectRule->isActive(),
                'priority'        => $carrierSelectRule->getPriority(),
                'carrier'         => $carrierSelectRule->getCarrier(),
                'carrier_rule_id' => $carrierSelectRuleId,
            ]
        );

        $dbFilter = $this->ruleFilterService->getRuleFiltersByRuleId($carrierSelectRuleId);
        $filterFieldNameToId = [];
        foreach($dbFilter as $filter) {
            $filterFieldNameToId[$filter->getField()->getName()] = $filter->getRuleFilterId();
        }
        $filterIdsToDelete = array_values($filterFieldNameToId);
        $filterIdsToStay = [];

        /** @var CarrierSelectRuleFilter $filter */
        foreach ($carrierSelectRule->getFilters() as $filter) {
            $filterFieldName = $filter->getField()->getName();
            if(isset($filterFieldNameToId[$filterFieldName])) {
                $filter->removeRuleFilterId();
                $filter->setRuleFilterId($filterFieldNameToId[$filterFieldName]);
            }
            else {
                $filter->removeRuleFilterId();
            }
            $ruleFilterId = $filter->getRuleFilterId();
            if ($ruleFilterId !== null) {
                $filterIdsToStay[] = $ruleFilterId;
                $this->ruleFilterService->updateRuleFilter($filter);
            } else {
                $this->ruleFilterService->createRuleFilter($carrierSelectRuleId, $filter);
            }
        }

        $filterIdsToDelete = array_diff($filterIdsToDelete, $filterIdsToStay);
        foreach ($filterIdsToDelete as $filterIdToDelete) {
            $this->ruleFilterService->delete($filterIdToDelete);
        }
    }

    /**
     * @param string $name
     *
     * @return CarrierSelectRule|null
     */
    public function tryGetByName(string $name): ?CarrierSelectRule
    {
        $id = $this->db->fetchValue('SELECT `id` FROM `carrierselect_rule` WHERE `name` = :name', ['name' => $name]);
        if ($id === false) {
            return null;
        }

        return $this->get($id);
    }

    /**
     * @param int $carrierSelectRuleId
     *
     * @throws RuntimeException
     * @return CarrierSelectRule
     *
     */
    public function get(int $carrierSelectRuleId): CarrierSelectRule
    {
        $array = $this->db->fetchRow(
            'SELECT `id`, `name`, `active`, `priority`, `carrier`
            FROM `carrierselect_rule` WHERE `id` = :carrier_rule_id',
            ['carrier_rule_id' => $carrierSelectRuleId]
        );
        if (empty($array)) {
            throw new RuntimeException('CarrierSelectRule not found');
        }
        $array['filters'] = $this->ruleFilterService->getRuleFiltersByRuleId($carrierSelectRuleId);

        return CarrierSelectRule::fromDbState($array);
    }

    /**
     * @param int $carrierSelectRuleId
     *
     * @throws RuntimeException
     */
    public function delete(int $carrierSelectRuleId): void
    {
        $rule = $this->get($carrierSelectRuleId);
        /** @var CarrierSelectRuleFilter $filter */
        foreach ($rule->getFilters() as $filter) {
            $this->ruleFilterService->delete($filter->getRuleFilterId());
        }
        $this->db->perform(
            'DELETE FROM `carrierselect_rule` 
            WHERE `id` = :carrier_rule_id',
            ['carrier_rule_id' => $carrierSelectRuleId]
        );
    }

    /**
     * @param string $name
     * @param int    $carrierSelectRuleId
     *
     * @return bool
     */
    public function existsOtherCarrierWithName(string $name, int $carrierSelectRuleId): bool
    {
        $otherCarrierWithName = $this->db->fetchRow(
            'SELECT `id`, `name`, `active`, `priority`, `carrier`
            FROM `carrierselect_rule` WHERE `name` = :name AND `id` <> :carrier_rule_id',
            ['name' => $name, 'carrier_rule_id' => $carrierSelectRuleId]
        );

        return !empty($otherCarrierWithName);
    }
}
