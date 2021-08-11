<?php

declare(strict_types=1);

namespace Xentral\Modules\CarrierSelect\Data;


use Iterator;

final class CarrierSelectRuleCollection implements Iterator
{

    /** @var CarrierSelectRule[] $rules */
    private $rules = [];

    /** @var int $position */
    private $position = 0;

    /**
     * CarrierSelectRuleCollection constructor.
     *
     * @param CarrierSelectRule[] $ruleArray
     */
    public function __construct($ruleArray = [])
    {
        foreach ($ruleArray as $rule) {
            $this->add($rule);
        }
    }

    /**
     * @param CarrierSelectRule $rule
     */
    public function add(CarrierSelectRule $rule): void
    {
        $this->rules[] = $rule;
        $this->sortRules();
    }

    /**
     * @param Order      $order
     * @param array|null $allowedShippingMethods
     *
     * @return string|null
     */
    public function getMatchingCarrierByOrder(Order $order, ?array $allowedShippingMethods = null): ?string
    {
        $rule = $this->tryGetMatchingRuleByOrder($order, $allowedShippingMethods);
        if ($rule === null) {
            return null;
        }

        return $rule->getCarrier();
    }

    /**
     * @param Order      $order
     * @param array|null $allowedShippingMethods
     *
     * @return CarrierSelectRule|null
     */
    public function tryGetMatchingRuleByOrder(Order $order, ?array $allowedShippingMethods = null): ?CarrierSelectRule
    {
        foreach ($this->rules as $rule) {
            if ($rule->isRuleMatching($order)) {
                if ($allowedShippingMethods === null || in_array($rule->getCarrier(), $allowedShippingMethods, true)) {
                    return $rule;
                }
            }
        }

        return null;
    }

    private function sortRules(): void
    {
        if (count($this->rules) <= 1) {
            return;
        }

        uasort(
            $this->rules,
            static function ($a, $b) {
                if ($a->getPriority() < $b->getPriority()) {
                    return -1;
                }
                if ($a->getPriority() > $b->getPriority()) {
                    return 1;
                }

                $idFromA = $a->getId();
                $idFromB = $b->getId();
                if ($idFromA === null && $idFromB === null) {
                    return 0;
                }
                if ($idFromB === null) {
                    return 1;
                }
                if ($idFromA === null) {
                    return -1;
                }

                if ($idFromA === $idFromB) {
                    return 0;
                }

                return $idFromA < $idFromB ? -1 : 1;
            }
        );
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        $rules = [];
        foreach ($this->rules as $rule) {
            $rules[] = CarrierSelectRule::fromDbState($rule->toArray());
        }

        return $rules;
    }

    /**
     * @return CarrierSelectRule
     */
    public function current(): CarrierSelectRule
    {
        return CarrierSelectRule::fromDbState($this->rules[$this->position]->toArray());
    }

    public function next(): void
    {
        $this->position++;
    }

    /**
     * @return int
     */
    public function key(): int
    {
        return $this->position;
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return isset($this->rules[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }
}
