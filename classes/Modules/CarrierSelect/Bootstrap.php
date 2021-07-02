<?php

namespace Xentral\Modules\CarrierSelect;

use Xentral\Components\SchemaCreator\Collection\SchemaCollection;
use Xentral\Components\SchemaCreator\Schema\TableSchema;
use Xentral\Components\SchemaCreator\Type;
use Xentral\Components\SchemaCreator\Index;
use Xentral\Components\SchemaCreator\Option\TableOption;
use Xentral\Core\DependencyInjection\ContainerInterface;
use Xentral\Modules\CarrierSelect\Service\CarrierSelectRuleFilterService;
use Xentral\Modules\CarrierSelect\Service\CarrierSelectRuleService;
use Xentral\Modules\CarrierSelect\Service\CarrierSelectService;

final class Bootstrap
{
    /**
     * @return array
     */
    public static function registerServices(): array
    {
        return [
            'CarrierSelectRuleService' => 'onInitCarrierRuleSelectService',
            'CarrierSelectService' => 'onInitCarrierSelectService',
            'CarrierSelectRuleFilterService' => 'onInitCarrierSelectRuleFilterService',
        ];
    }

    /**
     * @param ContainerInterface $container
     *
     * @return CarrierSelectRuleService
     */
    public static function onInitCarrierRuleSelectService(ContainerInterface $container): CarrierSelectRuleService
    {
        return new CarrierSelectRuleService(
            $container->get('Database'),
            $container->get('CarrierSelectRuleFilterService')
        );
    }

    /**
     * @param ContainerInterface $container
     *
     * @return CarrierSelectService
     */
    public static function onInitCarrierSelectService(ContainerInterface $container): CarrierSelectService
    {
        return new CarrierSelectService(
            $container->get('Database'),
            $container->get('CarrierSelectRuleService'),
            $container->get('CarrierSelectRuleFilterService')
        );
    }

    public static function onInitCarrierSelectRuleFilterService(ContainerInterface $container
    ): CarrierSelectRuleFilterService {
        return new CarrierSelectRuleFilterService($container->get('Database'));
    }

    /**
     * @param SchemaCollection $collection
     *
     * @return void
     */
    public static function registerTableSchemas(SchemaCollection $collection): void
    {
        $carrierSelectRuleTable = new TableSchema('carrierselect_rule');
        $carrierSelectRuleTable->addColumn(Type\Integer::asAutoIncrement('id'));
        $carrierSelectRuleTable->addColumn(new Type\Varchar('name', 64));
        $carrierSelectRuleTable->addColumn(new Type\Tinyint('active'));
        $carrierSelectRuleTable->addColumn(new Type\Integer('priority'));
        $carrierSelectRuleTable->addColumn(new Type\Varchar('carrier', 64));
        $carrierSelectRuleTable->addOption(TableOption::fromEngine('InnoDB'));
        $carrierSelectRuleTable->addOption(TableOption::fromCharset('utf8'));
        $carrierSelectRuleTable->addIndex(new Index\Primary(['id']));
        $carrierSelectRuleTable->addIndex(new Index\Unique(['name']));

        $carrierSelectRuleFilter = new TableSchema('carrierselect_rule_filter');
        $carrierSelectRuleFilter->addColumn(Type\Integer::asAutoIncrement('id'));
        $carrierSelectRuleFilter->addColumn(new Type\Integer('carrierselect_rule_id', 10, true));
        $carrierSelectRuleFilter->addColumn(new Type\Varchar('filter_comparator', 16));
        $carrierSelectRuleFilter->addColumn(new Type\Varchar('filter_field', 64));

        $carrierSelectRuleFilter->addOption(TableOption::fromEngine('InnoDB'));
        $carrierSelectRuleFilter->addOption(TableOption::fromCharset('utf8'));
        $carrierSelectRuleFilter->addIndex(new Index\Primary(['id']));
        $carrierSelectRuleFilter->addIndex(new Index\Unique(['carrierselect_rule_id', 'filter_field']));
        $carrierSelectRuleFilter->addIndex(
            new Index\Constraint(
                'carrierselect_rule_to_filter', ['carrierselect_rule_id'], 'carrierselect_rule', ['id']
            )
        );

        $carrierSelectRuleFilterValue = new TableSchema('carrierselect_rule_filter_value');
        $carrierSelectRuleFilterValue->addColumn(Type\Integer::asAutoIncrement('id'));
        $carrierSelectRuleFilterValue->addColumn(new Type\Integer('carrierselect_rule_filter_id', 10, true));
        $carrierSelectRuleFilterValue->addColumn(new Type\Varchar('filter_value', 64));
        $carrierSelectRuleFilterValue->addOption(TableOption::fromEngine('InnoDB'));
        $carrierSelectRuleFilterValue->addOption(TableOption::fromCharset('utf8'));
        $carrierSelectRuleFilterValue->addIndex(new Index\Primary(['id']));
        $carrierSelectRuleFilterValue->addIndex(new Index\Unique(['carrierselect_rule_filter_id', 'filter_value']));
        $carrierSelectRuleFilterValue->addIndex(
            new Index\Constraint(
                'carrierselect_filter_to_value', ['carrierselect_rule_filter_id'], 'carrierselect_rule_filter', ['id']
            )
        );

        $carrierSelectOrderChanged = new TableSchema('carrierselect_order_changed');
        $carrierSelectOrderChanged->addColumn(Type\Integer::asAutoIncrement('id'));
        $carrierSelectOrderChanged->addColumn(new Type\Integer('order_id', 10, true));
        $carrierSelectOrderChanged->addColumn(new Type\Integer('carrierselect_rule_id', 10, true));
        $carrierSelectOrderChanged->addColumn(new Type\Varchar('carrier_from', 64));
        $carrierSelectOrderChanged->addColumn(new Type\Varchar('carrier_to', 64));
        $carrierSelectOrderChanged->addColumn(new Type\Timestamp('created_at', 'current_timestamp'));
        $carrierSelectOrderChanged->addIndex(new Index\Primary(['id']));
        $carrierSelectOrderChanged->addIndex(new Index\Unique(['order_id']));
        $carrierSelectOrderChanged->addOption(TableOption::fromEngine('InnoDB'));
        $carrierSelectOrderChanged->addOption(TableOption::fromCharset('utf8'));

        $collection->add($carrierSelectRuleTable);
        $collection->add($carrierSelectRuleFilter);
        $collection->add($carrierSelectRuleFilterValue);
        $collection->add($carrierSelectOrderChanged);
    }
}
