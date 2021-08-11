<?php

declare(strict_types=1);

namespace Xentral\Modules\CarrierSelect\Data;


use Xentral\Modules\CarrierSelect\Exception\InvalidArgumentException;
use Xentral\Modules\CarrierSelect\Exception\RuntimeException;

final class CarrierSelectRule
{
    /** @var string $name */
    private $name;

    /** @var string $carrier */
    private $carrier;

    /** @var CarrierSelectRuleFilter[] $filters */
    private $filters = [];

    /** @var int $priority */
    private $priority;

    /** @var int|null $id */
    private $id;

    /** @var bool $active */
    private $active;

    /**
     * CarrierSelectRule constructor.
     *
     * @param string   $name
     * @param string   $carrier
     * @param int      $priority
     * @param int|null $id
     * @param bool     $active
     */
    public function __construct(string $name, string $carrier, int $priority, ?int $id = null, bool $active = true)
    {
        $this->validateFieldValue($carrier, 'carrier');
        $this->validateFieldValue($name, 'name');
        $this->name = $name;
        $this->priority = $priority;
        $this->id = $id;
        $this->carrier = $carrier;
        $this->active = $active;
    }

    /**
     * @param array $ruleArray
     *
     * @return static
     */
    public static function fromDbState(array $ruleArray): self
    {
        $ruleId = null;
        if (!empty($ruleArray['id'])) {
            $ruleId = (int)$ruleArray['id'];
        }
        $instance = new self(
            $ruleArray['name'],
            $ruleArray['carrier'],
            (int)$ruleArray['priority'],
            $ruleId,
            !empty($ruleArray['active'])
        );
        if (!empty($ruleArray['filters'])) {
            foreach ($ruleArray['filters'] as $filter) {
                if($filter instanceof CarrierSelectRuleFilter) {
                    $instance->addFilter($filter);
                    continue;
                }
                $instance->addFilter(
                    CarrierSelectRuleFilter::fromDbState($filter)
                );
            }
        }

        return $instance;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $filters = [];
        foreach ($this->filters as $filter) {
            $filters[] = $filter->toArray();
        }

        return [
            'id'       => $this->getId(),
            'name'     => $this->getName(),
            'carrier'  => $this->getCarrier(),
            'priority' => $this->getPriority(),
            'active'   => (int)$this->isActive(),
            'filters'  => $filters,
        ];
    }

    /**
     * @param CarrierSelectRuleFilter $filter
     *
     * @throws RuntimeException
     * @return self
     *
     */
    public function addFilter(CarrierSelectRuleFilter $filter): self
    {
        $this->ensureCanAddFilter($filter);
        $this->filters[] = $filter;

        return $this;
    }

    /**
     * @param Order $order
     *
     * @return bool
     */
    public function isRuleMatching(Order $order): bool
    {
        if (!$this->active) {
            return false;
        }
        if (empty($this->filters)) {
            return true;
        }
        foreach ($this->filters as $filter) {
            if (!$filter->isFilterMatching($order)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $name
     *
     * @throws InvalidArgumentException
     */
    public function setName(string $name): void
    {
        $this->validateFieldValue($name, 'name');
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $carrier
     *
     * @throws InvalidArgumentException
     */
    public function setCarrier(string $carrier): void
    {
        $this->validateFieldValue($carrier, 'carrier');
        $this->carrier = $carrier;
    }

    /**
     * @return string
     */
    public function getCarrier(): string
    {
        return $this->carrier;
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        $filters = [];
        foreach ($this->filters as $filter) {
            $filters[] = CarrierSelectRuleFilter::fromDbState($filter->toArray());
        }

        return $filters;
    }

    /**
     * @param array $filters
     */
    public function setFilters(array $filters): void
    {
        $this->filters = [];
        foreach ($filters as $filter) {
            $this->addFilter($filter);
        }
    }

    /**
     * @param int $ruleId
     *
     * @throws RuntimeException
     * @return $this
     *
     */
    public function setId(int $ruleId): self
    {
        if ($this->id !== null) {
            throw new RuntimeException('ruleId is alredy set');
        }
        $this->id = $ruleId;

        return $this;
    }

    public function removeId(): self
    {
        $this->id = null;

        return $this;
    }

    /**
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $priority
     */
    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param CarrierSelectRuleFilter $filterToAdd
     */
    private function ensureCanAddFilter(CarrierSelectRuleFilter $filterToAdd): void
    {
        /** @var CarrierSelectRuleFilter $filter */
        $filtername = ($filterToAdd->getField())->getName();
        foreach ($this->filters as $filter) {
            if ($filtername === $filter->getField()->getName()) {
                throw new RuntimeException("filter '{$filtername}' is already in list");
            }
        }
    }

    /**
     * @param string $value
     * @param string $field
     *
     * @throws InvalidArgumentException
     */
    private function validateFieldValue(string $value, string $field): void
    {
        if (!empty($value)) {
            return;
        }
        throw new InvalidArgumentException("'$field' can not be empty");
    }
}
