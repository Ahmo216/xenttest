<?php

declare(strict_types=1);

namespace Xentral\Modules\CarrierSelect\Service;


use Xentral\Components\Database\Database;
use Xentral\Modules\CarrierSelect\Data\CarrierSelectRule;
use Xentral\Modules\CarrierSelect\Data\CarrierSelectRuleCollection;
use Xentral\Modules\CarrierSelect\Data\CarrierSelectRuleFilter;
use Xentral\Modules\CarrierSelect\Data\Order;
use Xentral\Modules\CarrierSelect\Data\OrderPosition;
use Xentral\Modules\CarrierSelect\Exception\InvalidArgumentException;
use Xentral\Modules\CarrierSelect\Exception\RuntimeException;

final class CarrierSelectService
{
    /** @var Database $db */
    private $db;

    /** @var CarrierSelectRuleService $ruleService */
    private $ruleService;

    /** @var CarrierSelectRuleFilterService $ruleService */
    private $ruleFilterService;

    public function __construct(
        Database $db,
        CarrierSelectRuleService $ruleService,
        CarrierSelectRuleFilterService $ruleFilterService
    ) {
        $this->db = $db;
        $this->ruleService = $ruleService;
        $this->ruleFilterService = $ruleFilterService;
    }

    /**
     * @param int $carrierSelectRuleId
     *
     * @throws RuntimeException
     */
    public function deleteRule(int $carrierSelectRuleId): void
    {
        $this->ruleService->delete($carrierSelectRuleId);
    }

    /**
     * @param CarrierSelectRule $carrierSelectRule
     *
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    public function updateRule(
        CarrierSelectRule $carrierSelectRule
    ): void {
        $this->ruleService->update($carrierSelectRule);
    }

    /**
     * @param int $carrierSelectRuleId
     *
     * @return CarrierSelectRule
     */
    public function getRule(int $carrierSelectRuleId): CarrierSelectRule
    {
        return $this->ruleService->get($carrierSelectRuleId);
    }

    /**
     * @param string $name
     *
     * @return CarrierSelectRule|null
     */
    public function tryGetRuleByName(string $name): ?CarrierSelectRule
    {
        return $this->ruleService->tryGetByName($name);
    }

    /**
     * @param int                     $carrierSelectRuleId
     * @param CarrierSelectRuleFilter $ruleFilter
     *
     * @throws RuntimeException
     * @throws InvalidArgumentException
     * @return int
     *
     */
    public function createRuleFilter(int $carrierSelectRuleId, CarrierSelectRuleFilter $ruleFilter): int
    {
        return $this->ruleFilterService->createRuleFilter($carrierSelectRuleId, $ruleFilter);
    }

    /**
     * @param CarrierSelectRuleFilter $ruleFilter
     *
     * @return void
     */
    public function updateRuleFilter(CarrierSelectRuleFilter $ruleFilter): void
    {
        $this->ruleFilterService->updateRuleFilter($ruleFilter);
    }

    /**
     * @param int $carrierSelectRuleId
     *
     * @return CarrierSelectRuleFilter[]
     */
    public function getRuleFiltersByRuleId(int $carrierSelectRuleId): array
    {
        return $this->ruleFilterService->getRuleFiltersByRuleId($carrierSelectRuleId);
    }

    /**
     * @param int $carrierSelectRuleFilterId
     *
     * @return CarrierSelectRuleFilter
     */
    public function getRuleFilterById(int $carrierSelectRuleFilterId): CarrierSelectRuleFilter
    {
        return $this->ruleFilterService->get($carrierSelectRuleFilterId);
    }

    /**
     * @param int $orderId
     *
     * @return Order
     *
     * @throws RuntimeException
     */
    public function getOrderFromId(int $orderId): Order
    {
        $orderData = $this->db->fetchRow(
            'SELECT * FROM `auftrag` WHERE `id` = :order_id',
            ['order_id' => $orderId]
        );
        if (empty($orderData)) {
            throw new RuntimeException('Order not found');
        }
        $orderPositions = [];
        $orderPositionsDb = $this->db->fetchAll(
            'SELECT op.*, art.lagerartikel, art.porto, art.ean, art.gewicht, art.hoehe, art.laenge, art.breite
            FROM `auftrag_position` AS `op`
            INNER JOIN `artikel` AS `art` ON op.artikel = art.id
            WHERE op.auftrag = :order_id
            ORDER BY op.sort, op.id',
            ['order_id' => $orderId]
        );
        foreach($orderPositionsDb as $orderPosition) {
            $orderPositions[] = new OrderPosition($orderPosition);
        }

        return new Order($orderData, $orderPositions);
    }

    /**
     * @param int $ruleId
     *
     * @return CarrierSelectRule
     */
    public function getCarrierSelectRuleFromId(int $ruleId): CarrierSelectRule
    {
        return $this->ruleService->get($ruleId);
    }

    /**
     * @return CarrierSelectRuleCollection
     */
    public function tryGetRuleCollectionFromDb(): CarrierSelectRuleCollection
    {
        $rulesDb = $this->getRuleArrayFromActiveRules();
        $rules = [];
        foreach($rulesDb as $rule) {
            try {
                $rules[] = CarrierSelectRule::fromDbState($rule);
            }
            catch (RuntimeException $e) {

            }
        }

        return new CarrierSelectRuleCollection($rules);
    }

    /**
     * @param CarrierSelectRule $carrierSelectRule
     *
     * @return int
     */
    public function createRule(CarrierSelectRule $carrierSelectRule): int
    {
        $carrierSelectRuleId = $carrierSelectRule->getId();
        if ($carrierSelectRuleId !== null) {
            throw new RuntimeException('id must be null');
        }
        $ruleName = $carrierSelectRule->getName();
        $rule = $this->tryGetRuleByName($ruleName);
        if ($rule !== null) {
            throw new RuntimeException('rule with name already exists');
        }

        return $this->ruleService->create($carrierSelectRule);
    }

    /**
     * @param int $orderId
     *
     * @return bool
     */
    public function hasOrderChanged(int $orderId): bool
    {
        return !empty(
        $this->db->fetchValue(
            'SELECT `id` FROM `carrierselect_order_changed` WHERE `order_id` = :order_id',
            ['order_id' => $orderId]
        )
        );
    }

    /**
     * @param int      $orderId
     * @param string   $carrierFrom
     * @param string   $carrierTo
     * @param int|null $carrierselectRuleId
     */
    public function setOrderAsChanged(
        int $orderId,
        string $carrierFrom,
        string $carrierTo,
        ?int $carrierselectRuleId = 0
    ): void
    {
        if ($this->hasOrderChanged($orderId)) {
            throw new RuntimeException('Order has already been changed');
        }
        $this->db->perform(
            'INSERT INTO `carrierselect_order_changed` 
            (`order_id`, `carrierselect_rule_id`, `carrier_from`, `carrier_to`, `created_at`)
            VALUES (:order_id, :carrierselect_rule_id, :carrier_from, :carrier_to, NOW())',
            [
                'order_id'              => $orderId,
                'carrierselect_rule_id' => $carrierselectRuleId,
                'carrier_from'          => $carrierFrom,
                'carrier_to'            => $carrierTo,
            ]
        );
    }

    /**
     * @param array $rules
     *
     * @return array
     */
    private function getRulesWithFilterfromDbState(array $rules): array
    {
        if (empty($rules)) {
            return [];
        }
        $ruleIds = array_keys($rules);
        $ruleFilters = $this->db->fetchGroup(
            'SELECT `carrierselect_rule_id`, `filter_comparator`, `filter_field`, `id`
            FROM `carrierselect_rule_filter`
            WHERE `carrierselect_rule_id` IN (:rule_ids)',
            ['rule_ids' => $ruleIds]
        );
        $ruleFilterIds = [];
        foreach ($ruleFilters as $ruleFilterRows) {
            foreach ($ruleFilterRows as $filterRow) {
                $ruleFilterIds[] = (int)$filterRow['id'];
            }
        }

        $ruleFilterValues = [];
        if (!empty($ruleFilterIds)) {
            $ruleFilterValues = $this->db->fetchGroup(
                'SELECT `carrierselect_rule_filter_id`, `filter_value`, `id`
                FROM `carrierselect_rule_filter_value`
                WHERE `carrierselect_rule_filter_id` IN (:rule_filter_ids)',
                ['rule_filter_ids' => $ruleFilterIds]
            );
        }
        foreach ($rules as $ruleKey => $rule) {
            if (empty($ruleFilters[$rule['id']])) {
                continue;
            }
            $rules[$ruleKey]['filters'] = $ruleFilters[$rule['id']];
            foreach ($rules[$ruleKey]['filters'] as $ruleFilterKey => $ruleFilter) {
                if (empty($ruleFilterValues[$ruleFilter['id']])) {
                    continue;
                }
                $rules[$ruleKey]['filters'][$ruleFilterKey]['values'] =
                    array_unique(
                        array_map(
                            static function ($val) {
                                return $val['filter_value'];
                            },
                            $ruleFilterValues[$ruleFilter['id']]
                        )
                    );
            }
        }

        return $rules;
    }

    /**
     * @return array
     */
    private function getRuleArrayFromActiveRules(): array
    {
        $rules = $this->db->fetchGroup(
            'SELECT cr.id AS `group_id`, cr.carrier, cr.priority, cr.name, cr.active, cr.id 
            FROM `carrierselect_rule` AS `cr`
            INNER JOIN 
            (
              SELECT DISTINCT `type` FROM `versandarten` WHERE `aktiv` = 1
            ) AS `sm` ON cr.carrier = sm.type
            WHERE `active` = 1
            ORDER BY `priority`, `id`'
        );

        return $this->getRulesWithFilterfromDbState(array_map('reset', $rules));
    }
}
