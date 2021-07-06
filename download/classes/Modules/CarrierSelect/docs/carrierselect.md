#usage

get Service:

```php
    use Xentral\Modules\CarrierSelect\Service\CarrierSelectService;
    $carrierSelectService = $this->app->Container->get('CarrierSelectService');

```

Example creating rule:
```php
    use Xentral\Modules\CarrierSelect\Service\CarrierSelectService;
    use Xentral\Modules\CarrierSelect\Data\CarrierSelectRule;
    $carrierSelectService = $this->app->Container->get('CarrierSelectService');
    $ruleArr = ['name' => 'test', 'priority' => 1, 'active' => true, 'carrier' => 'DHL',];  
    $rule = CarrierSelectRule::fromDbState($ruleArr);
    $carrierSelectService->createRuleByCarrierSelectRule($rule);
```

Example check matching rules for order:
```php
    use Xentral\Modules\CarrierSelect\Service\CarrierSelectService;
    use Xentral\Modules\CarrierSelect\Data\CarrierSelectRule;
    use Xentral\Modules\CarrierSelect\Data\CarrierSelectRuleCollection;
    
    $collection = $this->service->getRuleCollectionFromDb();
    $order = $this->service->getOrderFromId($orderId);
    $allowedShippingMethods = $this->app->DB->SelectFirstCols(
    "SELECT DISTINCT `type` FROM `versandarten` WHERE `aktiv` = 1 AND `projekt` IN (0, {$orderRow['projekt']})"
    );
    $rule = $collection->getMatchingRuleByOrder($order, $allowedShippingMethods);

```
