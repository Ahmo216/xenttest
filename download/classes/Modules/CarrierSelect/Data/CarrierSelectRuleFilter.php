<?php


namespace Xentral\Modules\CarrierSelect\Data;

use Xentral\Modules\CarrierSelect\Data\Comparator\FilterComparatorInterface;
use Xentral\Modules\CarrierSelect\Data\Comparator\ValueBetweenComparator;
use Xentral\Modules\CarrierSelect\Data\Comparator\ValueGreaterThanComparator;
use Xentral\Modules\CarrierSelect\Data\Comparator\ValueGreaterThanEqualComparator;
use Xentral\Modules\CarrierSelect\Data\Comparator\ValueInListComparator;
use Xentral\Modules\CarrierSelect\Data\Comparator\ValueIsEmptyComparator;
use Xentral\Modules\CarrierSelect\Data\Comparator\ValueIsEqualComparator;
use Xentral\Modules\CarrierSelect\Data\Comparator\ValueIsNotEmptyComparator;
use Xentral\Modules\CarrierSelect\Data\Comparator\ValueNotInListComparator;
use Xentral\Modules\CarrierSelect\Data\Comparator\ValueSmallerThanComparator;
use Xentral\Modules\CarrierSelect\Data\Comparator\ValueSmallerThanEqualComparator;
use Xentral\Modules\CarrierSelect\Data\FilterField\ArticleFilter;
use Xentral\Modules\CarrierSelect\Data\FilterField\FastlaneFilter;
use Xentral\Modules\CarrierSelect\Data\FilterField\FilterFieldInterface;
use Xentral\Modules\CarrierSelect\Data\FilterField\OrderAmountFilter;
use Xentral\Modules\CarrierSelect\Data\FilterField\PaymentMethodFilter;
use Xentral\Modules\CarrierSelect\Data\FilterField\ProjectFilter;
use Xentral\Modules\CarrierSelect\Data\FilterField\ShippingCountryFilter;
use Xentral\Modules\CarrierSelect\Data\FilterField\ShippingMethodFilter;
use Xentral\Modules\CarrierSelect\Data\FilterField\ShopFilter;
use Xentral\Modules\CarrierSelect\Data\FilterField\VolumeFilter;
use Xentral\Modules\CarrierSelect\Data\FilterField\WeightFilter;
use Xentral\Modules\CarrierSelect\Exception\InvalidArgumentException;
use Xentral\Modules\CarrierSelect\Exception\RuntimeException;

final class CarrierSelectRuleFilter
{
    /** @var FilterFieldInterface $field */
    private $field;

    /** @var FilterComparatorInterface $comparator */
    private $comparator;

    /** @var array $values */
    private $values = [];

    /** @var null|int $ruleFilterId */
    private $ruleFilterId;

    /**
     * CarrierSelectRuleFilter constructor.
     *
     * @param FilterFieldInterface      $field
     * @param FilterComparatorInterface $comparator
     * @param int|null                  $ruleFilterId
     */
    public function __construct(
        FilterFieldInterface $field,
        FilterComparatorInterface $comparator,
        ?int $ruleFilterId = null
    ) {
        $this->field = $field;
        $this->comparator = $comparator;
        $this->ruleFilterId = $ruleFilterId;
    }

    /**
     * @param array $data
     *
     * @throws RuntimeException
     * @return static
     *
     */
    public static function fromDbState(array $data): self
    {
        $ruleFilterId = null;
        if (!empty($data['id'])) {
            $ruleFilterId = (int)$data['id'];
        }
        $filterField = self::getFieldFromDbState($data['filter_field']);
        if ($filterField === null) {
            throw new InvalidArgumentException("field '{$data['filter_field']}' is invalid");
        }
        $comparator = self::getComparatorFromDbState($data['filter_comparator']);

        if ($comparator === null) {
            throw new InvalidArgumentException("comparator '{$data['filter_comparator']}' is invalid");
        }

        $instance = new self($filterField, $comparator, $ruleFilterId);
        if (!empty($data['values'])) {
            foreach ($data['values'] as $value) {
                $instance->addValue($value);
            }
        }

        return $instance;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'filter_comparator' => $this->comparator->getName(),
            'filter_field'      => $this->field->getName(),
            'values'            => $this->getValues(),
            'id'                => $this->ruleFilterId,
        ];
    }

    /**
     * @param int $ruleFilterId
     *
     * @throws RuntimeException
     * @return $this
     *
     */
    public function setRuleFilterId(int $ruleFilterId): self
    {
        if ($this->ruleFilterId !== null) {
            throw new RuntimeException('RuleFilterId is already set');
        }
        $this->ruleFilterId = $ruleFilterId;

        return $this;
    }

    /**
     * @return $this
     */
    public function removeRuleFilterId(): self
    {
        $this->ruleFilterId = null;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getRuleFilterId(): ?int
    {
        return $this->ruleFilterId;
    }


    /**
     * @param $value
     *
     * @throws RuntimeException
     * @return self
     *
     */
    public function addValue($value): self
    {
        if (in_array($value, $this->values, true)) {
            throw new RuntimeException("value '{$value}' is already in list");
        }
        $this->values[] = $value;

        return $this;
    }

    /**
     * @param $value
     *
     * @throws RuntimeException
     * @return $this
     *
     */
    public function removeValue($value): self
    {
        if (!in_array($value, $this->values, true)) {
            throw new RuntimeException("value '{$value}' does not exists in list");
        }

        $this->values = array_diff($this->values, [$value]);

        return $this;
    }

    /**
     * @param Order $order
     *
     * @return bool
     */
    public function isFilterMatching(Order $order): bool
    {
        return $this->comparator->matches($this->values, $this->field->getValuesFromOrder($order));
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @return FilterComparatorInterface
     */
    public function getComparator(): FilterComparatorInterface
    {
        return $this->comparator;
    }

    /**
     * @return FilterFieldInterface
     */
    public function getField(): FilterFieldInterface
    {
        return $this->field;
    }

    /**
     * @param string $field
     *
     * @return FilterFieldInterface|null
     */
    private static function getFieldFromDbState(string $field): ?FilterFieldInterface
    {
        switch ($field) {
            case 'article':
                $instance = new ArticleFilter();
                break;
            case 'fastlane':
                $instance = new FastlaneFilter();
                break;
            case 'order_amount':
                $instance = new OrderAmountFilter();
                break;
            case 'payment_method':
                $instance = new PaymentMethodFilter();
                break;
            case 'project':
                $instance = new ProjectFilter();
                break;
            case 'shipping_country':
                $instance = new ShippingCountryFilter();
                break;
            case 'shop':
                $instance = new ShopFilter();
                break;
            case 'volume':
                $instance = new VolumeFilter();
                break;
            case 'weight':
                $instance = new WeightFilter();
                break;
            case 'shipping_method':
                $instance = new ShippingMethodFilter();
                break;
            default:
                return null;
        }
        if($instance->getName() === $field) {
            return $instance;
        }

        return null;
    }

    /**
     * @param string $comparator
     *
     * @return FilterComparatorInterface|null
     */
    private static function getComparatorFromDbState(string $comparator): ?FilterComparatorInterface
    {
        switch ($comparator) {
            case 'between':
                $instance = new ValueBetweenComparator();
                break;
            case 'in':
                $instance = new ValueInListComparator();
                break;
            case '>':
                $instance = new ValueGreaterThanComparator();
                break;
            case '>=':
                $instance = new ValueGreaterThanEqualComparator();
                break;
            case '<':
                $instance = new ValueSmallerThanComparator();
                break;
            case '<=':
                $instance = new ValueSmallerThanEqualComparator();
                break;
            case 'not_in':
                $instance = new ValueNotInListComparator();
                break;
            case 'empty':
                $instance = new ValueIsEmptyComparator();
                break;
            case 'not_empty':
                $instance = new ValueIsNotEmptyComparator();
                break;
            case '=':
                $instance = new ValueIsEqualComparator();
                break;
            default:
                return null;
        }
        if($instance->getName() === $comparator) {
            return $instance;
        }

        return null;
    }
}
