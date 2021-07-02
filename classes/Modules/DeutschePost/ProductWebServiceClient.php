<?php

declare(strict_types=1);

namespace Xentral\Modules\DeutschePost;

use DateTime;
use SoapFault;
use Xentral\Modules\DeutschePost\Exception\ProductCacheInvalidException;
use Xentral\Modules\DeutschePost\Exception\ProductCacheOutdatedException;
use Xentral\Modules\DeutschePost\Exception\ProductNotFoundException;
use Xentral\Modules\DeutschePost\Exception\ProductWebServiceException;
use Xentral\Modules\DeutschePost\Exception\ProductWebServiceSoapException;
use Xentral\Modules\SystemConfig\Exception\ConfigurationKeyNotFoundException;
use Xentral\Modules\SystemConfig\SystemConfigModule;

class ProductWebServiceClient
{
    private const CONFIG_PRODUCTS_NAMESPACE = 'deutsche_post_internetmarke';

    private const CONFIG_PRODUCTS_KEY = 'products';

    private const CONFIG_PRODUCTS_DATE_KEY = 'date';

    private const MAX_CACHE_LIFETIME = 24 * 60 * 60; // 1 day lifetime

    /** @var SystemConfigModule */
    private $systemConfig;


    /** @var ProductWebServiceSoapClient */
    private $soapClient;

    /**
     * ProductWebServiceClient constructor.
     *
     * @param SystemConfigModule          $systemConfig
     * @param ProductWebServiceSoapClient $soapClient
     */
    public function __construct(SystemConfigModule $systemConfig, ProductWebServiceSoapClient $soapClient)
    {
        $this->systemConfig = $systemConfig;
        $this->soapClient = $soapClient;
    }

    /**
     * @param int $productId as seen in PPL file
     *
     * @return float
     */
    public function getProductPrice(int $productId): float
    {
        $products = $this->getProducts();

        foreach ($products as $product) {
            if ($product['pplId'] === $productId) {
                return $product['price'];
            }
        }

        throw new ProductNotFoundException("Product {$productId} not found");
    }

    /**
     * @return array
     */
    public function getProducts(): array
    {
        try {
            return $this->loadProductsFromCache();
        } catch (ProductCacheInvalidException $e) {
            return $this->refreshProducts();
        }
    }

    /**
     * @return bool
     */
    private function shouldRefreshProducts(): bool
    {
        $productsDate = $this->systemConfig->tryGetValue(
            self::CONFIG_PRODUCTS_NAMESPACE,
            self::CONFIG_PRODUCTS_DATE_KEY
        );

        if (empty($productsDate)) {
            return true;
        }

        $currentTimestamp = $this->getCurrentTimestamp();
        $passedTime = $currentTimestamp - $productsDate;

        return $passedTime > self::MAX_CACHE_LIFETIME;
    }

    /**
     * @return int
     */
    private function getCurrentTimestamp(): int
    {
        return (new DateTime())->getTimestamp();
    }

    /**
     * @throws ProductWebServiceException
     * @throws ProductWebServiceSoapException
     *
     * @return array
     */
    private function refreshProducts(): array
    {
        try {
            $response = $this->soapClient->getProductList();
        } catch (SoapFault $e) {
            throw ProductWebServiceSoapException::fromSoapFault($e);
        }

        if (!empty($response->Exception)) {
            $exceptionText = $response->Exception->exceptionDetail->errorDetail;
            throw new ProductWebServiceException($exceptionText);
        }

        $products = array_map(
            function ($product) {
                return [
                    'pplId' => (int)$product->externIdentifier->id,
                    'name'  => $product->name,
                    'price' => (float)money_format(
                        '%!n',
                        (float)$product->priceDefinition->commercialGrossPrice->value
                    ),
                ];
            },
            $response->Response->shortSalesProductList->ShortSalesProduct
        );

        $json = json_encode($products);

        $this->systemConfig->setValue(self::CONFIG_PRODUCTS_NAMESPACE, self::CONFIG_PRODUCTS_KEY, $json);
        $this->systemConfig->setValue(
            self::CONFIG_PRODUCTS_NAMESPACE,
            self::CONFIG_PRODUCTS_DATE_KEY,
            (string)$this->getCurrentTimestamp()
        );

        return $products;
    }

    /**
     * @throws ConfigurationKeyNotFoundException
     * @throws ProductCacheInvalidException
     *
     * @return array
     */
    private function loadProductsFromCache(): array
    {
        if ($this->shouldRefreshProducts()) {
            throw new ProductCacheOutdatedException();
        }

        $productCacheString = $this->systemConfig->tryGetValue(self::CONFIG_PRODUCTS_NAMESPACE, self::CONFIG_PRODUCTS_KEY);

        // check if result is null, e.g. when the key is not present, but also if it's "" for some reason
        if (empty($productCacheString)) {
            throw new ProductCacheInvalidException('cache configuration string empty');
        }

        $products = json_decode(
            $productCacheString,
            true
        );

        if (empty($products)) {
            throw new ProductCacheInvalidException('cached product list empty');
        }

        return $products;
    }

    /**
     * @return void
     */
    public function resetProductCache(): void
    {
        $this->systemConfig->deleteKey(self::CONFIG_PRODUCTS_NAMESPACE, self::CONFIG_PRODUCTS_KEY);
        $this->systemConfig->deleteKey(self::CONFIG_PRODUCTS_NAMESPACE, self::CONFIG_PRODUCTS_DATE_KEY);
    }
}
