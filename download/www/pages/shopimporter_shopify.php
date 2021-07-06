<?php
/*
**** COPYRIGHT & LICENSE NOTICE *** DO NOT REMOVE ****
*
* Xentral (c) Xentral ERP Software GmbH GmbH, Fuggerstrasse 11, D-86150 Augsburg, * Germany 2019 
*
**** END OF COPYRIGHT & LICENSE NOTICE *** DO NOT REMOVE ****
*/
?>
<?php

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;
use phpseclib3\Crypt\AES;
use Xentral\Components\Database\Database;
use Xentral\Components\Http\JsonResponse;
use Xentral\Modules\Onlineshop\Data\ShopConnectorOrderStatusUpdateResponse;
use XentralAdapters\Shopify\Data\Metafield;
use XentralAdapters\Shopify\Data\Product;
use XentralAdapters\Shopify\Data\ProductImage;
use XentralAdapters\Shopify\Data\ProductVariant;
use XentralAdapters\Shopify\Data\VariantMetafield;
use XentralAdapters\Shopify\ShopifyClient;

include_once 'Shopimporter_Shopify_Adapter.php';

/**
 * Class Shopimporter_Shopify
 */
class Shopimporter_Shopify extends ShopimporterBase
{
    protected static $requestcount;

    /** @var Application $app */
    public $app;

    public $errors;

    public $shopid;

    public $data;

    public $ShopifyURL = '';

    public $ShopifyAPIKey = '';

    public $ShopifyPassword = '';

    public $ShopifyToken = '';

    public $locations = '';

    public $location = '';

    public $bearbeiter;

    public $table;

    public $partial;

    public $logging;

    public $dump;

    public $allow0;

    public $gotpendig;

    public $fulfilledabziehen;

    public $foreignNumberProductName = 'shopifyproductid';

    public $foreignNumberVariantName = 'shopifyvariantid';

    public $archive = false;

    public $autofullfilltax;

    public $eigenschaftenzubeschreibung = false;

    public $timezone;

    protected $apiVersion = '2019-10';

    /** bool */
    protected $notifyCustomer = false;

    /** @var Shopimporter_Shopify_Adapter */
    protected $adapter;

    /** @var bool */
    protected $exportProperties = false;

    /** @var bool */
    protected $exportCategories = false;

    /** @var bool */
    protected $exportImages = false;

    /** @var bool */
    protected $createTitleFromProperties = false;

    /** @var bool */
    protected $createOptionNameFromProperties = false;

    /** @var string */
    protected $priceToExport = 'bruttopreis';

    /** @var Database */
    protected $database;

    /** @var \Xentral\Components\Logger\Logger */
    private $logger;

    /** @var ShopifyClient */
    private $shopify;

    /**
     * Shopimporter_Shopify constructor.
     *
     * @param Application $app
     * @param bool        $intern
     */
    public function __construct($app, $intern = false)
    {
        $this->app = $app;
        $this->database = $this->app->Container->get('Database');

        /** @var \Xentral\Components\Logger\Logger $logger */
        $this->logger = $this->app->Container->get('Logger');
        if ($intern) {
            return;
        }
        $this->app->ActionHandlerInit($this);

        $this->dump = true;

        $this->app->ActionHandler('list', 'Shopimporter_ShopifyList');
        $this->app->ActionHandler('auth', 'ImportAuth');
        $this->app->ActionHandler('getlist', 'ImportGetList');
        $this->app->ActionHandler('sendlist', 'ImportSendList');
        $this->app->ActionHandler('sendlistlager', 'ImportSendListLager');
        $this->app->ActionHandler('getarticle', 'ImportGetArticle');
        $this->app->ActionHandler('getauftraegeanzahl', 'ImportGetAuftraegeAnzahl');
        $this->app->ActionHandler('getauftrag', 'ImportGetAuftrag');
        $this->app->ActionHandler('deletearticle', 'ImportDeleteArticle');
        $this->app->ActionHandler('deleteauftrag', 'ImportDeleteAuftrag');
        $this->app->ActionHandler('updateauftrag', 'ImportUpdateAuftrag');
        $this->app->ActionHandler('artikelgruppen', 'ImportArtikelgruppen');
        $this->app->ActionHandler('getarticlelist', 'ImportGetArticleList');
        $this->app->ActionHandler('test', 'ImportTest');
        $this->app->ActionHandler('storniereauftrag', 'ImportStorniereAuftrag');

        $this->app->DefaultActionHandler('list');

        $this->app->ActionHandlerListen($app);
    }

    /**
     * @return string
     */
    public function getClickByClickHeadline()
    {
        return 'Bitte im Shopify Backend eine eigene App für Xentral anlegen und die Zugangsdaten hier eintragen.';
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function checkApiApp($data)
    {
        if (empty($data['data']['ShopifyToken'])) {
            foreach (['ShopifyURL', 'ShopifyAPIKey', 'ShopifyPassword'] as $field) {
                if (empty($data['data'][$field])) {
                    return ['success' => false, 'error' => sprintf('%s is empty', $field)];
                }
            }
        }

        $shops = $this->app->DB->SelectArr(
            sprintf(
          "SELECT `einstellungen_json`, `bezeichnung`,`id` 
        FROM `shopexport` WHERE `modulename` = 'shopimporter_shopify'"
      )
        );
        if (empty($shops)) {
            return [
                'info' => [
                    'Shop' => 'Shopify',
                    'info' => $data['data']['ShopifyURL'],
                ],
            ];
        }
        foreach ($shops as $shop) {
            $json = @json_decode($shop['einstellungen_json'], true);
            if (empty($json['felder']) || empty($json['felder']['ShopifyURL'])) {
                continue;
            }
            if ($json['felder']['ShopifyURL'] === $data['data']['ShopifyURL']) {
                return [
                    'success' => false,
                    'error' => sprintf('Shop with url %s already exists', $data['data']['ShopifyURL']),
                ];
            }
        }

        return [
            'info' => [
                'Shop' => 'Shopify',
                'info' => $data['data']['ShopifyURL'],
            ],
        ];
    }

    public function ImportGetArticleList()
    {
        $result = $this->adapter->call('products.json?fields=id&limit=100');
        $productIds = $result['data']['products'];

        while (!empty($result['links']['next'])) {
            $result = $this->adapter->call('products.json?' . $result['links']['next']);
            $productIds = array_merge($productIds, $result['data']['products']);
        }

        $response = array_map(function ($value) {
            return reset($value);
        }, $productIds);

        return $response;
    }

    public function ShopexportShow($id, $obj, $tab)
    {
        $json = $id <= 0 ? null : $this->app->DB->Select(
            sprintf(
          "SELECT `einstellungen_json` 
        FROM `shopexport` 
        WHERE `id` = %d AND `shoptyp` = 'intern' AND `modulename` = 'shopimporter_shopify'",
          $id
      )
        );
        if (empty($json)) {
            return;
        }
        $json = json_decode($json, true);
        if (!empty($json['felder']['locations']) && empty($json['felder']['location'])) {
            $this->app->Tpl->Add(
                'MESSAGE',
                '<div class="warning">Bitte stellen Sie einen Lagerstandort ein.</div>'
            );
        }
    }

    public function ShopifyImporterShopexportCreate($id)
    {
        if ($id <= 0 || !$this->app->DB->Select("SELECT id FROM shopexport WHERE shoptyp = 'intern' AND id = '${id}' AND modulename = 'shopimporter_shopify' LIMIT 1")) {
            return;
        }
        $this->app->DB->Update("UPDATE shopexport SET positionsteuersaetzeerlauben = 1 WHERE id = ${id} LIMIT 1");
    }

    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param $adapter
     */
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
    }

    public function EinstellungenStruktur($id = 0)
    {
        $options = $this->getKonfig($id, null);
        if (!empty($this->locations)) {
            $options = json_decode(base64_decode($this->locations), true);
        } else {
            $options = [];
        }

        return
      [
          'ausblenden' => ['abholmodus' => ['ab_nummer', 'status']],
          'functions' => ['getarticlelist'],
          'felder' => [
              'logging' => ['typ' => 'checkbox', 'bezeichnung' => '{|Erweiterte Protokollierung|}:'],
              'ShopifyURL' => ['typ' => 'text', 'bezeichnung' => '{|Shopify URL|}:', 'size' => 40],
              'ShopifyAPIKey' => ['typ' => 'text', 'bezeichnung' => '{|Shopify API-Key|}:', 'size' => 40],
              'ShopifyPassword' => ['typ' => 'text', 'bezeichnung' => '{|Shopify Passwort|}:', 'size' => 40],
              'ShopifyToken' => ['typ' => 'text', 'bezeichnung' => '{|Shopify Token|}:', 'size' => 40],
              'preisalsnetto' => ['typ' => 'checkbox', 'bezeichnung' => '{|Preise als Netto uebertragen|}:', 'default' => 0],
              'partial' => ['typ' => 'checkbox', 'bezeichnung' => '{|auch Teilversendete Auftr&auml;ge abholen|}:'],
              'allow0' => ['typ' => 'checkbox', 'bezeichnung' => '{|erlaube Verkauf von Varianten mit Lagerzahl 0|}:', 'default' => 1],
              'fulfilledabziehen' => ['typ' => 'checkbox', 'bezeichnung' => '{|Teilgelieferte Mengen abziehen|}:', 'default' => 0, 'defaultcreate' => 1],
              'gotpendig' => ['typ' => 'checkbox', 'bezeichnung' => '{|auch Pending Payment abholen|}:', 'default' => 0],
              'shopifytracking' => ['typ' => 'checkbox', 'bezeichnung' => '{|Tracking E-Mails &uuml;ber Shopify versenden|}:', 'default' => 0],
              'variantnameauseigenschaften' => ['typ' => 'checkbox', 'bezeichnung' => '{|Variantentitel aus Eigenschaften zusammensetzen|}:', 'default' => 0],
              'optionsnameauseigenschaften' => ['typ' => 'checkbox', 'bezeichnung' => '{|Bei Export Optionenname aus Eigenschaftenbezeichnungen zusammensetzen|}:', 'default' => 0],
              'eigenschaftenzubeschreibung' => ['typ' => 'checkbox', 'bezeichnung' => '{|Bei Import Optionen in Shopify zu Artikelbeschreibung übernehmen|}:', 'default' => 0],
              'locations' => ['typ' => 'hidden'],
              'location' => ['typ' => 'select', 'bezeichnung' => '{|Lagerstandort|}:', 'optionen' => $options],
              'autofullfilltax' => ['typ' => 'checkbox', 'bezeichnung' => '{|Versandsteuer nach Positionen ermitteln|}:'],
          ], ];
    }

    public function getKonfig($shopid, $data = null)
    {
        $this->timezone = 'Europe/Berlin';
        $this->shopid = $shopid;
        $this->data = $data;
        $this->bearbeiter = 'Cronjob';
        if (isset($this->app->User) && $this->app->User && method_exists($this->app->User, 'GetName')) {
            $this->bearbeiter = $this->app->DB->real_escape_string($this->app->User->GetName());
        }
        $einstellungen = $this->app->DB->SelectArr("SELECT einstellungen_json,eigenschaftenuebertragen,kategorienuebertragen,shopbilderuebertragen FROM shopexport WHERE id = '${shopid}' LIMIT 1");
        $einstellungen = reset($einstellungen);

        $this->exportProperties = (bool)$einstellungen['eigenschaftenuebertragen'];
        $this->exportCategories = (bool)$einstellungen['kategorienuebertragen'];
        $this->exportImages = (bool)$einstellungen['shopbilderuebertragen'];

        if ($einstellungen) {
            $einstellungen = json_decode($einstellungen['einstellungen_json'], true);
        }
        $this->ShopifyURL = trim($einstellungen['felder']['ShopifyURL']);
        if (empty($this->ShopifyURL)) {
            $this->ShopifyURL = 'missingShop.myshopify.com/';
        }
        if (stripos($this->ShopifyURL, 'http') === false) {
            $this->ShopifyURL = 'https://' . $this->ShopifyURL;
        }
        $this->ShopifyAPIKey = (string)$einstellungen['felder']['ShopifyAPIKey'];
        $this->ShopifyPassword = (string)$einstellungen['felder']['ShopifyPassword'];
        $this->ShopifyToken = $einstellungen['felder']['ShopifyToken'];
        if (empty($this->ShopifyToken)) {
            if (strpos($this->ShopifyURL, 'https://') !== false) {
                $this->ShopifyURL = 'https://' . $this->ShopifyAPIKey . ':' . $this->ShopifyPassword . '@' . str_replace('https://', '', $this->ShopifyURL);
            } else {
                $this->ShopifyURL = 'http://' . $this->ShopifyAPIKey . ':' . $this->ShopifyPassword . '@' . str_replace('http://', '', $this->ShopifyURL);
            }
        }
        $this->table = 'shopimporter_shopify_auftraege';
        $this->partial = (int)$einstellungen['felder']['partial'];
        $this->logging = (int)$einstellungen['felder']['logging'];
        $this->eigenschaftenzubeschreibung = (int)$einstellungen['felder']['eigenschaftenzubeschreibung'];
        $this->allow0 = (int)$einstellungen['felder']['allow0'];
        $this->fulfilledabziehen = (int)$einstellungen['felder']['fulfilledabziehen'];
        $this->gotpendig = (int)$einstellungen['felder']['gotpendig'];
        $this->preisalsnetto = (int)$einstellungen['felder']['preisalsnetto'];
        $this->shopifytracking = (int)$einstellungen['felder']['shopifytracking'];
        $this->notifyCustomer = (int)$einstellungen['felder']['shopifytracking'] === 1;
        $this->location = $einstellungen['felder']['location'];
        $this->locations = $einstellungen['felder']['locations'];
        $this->autofullfilltax = $einstellungen['felder']['autofullfilltax'];
        $this->createOptionNameFromProperties = (bool)$einstellungen['felder']['optionsnameauseigenschaften'];
        $this->createTitleFromProperties = (bool)$einstellungen['felder']['variantnameauseigenschaften'];
        $this->priceToExport = 'bruttopreis';
        if (!empty($einstellungen['felder']['preisalsnetto'])) {
            $this->priceToExport = 'preis';
        }

        if ($this->adapter === null) {
            $this->adapter = new Shopimporter_Shopify_Adapter($this->app, $this->ShopifyURL, $this->shopid, $this->ShopifyToken);
        }

        $this->shopify = ShopifyClient::create($this->ShopifyURL, $this->ShopifyAPIKey, $this->ShopifyPassword);
    }

    public function Shopimporter_ShopifyList()
    {
        $this->app->DB->Select('SELECT transaction_id FROM shopimporter_shopify_auftraege LIMIT 1');
        if ($this->app->DB->error()) {
            $this->Install();
        }
        if ($id = $this->app->DB->Select("SELECT id FROM shopexport WHERE modulename = 'shopimporter_shopify' ORDER BY aktiv = 1 DESC LIMIT 1")) {
            header('Location: index.php?module=onlineshops&action=edit&id=' . $id);
            exit;
        }
        $msg = $this->app->erp->base64_url_encode('<div class="info">Sie k&ouml;nnen hier die Shops einstellen</div>');
        header('Location: index.php?module=onlineshops&action=list&msg=' . $msg);
        exit;
    }

    public function Install()
    {
        $this->app->erp->CheckTable('shopimporter_shopify_auftraege');
        $this->app->erp->CheckColumn('id', 'int(11)', 'shopimporter_shopify_auftraege', 'NOT NULL AUTO_INCREMENT');
        $this->app->erp->CheckColumn('shop', 'INT(11)', 'shopimporter_shopify_auftraege', "DEFAULT '0' NOT NULL");
        $this->app->erp->CheckColumn('extid', 'varchar(32)', 'shopimporter_shopify_auftraege', "DEFAULT '' NOT NULL");
        $this->app->erp->CheckColumn('status', 'INT(11)', 'shopimporter_shopify_auftraege', "DEFAULT '0' NOT NULL");
        $this->app->erp->CheckColumn('bearbeiter', 'varchar(32)', 'shopimporter_shopify_auftraege', "DEFAULT '' NOT NULL");
        $this->app->erp->CheckColumn('zeitstempel', 'TIMESTAMP', 'shopimporter_shopify_auftraege', 'DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->app->erp->CheckColumn('transaction_id', 'varchar(64)', 'shopimporter_shopify_auftraege', "DEFAULT '' NOT NULL");
        $this->app->erp->CheckColumn('zahlungsweise', 'varchar(64)', 'shopimporter_shopify_auftraege', "DEFAULT '' NOT NULL");
        $this->app->erp->CheckIndex('shopimporter_shopify_auftraege', 'shop');
        $this->app->erp->CheckIndex('shopimporter_shopify_auftraege', 'extid');

        $this->app->erp->CheckTable('shopify_image_association');
        $this->app->erp->CheckColumn('id', 'int(11)', 'shopify_image_association', 'NOT NULL AUTO_INCREMENT');
        $this->app->erp->CheckColumn('image_id', 'int(11)', 'shopify_image_association', 'NOT NULL');
        $this->app->erp->CheckColumn('image_version', 'int(11)', 'shopify_image_association', 'NOT NULL');
        $this->app->erp->CheckColumn('external_id', 'varchar(255)', 'shopify_image_association', 'NOT NULL');
        $this->app->erp->CheckIndex('shopify_image_association', 'extid');

        $this->app->erp->RegisterHook('shopexport_create', 'shopimporter_shopify', 'ShopifyImporterShopexportCreate');
        $this->app->erp->RegisterHook('shopexport_show', 'shopimporter_shopify', 'ShopexportShow');
    }

    protected function prepareVariantData(Product $product, ProductVariant $variant): array
    {
        $tax = 1.0 + (float)$this->getTaxRateFromCountries();
        $variantData = [
            'name' => $product->getTitle() . ' ' . $variant->getTitle(),
            'uebersicht_de' => $product->getBodyHtml(),
            'hersteller' => $product->getVendor(),
            'aktiv' => $product->getStatus() === 'active' ? 1 : 0,
            'gewicht' => $variant->getWeight(),
            'nummer' => $variant->getSku(),
            'variante_von' => $product->getId(),
            'ean' => $variant->getBarcode(),
            'artikelnummerausshop' => $variant->getSku(),
            'nummerintern' => $variant->getId(),
            'preis_netto' => $this->preisalsnetto ? $variant->getPrice() / $tax : $variant->getPrice(),
            'restmenge' => $variant->getInventoryQuantity(),
            'fremdnummern' => [
                [
                    'nummer' => $variant->getId(),
                    'bezeichnung' => $this->foreignNumberVariantName,
                ]
            ]
        ];
        $optionCounter = 1;
        foreach ($product->getOptions() as $option) {
            $getOption = 'getOption' . $optionCounter;
            $variantData['matrixprodukt_wert' . $optionCounter] = $variant->$getOption();
            $optionCounter++;
        }
        /** @var ProductImage $image */
        foreach ($product->getImages() as $image) {
            if (in_array($variant->getId(), $image->getVariantIds(), true)) {
                $variantData['bilder'][] = [
                    'path' => $image->getSrc(),
                    'content' => @base64_encode(@file_get_contents($image->getSrc()))
                ];
            }
        }

        return $variantData;
    }

    public function ImportGetArticle()
    {
        $tmp = $this->CatchRemoteCommand('data');
        $nummer = $tmp['nummer'];
        if (isset($tmp['nummerintern'])) {
            $nummer = $tmp['nummerintern'];
        }

        try {
            $variant = $this->shopify->variants()->get($nummer);
            return $this->prepareVariantData($this->shopify->products()->get($variant->getProductId()), $variant);
        } catch (Exception $exception) {

        }

        try {
            $product = $this->shopify->products()->get($nummer);
        } catch (Exception $exception) {
            return [];
        }

        $variants = [];
        /** @var ProductVariant $variant */
        foreach ($product->getVariants() as $variant) {
            $variants[] = $this->prepareVariantData($product, $variant);
        }

        $productData = $variants[0];
        $optionCounter = 1;
        foreach ($product->getOptions() as $option) {
            $productData['matrixprodukt_gruppe' . $optionCounter] = $option['name'];
            $productData['matrixprodukt_optionen' . $optionCounter] = $option['values'];
            $optionCounter++;
        }
        $productData['name'] = $product->getTitle();

        if (count($variants) > 1) {
            $productData['fremdnummern'] = [];
            $productData['bilder'] = [];
            unset($productData['artikelnummerausshop'],
                $productData['matrixprodukt_wert1'],
                $productData['matrixprodukt_wert2'],
                $productData['matrixprodukt_wert3'],
                $productData['variante_von'],
                $productData['nummer'],
                $productData['nummerintern'],
            );
        }

        $productData['fremdnummern'][] =
            [
                'nummer' => $product->getId(),
                'bezeichnung' => $this->foreignNumberProductName,
            ];
        foreach ($product->getImages() as $image) {
            if (empty($image->getVariantIds())) {
                $productData['bilder'][] = [
                    'path' => $image->getSrc(),
                    'content' => @base64_encode(@file_get_contents($image->getSrc()))
                ];
            }
        }
              $this->adapter->call('products/' . $product->getId() . '/metafields.json', 'POST', ['metafield' => [
                'key' => 'sync_status',
                'value' => 1,
                'value_type' => 'integer',
                'namespace' => 'xentral',
              ]]);
              $result = $this->adapter->call('products/' . $nummer . '.json');

        if (count($variants) === 1) {
            return $productData;
        }

        return array_merge([$productData], $variants);
    }

    // Wenn WaWision Artikel abholt wird diese Funktion aufgerufen / es muss das $data array gefüllt werden

    /**
     * @return float|null
     */
    public function getTaxRateFromCountries()
    {
        $tax = null;
        $countries = $this->adapter->call('countries.json');
        if (empty($countries['data']) || empty($countries['data']['countries'])) {
            return null;
        }
        $xentralCountry = $this->app->erp->Firmendaten('land');
        foreach ($countries['data']['countries'] as $country) {
            if ($country['code'] !== $xentralCountry) {
                continue;
            }
            if (isset($country['tax'])) {
                return (float)$country['tax'];
            }

            return null;
        }

        return null;
    }

    public function DumpVar($variable)
    {
    }

    public function ImportTest()
    {
        $result = $this->adapter->call('locations.json');

        return $result['links'];
    }

    public function ImportSendListLager(): int
    {
        // TODO error/exception handling
        $tmp = $this->CatchRemoteCommand('data');

        $productsWithVariants = array_filter($tmp, function (array $product) {
            return !empty($product['artikel_varianten']);
        });

        $productsWithoutVariants = array_filter($tmp, function (array $product) {
            return empty($product['artikel_varianten']);
        });

        $stocks = $this->getVariantStockFromProducts($productsWithVariants) +
      $this->getStockForProductsWithoutVariants($productsWithoutVariants);

        $successCounter = 0;
        foreach ($stocks as $id => $stock) {
            $this->setInventoryLevelForVariantId($id, $stock);
            $successCounter++;
        }

        return $successCounter;
    }

    private function getVariantStockFromProducts(array $productsWithVariants): array
    {
        $variantStock = [];
        foreach ($productsWithVariants as $internalProduct) {
            foreach ($internalProduct['artikel_varianten'] as $variant) {
                $variantId = $this->getVariantIdByArticleId($variant['artikel']);

                if ($variantId === null) {
                    continue;
                }
                $variantStock[$variantId] = $this->calculateStock($variant['lag'], $variant['pseudolager']);
            }
        }

        return $variantStock;
    }

    /**
     * @param array $foreignNumbers
     *
     * @return int|null
     */
    protected function getVariantIdFromForeignNumbers(array $foreignNumbers): ?int
    {
        foreach ($foreignNumbers as $foreignNumber) {
            if (strtolower($foreignNumber['bezeichnung']) === $this->foreignNumberVariantName) {
                return (int)$foreignNumber['nummer'];
            }
        }

        return null;
    }

    /**
     * @param $stock
     * @param $pseudoStock
     *
     * @return int
     */
    protected function calculateStock($stock, $pseudoStock): int
    {
        $pseudoStock = trim($pseudoStock);
        if ($pseudoStock !== '') {
            return (int)$pseudoStock;
        }

        return (int)$stock;
    }

    private function getStockForProductsWithoutVariants(array $articlesWithoutVariants): array
    {
        $productStock = [];
        foreach ($articlesWithoutVariants as $article) {
            if (empty($article['artikelnummer_fremdnummern'])) {
                continue;
            }
            $variantId = $this->getVariantIdFromForeignNumbers($article['artikelnummer_fremdnummern']);
            if ($variantId === null) {
                $productId = $this->getProductIdFromForeignNumbers($article['artikelnummer_fremdnummern']);
                if ($productId === null) {
                    continue;
                }
                $product = $this->shopify->products()->get($productId);
                $variantId = $product->getVariants()[0]->getId();
                $this->FremdnummerInsert($article['artikel'], $variantId, $this->foreignNumberVariantName);
            }

            if (empty($variantId)) {
                continue;
            }

            $productStock[$variantId] = $this->calculateStock($article['anzahl_lager'], $article['pseudolager']);
        }

        return $productStock;
    }

    /**
     * @param array $foreignNumbers
     *
     * @return int|null
     */
    protected function getProductIdFromForeignNumbers(array $foreignNumbers): ?int
    {
        foreach ($foreignNumbers as $foreignNumber) {
            if (strtolower($foreignNumber['bezeichnung']) === $this->foreignNumberProductName) {
                return (int)$foreignNumber['nummer'];
            }
        }

        return null;
    }

    public function FremdnummerInsert($artikelid, $fremdnummer, $bezeichnung)
    {
        $shopid = $this->shopid;
        if (!empty($fremdnummer)) {
            $fremdnummerid = $this->app->DB->Select("SELECT id FROM artikelnummer_fremdnummern WHERE artikel=${artikelid} AND shopid='${shopid}' AND aktiv='1' AND bezeichnung='${bezeichnung}'");
            if ($fremdnummerid) {
                $this->app->DB->Update("UPDATE artikelnummer_fremdnummern SET nummer='${fremdnummer}' WHERE id ='${fremdnummerid}'");
            } else {
                $this->app->DB->Insert("INSERT INTO artikelnummer_fremdnummern (shopid, artikel, nummer, bezeichnung, bearbeiter) 
          VALUES ('${shopid}','${artikelid}','${fremdnummer}','${bezeichnung}', 'Shopimporter')");
            }
        }
    }

    /**
     * @param string $variantId
     * @param int    $targetStock
     */
    protected function setInventoryLevelForVariantId(string $variantId, int $targetStock): void
    {
        $variant = $this->shopify->variants()->get((int)$variantId);

        $adjustment = $targetStock - $variant->getInventoryQuantity();

        if ($adjustment === 0) {
            return;
        }

        $this->shopify->inventoryLevels()->adjust(
            $variant->getInventoryItemId(),
            $this->location,
            $adjustment
        );
    }

    protected function getProductIdForArticle($article): ?string
    {
        $productId = $this->getProductIdFromForeignNumbers($article['artikelnummer_fremdnummern'] ?? []);
        if ($productId === null) {
            if (isset($article['artikel_varianten'])) {
                $productSearchResult = $this->findProductsBySku($article['artikel_varianten'][0]['nummer']);
            } else {
                $productSearchResult = $this->findProductsBySku($article['nummer']);
            }
            if (count($productSearchResult['data']['data']['products']['edges']) === 1) {
                $productId = $productSearchResult['data']['data']['products']['edges'][0]['node']['legacyResourceId'];
            }
        }

        return $productId ?: null;
    }

    protected function getProductForArticle($article): Product
    {
        $productId = $this->getProductIdForArticle($article);

        if ($productId !== null) {
            try {
                return $this->shopify->products()->get($productId);
            } catch (Exception $exception) {
                //Product ID provided but not found in Shopify
            }
        }

        return new Product();
    }

    public function ImportSendList()
    {
        $tmp = $this->CatchRemoteCommand('data');
        $anzahl = 0;

        foreach ($tmp as $article) {
            if ($article['variante'] && $article['variantevon'] != '') {
                return 'error: Variantenexport ist nur über den Hauptartikel möglich.';
            }
            $artikel = $article['artikel'];
            if ($artikel === 'ignore') {
                continue;
            }

            $product = $this->getProductForArticle($article);

            try {
                $product
                    ->setTitle($article['name_de'])
                    ->setBodyHtml(htmlspecialchars_decode($article['uebersicht_de']))
                    ->setVendor($article['hersteller'])
                    ->setStatus($article['inaktiv'] ? Product::STATUS_DRAFT : Product::STATUS_ACTIVE);
                if ($this->exportCategories) {
                    $product->setProductType($article['kategoriename']);
                }

                if (!isset($article['artikel_varianten'])) {
                    $variant = new ProductVariant();
                    if (!empty($product->getVariants())) {
                        $variant = $product->getVariants()[0];
                    }
                    $variant
                        ->setPrice((float)$article[$this->priceToExport])
                        ->setCompareAtPrice(round(trim($article['pseudopreis']), 2))
                        ->setWeight((float)str_replace(',', '.', $article['gewicht']))
                        ->setInventoryManagement('shopify')
                        ->setSku(empty($article['artikelnummer']) ? $article['nummer'] : $article['artikelnummer'])
                        ->setBarcode(empty($article['ean']) ? null : $article['ean'])
                        ->setInventoryPolicy($article['restmenge'] ? 'deny' : 'continue');
                    $product->setVariants([$variant]);
                } else {
                    if (empty($article['matrix_varianten']['gruppen'])) {
                        $optionName = 'Default Title';
                        if ($this->createOptionNameFromProperties && isset($article['eigenschaften']) && count($article['eigenschaften']) > 0) {
                            $optionName = (implode(' | ', array_map(static function ($array) {
                                return $array['name'];
                            }, $article['eigenschaften'])));
                        }
                        $options[] = [
                            'name'     => $optionName,
                            'position' => 1,
                            'values'   => ['x'],];
                    } else {
                        $counter = 1;
                        $options = [];
                        foreach ($article['matrix_varianten']['gruppen'] as $groupName => $groupValues) {
                            $options[] = [
                                'name'     => $groupName,
                                'position' => $counter,
                                'values'   => array_keys($groupValues),];
                            $counter++;
                        }
                        $product->setOptions($options);
                    }
                    $variants = $this->processVariants($article, $product);
                    $product->setVariants($variants);
                }

                if ($product->getId() === null) {
                    $product = $this->shopify->products()->create($product);
                    $this->FremdnummerInsert(
                        $artikel,
                        $product->getId(),
                        $this->foreignNumberProductName
                    );
                } else {
                    $product = $this->shopify->products()->update($product);
                }

                if (empty($article['artikel_varianten'])) {
                    $this->FremdnummerInsert(
                        $artikel,
                        $product->getVariants()[0]->getId(),
                        $this->foreignNumberVariantName
                    );
                } else {
                    foreach ($article['artikel_varianten'] as $xentralVariant) {
                        foreach ($product->getVariants() as $shopifyVariant) {
                            if ($this->isVariationAssociated($xentralVariant, $shopifyVariant)) {
                                $this->FremdnummerInsert(
                                    $xentralVariant['artikel'],
                                    $shopifyVariant->getId(),
                                    $this->foreignNumberVariantName
                                );
                            }
                        }
                    }
                }

                $this->handleMetaFields($article, $product);

                $this->handleTranslations($article, $product);

                if ($this->exportImages) {
                    $this->handleImages($article, $product);
                }

                $anzahl++;
            } catch (\GuzzleHttp\Exception\RequestException $exception) {
                return 'error: ' . $this->handleGuzzleException(
                    $exception,
                    "Can't export product"
                );
            }
        }

        return $anzahl;
    }

    /**
     * Try to extract error message from exception response.
     * Otherwise, return $defaultMessage.
     */
    // TODO: Add unit test
    protected function handleGuzzleException(
        GuzzleException $exception,
        ?string $defaultMessage = "Can't proceed Shopify request."
    ): string {
        if (!$exception->hasResponse()) {
            return $defaultMessage;
        }

        $response = $exception->getResponse();
        $reason = $response->getReasonPhrase();

        $messages = json_decode($response->getBody(), true);
        if (is_null($messages) && json_last_error() !== JSON_ERROR_NONE) {
            // It's not json return it as is.
            $message = $response->getBody();
        } else {
            $message = join(',', Arr::flatten($messages));
        }

        return "${reason} ${message}";
    }

    protected function findProductsBySku(string $sku)
    {
        $data = [
            'query' => 'query {
        products(first:1, query:"sku:' . $sku . '") {
          edges {
            node {
              legacyResourceId
            }
          }
        }
      }',
        ];

        return $this->adapter->call('graphql.json', 'POST', $data, true);
    }

    /**
     * @param array   $article
     * @param Product $product
     *
     * @return ProductVariant[]
     */
    protected function processVariants(array $article, Product $product): array
    {
        $variants = [];

        $existingVariantIds = array_map(static function (ProductVariant $variant) {
            return $variant->getId();
        }, $product->getVariants());

        $foundVariantIds = [];

        foreach ($article['artikel_varianten'] as $xentralVariant) {
            $variationFound = false;
            foreach ($product->getVariants() as $variant) {
                $variationFound = $this->isVariationAssociated($xentralVariant, $variant);
                if (!$variationFound) {
                    continue;
                }
                $variants[] = $this->fillVariantWithData(
                    $xentralVariant,
                    $variant,
                    isset($article['matrix_varianten'])
            ? $article['matrix_varianten']['artikel'][$xentralVariant['artikel']]
            : []
                );
                $foundVariantIds[] = $variant->getId();
                break;
            }
            if (!$variationFound) {
                $variants[] = $this->fillVariantWithData(
                    $xentralVariant,
                    new ProductVariant(),
                    isset($article['matrix_varianten'])
            ? $article['matrix_varianten']['artikel'][$xentralVariant['artikel']]
            : []
                );
            }
        }

        foreach ($existingVariantIds as $existingVariantId) {
            if (!in_array($existingVariantId, $foundVariantIds, false)) {
                $this->shopify->variants()->delete($product->getId(), $existingVariantId);
            }
        }

        //TODO delete existing variants no longer needed
        return $variants;
    }

    protected function isVariationAssociated(array $xentralVariant, ProductVariant $shopifyVariant): bool
    {
        $query = sprintf("SELECT af.nummer FROM `artikelnummer_fremdnummern` AS `af` 
      WHERE af.artikel = %d
      AND af.shopid = %d
      AND af.bezeichnung = '%s'
      AND aktiv = 1", $xentralVariant['artikel'], $this->shopid, $this->foreignNumberVariantName);
        $foreignNumberVariantId = (int)$this->app->DB->Select($query);

        return $foreignNumberVariantId === $shopifyVariant->getId()
      || $xentralVariant['nummer'] === $shopifyVariant->getSku();
    }

    protected function fillVariantWithData(array $xentralVariant, ProductVariant $shopifyVariant, array $matrixInformation): ProductVariant
    {
        $titel = $xentralVariant['name_de'];

        $shopifyVariant
            ->setTitle($titel)
            ->setSku($xentralVariant['nummer'])
            ->setPrice((float)$xentralVariant[$this->priceToExport])
            ->setCompareAtPrice(round($xentralVariant['pseudopreis'], 2))
            ->setWeight((float)str_replace(',', '.', $xentralVariant['gewicht']))
            ->setInventoryManagement('shopify')
            ->setBarcode(empty($xentralVariant['ean']) ? null : $xentralVariant['ean'])
            ->setInventoryPolicy($xentralVariant['restmenge'] ? 'deny' : 'continue');

        if (empty($matrixInformation)) {
            if ($this->createTitleFromProperties && isset($xentralVariant['eigenschaften']) && count($xentralVariant['eigenschaften']) > 0) {
                $shopifyVariant->setTitle(implode(' | ', array_map(static function ($array) {
                    return $array['values'];
                }, $xentralVariant['eigenschaften'])));
            }
        } else {
            $shopifyVariant->setOption1($matrixInformation[0]['values']);
            if (isset($matrixInformation[1])) {
                $shopifyVariant->setOption2($matrixInformation[1]['values']);
            }
            if (isset($matrixInformation[2])) {
                $shopifyVariant->setOption3($matrixInformation[2]['values']);
            }
        }

        return $shopifyVariant;
    }

    protected function handleMetaFields(array $article, Product $product): void
    {
        $metaFieldsToProcess = $this->getMetaFieldsToProcess($article, $product);

        /** @var MetaField $metaField */
        foreach ($metaFieldsToProcess as $metaField) {
            if (empty($metaField->getValue())) {
                if ($metaField->getId() !== null) {
                    //Delete MetaFields with an empty value
                    $this->shopify->metafields()->delete($metaField->getId());
                }
            } else {
                if ($metaField->getId() === null) {
                    //Create new MetaFields
                    $this->shopify->metafields()->create($metaField);
                } else {
                    //Update existing MetaFields
                    $this->shopify->metafields()->update($metaField);
                }
            }
        }
    }

    protected function getMetaFieldsToProcess(array $article, Product $product): array
    {
        return array_merge(
            $this->getMetaFieldsForProductToProcess($article, $product),
            $this->getMetaFieldsForVariantsToProcess($article, $product)
        );
    }

    protected function getMetaFieldsForProductToProcess(array $article, Product $product): array
    {
        $metaInformation = [];

        if (!empty($article['freifelder'])) {
            $metaInformation = $this->getMetaInformationFromFreeFields($article['freifelder']);
        }

        $metaInformation['global']['title_tag'] = $article['metatitle_de'];
        $metaInformation['global']['description_tag'] = $article['metadescription_de'];

        if ($this->exportProperties) {
            foreach ($article['eigenschaften'] as $key => $value) {
                $metaInformation['global'][substr($value['name'], 0, 30)] = $value['values'];
            }
        }

        $productMetaFields = [];
        foreach ($metaInformation as $namespace => $metaData) {
            foreach ($metaData as $metaKey => $metaValue) {
                $productMetaFields[] = new Metafield(
                    null,
                    $metaKey,
                    $metaValue,
                    'string',
                    $namespace,
                    '',
                    $product->getId(),
                    'product'
                );
            }
        }

        $existingMetaFields = $this->shopify->metafields()->listFor('product', $product->getId());

        return $this->prepareMetaFieldsForProcessing($existingMetaFields, $productMetaFields);
    }

    protected function getMetaInformationFromFreeFields(array $freeFields, string $namespace = 'global'): array
    {
        $metaInformation = [];
        foreach ($freeFields as $iso => $freeFieldData) {
            foreach ($freeFieldData as $freeFieldName => $freeFieldValue) {
                if ($iso !== 'DE') {
                    continue;
                }
                $metaInformation[$namespace][$freeFieldName] = $freeFieldValue;
            }
        }

        return $metaInformation;
    }

    /**
     * @param Metafield[]                    $metaFieldsExistingInShopify
     * @param Metafield[]|VariantMetafield[] $metaFieldsFromXentral
     *
     * @return Metafield[]
     */
    protected function prepareMetaFieldsForProcessing(array $metaFieldsExistingInShopify, array $metaFieldsFromXentral): array
    {
        $metaFieldsXentral = [];
        foreach ($metaFieldsFromXentral as $metaField) {
            $metaFieldsXentral[$metaField->getNamespace() . $metaField->getKey()] = $metaField;
        }

        /** @var MetaField[] $metaFieldsToProcess */
        $metaFieldsToProcess = [];
        foreach ($metaFieldsExistingInShopify as $metaField) {
            $uniqueKey = $metaField->getNamespace() . $metaField->getKey();
            if (!isset($metaFieldsXentral[$uniqueKey])) {
                $metaField->setValue('');
            }
            $metaFieldsToProcess[$uniqueKey] = $metaField;
        }

        foreach ($metaFieldsXentral as $uniqueKey => $metaFieldFromXentral) {
            if (isset($metaFieldsToProcess[$uniqueKey])) {
                if ($metaFieldsToProcess[$uniqueKey]->getValue() === $metaFieldFromXentral->getValue()) {
                    //skip due to no change being made
                    unset($metaFieldsToProcess[$uniqueKey]);
                } else {
                    //amend
                    $metaFieldsToProcess[$uniqueKey]->setValue($metaFieldFromXentral->getValue());
                }
            } elseif (!empty($metaFieldFromXentral->getValue())) {
                //create new if it has a value
                $metaFieldsToProcess[$metaFieldFromXentral->getNamespace() . $metaFieldFromXentral->getKey()] = $metaFieldFromXentral;
            }
        }

        return array_values($metaFieldsToProcess);
    }

    protected function getMetaFieldsForVariantsToProcess(array $article, Product $product): array
    {
        $metaFieldsToProcess = [];
        if (!empty(($article['artikel_varianten']))) {
            foreach ($article['artikel_varianten'] as $xentralVariant) {
                foreach ($product->getVariants() as $shopifyVariant) {
                    if ($this->isVariationAssociated($xentralVariant, $shopifyVariant)) {
                        $metaInformation = [];
                        if (!empty($xentralVariant['freifelder'])) {
                            $metaInformation = $this->getMetaInformationFromFreeFields($xentralVariant['freifelder'], 'variant');
                        }

                        if ($this->exportProperties) {
                            foreach ($xentralVariant['eigenschaften'] as $property) {
                                $metaInformation['variant'][substr($property['name'], 0, 30)] = $property['values'];
                            }
                        }

                        $existingMetaFields = $this->shopify->metafields()->listForVariant($product->getId(), $shopifyVariant->getId());

                        $variantMetaFields = [];
                        foreach ($metaInformation as $namespace => $metaData) {
                            foreach ($metaData as $metaKey => $metaValue) {
                                $variantMetaFields[] = new VariantMetafield(
                                    null,
                                    $metaKey,
                                    $metaValue,
                                    'string',
                                    $namespace,
                                    '',
                                    $shopifyVariant->getId(),
                                    $product->getId()
                                );
                            }
                        }

                        $metaFieldsToProcess = array_merge($metaFieldsToProcess, $this->prepareMetaFieldsForProcessing($existingMetaFields, $variantMetaFields));

                        break;
                    }
                }
            }
        } else {
            $metaInformation = [];
            $metaInformation['global'] = [
                'harmonized_system_code' => $article['zolltarifnummer'],
                'sync_status' => 1,
            ];

            $variantMetaFields = [];
            foreach ($metaInformation as $namespace => $metaData) {
                foreach ($metaData as $metaKey => $metaValue) {
                    $variantMetaFields[] = new VariantMetafield(
                        null,
                        $metaKey,
                        $metaValue,
                        'string',
                        $namespace,
                        '',
                        $product->getVariants()[0]->getId(),
                        $product->getId()
                    );
                }
            }

            $existingMetaFields = $this->shopify->metafields()->listForVariant($product->getId(), $product->getVariants()[0]->getId());

            $metaFieldsToProcess = $this->prepareMetaFieldsForProcessing($existingMetaFields, $variantMetaFields);
        }

        return $metaFieldsToProcess;
    }

    protected function handleTranslations(array $article, Product $product): void
    {
        $productTranslations = [];
        $productTranslations['de'] = [
            'title' => $article['name_de'],
            'meta_title' => $article['metatitle_de'],
            'body_html' => htmlspecialchars_decode($article['uebersicht_de']),
            'meta_description' => $article['metadescription_de'],
        ];

        $productTranslations['en'] = [
            'title' => $article['name_en'],
            'meta_title' => $article['metatitle_en'],
            'body_html' => htmlspecialchars_decode($article['uebersicht_en']),
            'meta_description' => $article['metadescription_en'],
        ];

        foreach ($article['texte'] as $explicitTranslation) {
            $productTranslations[strtolower($explicitTranslation['sprache'])] = [
                'title' => $explicitTranslation['name'],
                'meta_title' => $explicitTranslation['meta_title'],
                'body_html' => $explicitTranslation['beschreibung_online'],
                'meta_description' => $explicitTranslation['meta_description'],
            ];
        }
        $this->translateProduct($product->getId(), $productTranslations);

        if (!empty($article['matrix_varianten']['texte'])) {
            $this->translateVariants($product->getId(), $article['matrix_varianten']['texte']);
        }

        $freeFieldTranslations = [];
        foreach ($article['freifelder'] as $iso => $freeFields) {
            if ($iso === 'DE') {
                continue;
            }
            foreach ($freeFields as $freeField) {
                $freeFieldTranslations[$freeField['mapping']][strtolower($iso)] = $freeField['wert'];
            }
        }
        $this->translateFreeFields($product->getId(), $freeFieldTranslations);
    }

    /**
     * @param string $productId
     * @param array  $translationData
     */
    public function translateProduct($productId, $translationData): void
    {
        $data = ['query' => 'query { translatableResource(resourceId: "gid://shopify/Product/' . $productId . '") { resourceId
      translatableContent { key value digest locale } } }'];
        $productInformationData = $this->adapter->call('graphql.json', 'POST', $data, true);

        $digestInformation = [];
        foreach ($productInformationData['data']['data']['translatableResource']['translatableContent'] as $information) {
            $digestInformation[$information['key']] = $information['digest'];
        }

        $translationQuery = 'mutation translationsRegister($resourceId: ID!, $translations: [TranslationInput!]!) {
        translationsRegister(resourceId: $resourceId, translations: $translations) {
        translations { key locale outdated value }
        userErrors { code field message } } }';
        foreach ($translationData as $iso => $translations) {
            $translationBlocks = [];
            foreach ($translations as $key => $translation) {
                $translationBlocks[] = [
                    'locale' => $iso,
                    'key' => $key,
                    'value' => $translation,
                    'translatableContentDigest' => $digestInformation[$key],
                ];
            }
            if (!empty($translationBlocks)) {
                $variables = [
                    'resourceId' => 'gid://shopify/Product/' . $productId,
                    'translations' => $translationBlocks,
                ];
                $translationRegister = [
                    'operationName' => 'translationsRegister',
                    'query' => $translationQuery,
                    'variables' => $variables,
                ];
                $this->adapter->call('graphql.json', 'POST', $translationRegister, true, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
        }
    }

    // delete an article

    public function translateVariants($productId, $variantTranslations): void
    {
        $variantsInShopify = $this->adapter->call("products/${productId}/variants.json", 'GET');

        $translationQuery = 'mutation translationsRegister($resourceId: ID!, $translations: [TranslationInput!]!) {
        translationsRegister(resourceId: $resourceId, translations: $translations) {
        translations { key locale outdated value }
        userErrors { code field message } } }';

        foreach ($variantsInShopify['data']['variants'] as $variant) {
            $variantId = $variant['id'];

            $digestQuery = '{ translatableResource(resourceId: "gid://shopify/ProductVariant/' . $variantId . '") { resourceId
                translatableContent { key value digest locale } } }';
            $data = ['query' => $digestQuery];
            $digestInformation = $this->adapter->call('graphql.json', 'POST', $data, true);

            $digestMapping = [];
            foreach ($digestInformation['data']['data']['translatableResource']['translatableContent'] as $information) {
                $digestMapping[$information['key']] = $information['digest'];
            }

            foreach ($variantTranslations['werte'] as $iso => $optionTranslations) {
                $translationBlocks = [];

                for ($i = 1; $i <= 3; $i++) {
                    $key = 'option' . $i;
                    if (!empty($variant[$key])
            && array_key_exists($variant[$key], $optionTranslations)
            && array_key_exists($key, $digestMapping)) {
                        $translationBlocks[] = [
                            'locale' => strtolower($iso),
                            'key' => $key,
                            'value' => $optionTranslations[$variant[$key]],
                            'translatableContentDigest' => $digestMapping[$key],
                        ];
                    }
                }

                if (empty($translationBlocks)) {
                    continue;
                }

                $variables = [
                    'resourceId' => $digestInformation['data']['data']['translatableResource']['resourceId'],
                    'translations' => $translationBlocks,
                ];
                $translationRegister = [
                    'operationName' => 'translationsRegister',
                    'query' => $translationQuery,
                    'variables' => $variables,
                ];
                $this->adapter->call('graphql.json', 'POST', $translationRegister, true, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
        }
    }

    /**
     * @param string $productId
     * @param array  $freeFieldTranslationData
     */
    public function translateFreeFields($productId, $freeFieldTranslationData): void
    {
        $query = '{ product(id: "gid://shopify/Product/' . $productId . '") {';
        $counter = 0;
        foreach ($freeFieldTranslationData as $metaFieldKey => $x) {
            $query .= sprintf(
                'metafield_%d: metafield(namespace: "global", key: "%s") { key id}',
                $counter,
                $metaFieldKey
            );
            $counter++;
        }
        $query .= ' } }';

        $data = ['query' => $query];
        $productKeysData = $this->adapter->call('graphql.json', 'POST', $data, true);

        $metaFieldIdInformation = [];
        foreach ($productKeysData['data']['data']['product'] as $metaFieldData) {
            $metaFieldIdInformation[$metaFieldData['key']] = $metaFieldData['id'];
        }

        $translationQuery = 'mutation translationsRegister($resourceId: ID!, $translations: [TranslationInput!]!) {
        translationsRegister(resourceId: $resourceId, translations: $translations) {
        translations { key locale outdated value }
        userErrors { code field message } } }';

        foreach ($freeFieldTranslationData as $metaFieldKey => $translationData) {
            if (!isset($metaFieldIdInformation[$metaFieldKey])) {
                continue;
            }

            $digestQuery = '{ translatableResource(resourceId: "' . $metaFieldIdInformation[$metaFieldKey] . '") { resourceId
                translatableContent { key value digest } } }';

            $data = ['query' => $digestQuery];
            $digestInformation = $this->adapter->call('graphql.json', 'POST', $data, true);

            if (empty($digestInformation['data']['data']['translatableResource']['translatableContent'][0]['digest'])) {
                continue;
            }
            $digest = $digestInformation['data']['data']['translatableResource']['translatableContent'][0]['digest'];

            $translationBlocks = [];
            foreach ($translationData as $iso => $translation) {
                $translationBlocks[] = [
                    'locale' => $iso,
                    'key' => 'value',
                    'value' => $translation,
                    'translatableContentDigest' => $digest,
                ];
            }
            if (!empty($translationBlocks)) {
                $variables = [
                    'resourceId' => $metaFieldIdInformation[$metaFieldKey],
                    'translations' => $translationBlocks,
                ];
                $translationRegister = [
                    'operationName' => 'translationsRegister',
                    'query' => $translationQuery,
                    'variables' => $variables,
                ];
                $this->adapter->call('graphql.json', 'POST', $translationRegister, true, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
        }
    }

    // receive all new articles

    protected function handleImages(array $article, Product $product): void
    {
        $internalImages = $this->prepareImageArrays($article);

        $unsortedExistingImages = $this->shopify->images()->list($product->getId());
        $existingImages = [];
        foreach ($unsortedExistingImages as $unsortedExistingImage) {
            $existingImages[$unsortedExistingImage->getId()] = $unsortedExistingImage;
        }

        $knownExternalImages = [];
        foreach ($internalImages as $internalImage) {
            if (empty($internalImage['external_id']) || !array_key_exists($internalImage['external_id'], $existingImages)) {
                $productImage = $this->shopify->images()->create($product->getId(), [
                    'attachment' => $internalImage['attachment'],
                    'filename' => $internalImage['filename'],
                    'variant_ids' => $internalImage['variant_ids'],
                ]);
                $internalImage['external_id'] = $productImage->getId();
                $this->saveImageAssociation($internalImage['file_id'], $internalImage['file_version'], $productImage->getId());
            }
            $knownExternalImages[] = $internalImage['external_id'];
        }

        foreach ($existingImages as $existingImage) {
            if (!in_array($existingImage->getId(), $knownExternalImages)) {
                $this->shopify->images()->delete($product->getId(), $existingImage->getId());
            }
        }
    }

    //get checksum list from onlineshop

    protected function prepareImageArrays(array $article)
    {
        $images = [];
        foreach ($article['Dateien'] as $value) {
            $images[] = [
                'file_id' => (int)$value['id'],
                'file_version' => (int)$value['version'],
                'external_id' => $this->getImageAssociation((int)$value['id'], (int)$value['version']),
                'attachment' => $value['datei'],
                'filename' => $value['filename'],
                'variant_ids' => [],
            ];
        }

        if (empty($article['artikel_varianten'])) {
            return $images;
        }

        foreach ($article['artikel_varianten'] as $variant) {
            $variantId = $this->getVariantIdByArticleId($variant['artikel']);
            if ($variantId === null) {
                continue;
            }

            $fileCount = !empty($variant['Dateien']['filename']) ? count($variant['Dateien']['filename']): 0;
            for ($i = $fileCount-1; $i >= 0; $i--) {
                $images[] = [
                    'file_id' => (int)$variant['Dateien']['id'][$i],
                    'file_version' => (int)$variant['Dateien']['version'][$i],
                    'external_id' => $this->getImageAssociation((int)$variant['Dateien']['id'][$i], (int)$variant['Dateien']['version'][$i]),
                    'attachment' => $variant['Dateien']['datei'][$i],
                    'filename' => $variant['Dateien']['filename'][$i],
                    'variant_ids' => [$variantId],
                ];
            }
        }

        return $images;
    }

    protected function getImageAssociation(int $fileId, int $version): ?int
    {
        $statement = $this->database
            ->select()
            ->cols(['external_id'])
            ->from('shopify_image_association')
            ->where('image_id = :file_id')
            ->where('image_version = :file_version')
            ->bindValue('file_id', $fileId)
            ->bindValue('file_version', $version)
            ->bindValue('description', $this->foreignNumberVariantName)
            ->limit(1);

        return $this->database->fetchValue($statement->getStatement(), $statement->getBindValues()) ?: null;
    }

    protected function getVariantIdByArticleId(int $articleId): ?int
    {
        $statement = $this->database
            ->select()
            ->cols(['nummer'])
            ->from('artikelnummer_fremdnummern')
            ->where('artikel = :article_id')
            ->where('LOWER(bezeichnung) = :description')
            ->where('shopid = :shop_id')
            ->bindValue('article_id', $articleId)
            ->bindValue('description', $this->foreignNumberVariantName)
            ->bindValue('shop_id', $this->shopid)
            ->limit(1);

        return $this->database->fetchValue($statement->getStatement(), $statement->getBindValues()) ?: null;
    }

    protected function saveImageAssociation(int $internalId, int $version, int $externalId): void
    {
        $statement = $this->database
            ->insert()
            ->into('shopify_image_association')
            ->cols(['image_id', 'image_version', 'external_id'])
            ->bindValues([
                'image_id' => $internalId,
                'image_version' => $version,
                'external_id' => $externalId,
            ]);

        $this->database->perform($statement->getStatement(), $statement->getBindValues()) ?: null;
    }

    public function ImportDeleteArticle()
    {

    }

    public function ImportArtikelgruppen()
    {
        return;
        $tmp = $this->CatchRemoteCommand('data');
        $anzahl = 0;
        $this->app->DB->Delete('DELETE FROM artikelgruppen');
        $ctmp = !empty($tmp) ? count($tmp) : 0;
        for ($i = 0; $i < $ctmp; $i++) {
            $id = $tmp[$i]['id'];

            $this->app->DB->Insert("INSERT INTO artikelgruppen (id) VALUES ('${id}')");

            foreach ($tmp[$i] as $key => $value) {
                $this->app->DB->Update("UPDATE artikelgruppen SET ${key}='${value}' WHERE id='${id}' LIMIT 1");
            }

            $anzahl++;
        }

        return $anzahl;
    }

    public function ImportGetAuftraegeAnzahl()
    {
        $tmp = $this->CatchRemoteCommand('data');
        if (!empty($tmp['ab_nummer'])) {
            $tmp['ab_nummer']--;
        }
        if (!empty($tmp['ab_nummer'])) {
            if (!empty($tmp['holeallestati']) && $tmp['holeallestati'] == 1) {
                $result = $this->adapter->call('orders.json?status=any&since_id=' . $tmp['ab_nummer'] . '&limit=25');
            } else {
                $result = $this->adapter->call('orders.json?fulfillment_status=unshipped&since_id=' . $tmp['ab_nummer'] . '&limit=25');
                if (!(isset($result['data']['orders']) && count($result['data']['orders']) >= 25) && $this->partial) {
                    $result2 = $this->adapter->call('orders.json?fulfillment_status=partial&since_id=' . $tmp['ab_nummer'] . '&limit=25');
                    if (isset($result2['data']['orders'])) {
                        return count($result['orders']) + count($result2['data']['orders']);
                    }

                    return count($result['orders']);
                }
            }
            if (isset($result['data']['orders'])) {
                return count($result['data']['orders']);
            }

            return 0;
        } elseif (!empty($tmp['nummer'])) {
            $result = $this->adapter->call('orders.json?status=any&ids=' . $tmp['nummer']);
            if (!empty($result['data']['orders'])) {
                return 1;
            }

            return 0;
        }
        if ((int)$tmp['count'] >= 25 || !$this->partial) {
            return $tmp['count'];
        }
        $result2 = $this->adapter->call('orders/count.json?fulfillment_status=partial&financial_status=paid&limit=25');

        return (int)$tmp['count'] + (int)$result2['data']['count'];
    }

    public function ImportGetAuftrag()
    {
        $this->log('Shopify Import started');
        $alleabholen = false;
        $_tmp = $this->CatchRemoteCommand('data');
        if (!empty($_tmp['archive'])) {
            $this->archive = true;
        }

        $minTime = $this->validateDate($_tmp['datumvon'])
      ? $_tmp['datumvon']
      : 'now - 90 days';

        $von = new DateTime($minTime, new DateTimeZone($this->timezone));
        $von->setTimeZone(new DateTimeZone('UTC'));
        $von = $von->format('Y-m-d\\TH:i:s\\Z');

        $maxTime = $this->validateDate($_tmp['datumbis'])
      ? $_tmp['datumbis']
      : 'now';

        $bis = new DateTime($maxTime, new DateTimeZone($this->timezone));
        $bis->setTimeZone(new DateTimeZone('UTC'));
        $bis = $bis->format("Y-m-d\TH:i:s\Z");

        $bismax = new DateTime('now', new DateTimeZone($this->timezone));
        $bismax->setTimeZone(new DateTimeZone('UTC'));
        $bismax = $bismax->format("Y-m-d\TH:i:s\Z");
        if (strtotime($bis) > strtotime($bismax)) {
            $bis = $bismax;
        }
        $demomodus = $this->app->DB->Select("SELECT demomodus FROM shopexport WHERE id = '" . $this->shopid . "' LIMIT 1");

        // Wenn man keinen kleinen timeout eingestellt hat und kein Problem damit das es etwas länger dauert,
        // kann man hier die Anzahl der Aufträge die auf eimal abgeholt werden sollen einstellen.
        $anzGleich = empty($_tmp['anzgleichzeitig']) ? 0 : (int)$_tmp['anzgleichzeitig'];

        $exitzeitstempel = null;
        if ($anzGleich < 1) {
            $anzGleich = 1;
        }
        $_anzGleich = $anzGleich;
        if ($anzGleich >= 50) {
            $anzGleich = 100;
        }
        if ($anzGleich == 1) {
            $anzGleich = 100;
        }

        if (empty($_tmp['nummer'])) {
            $this->CheckOldAuftrag();
            $paid = 'paid';
            if ($this->gotpendig) {
                $paid = 'paid,pending';
            }
            if ($alleabholen) {
                $result = $this->adapter->call("orders.json?financial_status=${paid}&limit=" . $anzGleich . '&updated_at_min=' . $von . '&updated_at_max=' . $bis);
                $result = $result['data'];
            } else {
                $result = $this->adapter->call("orders.json?fulfillment_status=unshipped&financial_status=${paid}&limit=" . $anzGleich . '&updated_at_min=' . $von . '&updated_at_max=' . $bis);
                $result = $result['data'];
                if ($this->partial) {
                    $result2 = $this->adapter->call("orders.json?fulfillment_status=partial&financial_status=${paid}&limit=" . $anzGleich . '&updated_at_min=' . $von . '&updated_at_max=' . $bis);
                    $result2 = $result2['data'];
                    if (count($result2['orders']) > 0 && count($result['orders']) == 0) {
                        $result = $result2;
                    } elseif (count($result2['orders']) > 0 && count($result['orders']) > 0) {
                        $result['orders'] = array_merge($result['orders'], $result2['orders']);
                        unset($result2);
                    }
                }
            }
            $this->log('Shopify Log at LOC 1649', [
                'querystring' => '1limit=' . $anzGleich . '&updated_at_min=' . $von . '&updated_at_max=' . $bis,
            ]);
            if (count($result['orders']) == 0) {
                $this->log('Finished: order count 0');

                return;
            }
            $maxzaehler = 40;

            $startdate = $this->app->DB->Select(
                "SELECT `startdate` FROM shopexport WHERE `id` = {$this->shopid} AND startdate!= '0000-00-00'"
            );
            $startdate = empty($startdate) ? 0 : (int)strtotime($startdate);

            //$erg[] = array(0,count($result['orders']),"/admin/orders.json?fulfillment_status=shipped&status=any&financial_status=paid&limit=".$anzGleich."&updated_at_min=".$von."&updated_at_max=".$bis);
            while (count($result['orders']) >= $anzGleich || count($result['orders']) == 0) {
                $maxzaehler--;
                if ($maxzaehler <= 0) {
                    if ($exitzeitstempel) {
                        $this->app->DB->Insert("INSERT INTO `shopexport_log` (`shopid`,`typ`,`bearbeiter`,`parameter1`,`parameter2`) VALUES ('" . $this->shopid . "','GetAuftrag','" . $this->bearbeiter . "','Es sind Auftr&auml;ge vorhanden, wurden aber nicht abgeholt','')");

                        date_default_timezone_set($this->timezone);
                        $this->log('Shopify import finished LOC: 1675', ['zeitstempel' => date('Y-m-d H:i:s', strtotime($exitzeitstempel))]);

                        return ['zeitstempel' => date('Y-m-d H:i:s', strtotime($exitzeitstempel))];
                    }

                    return '';
                }
                if (count($result['orders']) == 0) {
                    date_default_timezone_set('UTC');
                    $von = !empty($zwischen) ? $zwischen : $von;
                    $_von = strtotime($von);
                    $_bis = strtotime($bis);
                    $_zwischen = ceil(($_bis + $_von) / 2);
                    $zwischen = date('Y-m-d\TH:i:s\Z', $_zwischen);
                    if ($alleabholen) {
                        $result = $this->adapter->call("orders.json?financial_status=${paid}&limit=" . $anzGleich . '&updated_at_min=' . $von . '&updated_at_max=' . $zwischen);
                        $result = $result['data'];
                    } else {
                        $result = $this->adapter->call("orders.json?fulfillment_status=unshipped&financial_status=${paid}&limit=" . $anzGleich . '&updated_at_min=' . $von . '&updated_at_max=' . $zwischen);
                        $this->log('Shopify Log at LOC: 1696', ['querystring' => '2limit=' . $anzGleich . '&updated_at_min=' . $von . '&updated_at_max=' . $zwischen]);
                        $result = $result['data'];

                        if ($this->partial) {
                            $result2 = $this->adapter->call("orders.json?fulfillment_status=partial&financial_status=${paid}&limit=" . $anzGleich . '&updated_at_min=' . $von . '&updated_at_max=' . $zwischen);
                            $result2 = $result2['data'];

                            if (count($result2['orders']) > 0 && count($result['orders']) == 0) {
                                $result = $result2;
                            } elseif (count($result2['orders']) > 0 && count($result['orders']) > 0) {
                                $result['orders'] = array_merge($result['orders'], $result2['orders']);
                                unset($result2);
                            }
                        }
                    }

                    $this->log('Shopify Log at LOC 1715', [
                        'von' => date('Y-m-d\TH:i:s\Z', $von),
                        'zwischen' => date('Y-m-d\TH:i:s\Z', $zwischen),
                        'bis' => date('Y-m-d\TH:i:s\Z', $bis),
                    ]);

                    $erg[] = [3, count($result['orders']), "orders/count.json?fulfillment_status=shipped&status=any&financial_status=${paid}&updated_at_min=" . $von . '&updated_at_max=' . $zwischen];
                    if ((count($result['orders']) < $anzGleich) && count($result['orders']) > 0) {
                        break;
                    }
                    if (count($result['orders']) == 0) {
                        $exitzeitstempel = $zwischen;
                    }
                } else {
                    date_default_timezone_set('UTC');
                    $_von = strtotime($von);
                    if (isset($zwischen)) {
                        $_bis = strtotime($zwischen);
                    } else {
                        $_bis = strtotime($bis);
                    }
                    $_zwischen = ceil(($_bis + $_von) / 2);
                    $zwischen = date('Y-m-d\TH:i:s\Z', $_zwischen);
                    if ($alleabholen) {
                        $result = $this->adapter->call("orders.json?financial_status=${paid}&limit=" . $anzGleich . '&updated_at_min=' . $von . '&updated_at_max=' . $zwischen);
                        $result = $result['data'];
                    } else {
                        $result = $this->adapter->call("orders.json?fulfillment_status=unshipped&financial_status=${paid}&limit=" . $anzGleich . '&updated_at_min=' . $von . '&updated_at_max=' . $zwischen);
                        $result = $result['data'];

                        if ($this->partial) {
                            $result2 = $this->adapter->call("orders.json?fulfillment_status=partial&financial_status=${paid}&limit=" . $anzGleich . '&updated_at_min=' . $von . '&updated_at_max=' . $zwischen);
                            $result2 = $result2['data'];

                            if (count($result2['orders']) > 0 && count($result['orders']) == 0) {
                                $result = $result2;
                            } elseif (count($result2['orders']) > 0 && count($result['orders']) > 0) {
                                $result['orders'] = array_merge($result['orders'], $result2['orders']);
                                unset($result2);
                            }
                        }
                    }

                    $this->log('Shopify Log at LOC: 1764', [
                        'querystring' => '3limit=' . $anzGleich . '&updated_at_min=' . $von . '&updated_at_max=' . $zwischen,
                        'von' => date('Y-m-d\TH:i:s\Z', $_von),
                        'zwischen' => date('Y-m-d\TH:i:s\Z', $zwischen),
                        'bis' => date('Y-m-d\TH:i:s\Z', $_bis),
                    ]);

                    if ((count($result['orders']) < $anzGleich) && count($result['orders']) > 0) {
                        break;
                    }
                    if (count($result['orders']) == 0) {
                        $exitzeitstempel = $zwischen;
                    }
                }
            }
        } else {
            if (!empty($_tmp['ab_nummer'])) {
                $_tmp['ab_nummer']--;
            }
            if (!empty($_tmp['ab_nummer'])) {
                if (!empty($_tmp['holeallestati']) && $_tmp['holeallestati'] == 1) {
                    $result = $this->adapter->call('orders.json?status=any&since_id=' . $_tmp['ab_nummer']);
                    $result = $result['data'];
                } else {
                    $result = $this->adapter->call('orders.json?fulfillment_status=unshipped&since_id=' . $_tmp['ab_nummer']);
                    $result = $result['data'];

                    if ($this->partial) {
                        $result2 = $this->adapter->call('orders.json?fulfillment_status=partial&since_id=' . $_tmp['ab_nummer']);
                        $result2 = $result2['data'];

                        if (count($result2['orders']) > 0 && count($result['orders']) == 0) {
                            $result = $result2;
                        } elseif (count($result2['orders']) > 0 && count($result['orders']) > 0) {
                            $result['orders'] = array_merge($result['orders'], $result2['orders']);
                            unset($result2);
                        }
                    }
                }
            } elseif (!empty($_tmp['nummer'])) {
                $result = $this->adapter->call('orders.json?status=any&ids=' . $_tmp['nummer']);
                $result = $result['data'];
            } else {
                $result = $this->adapter->call('orders.json?fulfillment_status=unshipped&financial_status=paid&limit=1');
                $result = $result['data'];

                if ($this->partial) {
                    $result2 = $this->adapter->call('orders.json?fulfillment_status=partial&financial_status=paid&limit=1');
                    $result2 = $result2['data'];

                    if (count($result2['orders']) > 0 && count($result['orders']) == 0) {
                        $result = $result2;
                    } elseif (count($result2['orders']) > 0 && count($result['orders']) > 0) {
                        $result['orders'] = array_merge($result['orders'], $result2['orders']);
                        unset($result2);
                    }
                }
            }
        }

        if ($anzGleich >= 1) {
            $anzahl = count($result['orders']);
        } else {
            $anzahl = 1;
        }

        if (($_anzGleich == 1 || $demomodus) && $anzahl > 1) {
            $anzahl = 1;
        }
        $j = -1;
        for ($i = 0; $i < $anzahl; $i++) {
            $auftrag = (string)$result['orders'][$i]['id'];
            if ($auftrag === '') {
                continue;
            }

            $this->app->DB->Select('SELECT transaction_id FROM shopimporter_shopify_auftraege LIMIT 1');
            if ($this->app->DB->error()) {
                $this->Install();
            }

            if (!$this->archive && ($checkid = $this->app->DB->Select('SELECT id FROM `' . $this->table . "` WHERE extid = '${auftrag}' AND extid <> '' AND shop = '" . $this->shopid . "' LIMIT 1"))) {// && (($i < $anzahl - 1) || $j > -1))
                if ($result['orders'][$i]['updated_at']) {
                    if ($exitzeitstempel) {
                        if (strtotime($exitzeitstempel) < strtotime($result['orders'][$i]['updated_at'])) {
                            $exitzeitstempel = $result['orders'][$i]['updated_at'];
                        }
                    } else {
                        $exitzeitstempel = $result['orders'][$i]['updated_at'];
                    }
                }
                if ($this->app->DB->Select("SELECT id FROM shopimporter_shopify_auftraege WHERE id = '${checkid}' AND transaction_id = '' LIMIT 1")) {
                    $transactionsarr = $this->adapter->call('orders/' . $result['orders'][$i]['id'] . '/transactions.json');
                    $transactionsarr = $transactionsarr['data'];
                    if (isset($transactionsarr['transactions']) && isset($transactionsarr['transactions'][0])) {
                        $transactionsarr = $transactionsarr['transactions'][0];
                        if (isset($transactionsarr['authorization']) && (string)$transactionsarr['authorization'] !== '') {
                            if (isset($transactionsarr['receipt']) && isset($transactionsarr['receipt']['transaction_id']) && (string)$transactionsarr['receipt']['transaction_id'] !== '') {
                                $transaction_id = $this->app->DB->real_escape_string($transactionsarr['receipt']['transaction_id']);
                            } else {
                                $transaction_id = $this->app->DB->real_escape_string($transactionsarr['authorization']);
                            }
                            $this->app->DB->Update("UPDATE shopimporter_shopify_auftraege SET transaction_id = '${transaction_id}',zahlungsweise ='" . $this->app->DB->real_escape_string((string)$result['orders'][$i]['gateway']) . "' WHERE id = '${checkid}' LIMIT 1");
                        }
                    }
                }
                continue;
            }
            if (!$this->archive && ($result['orders'][$i]['fulfillment_status'] === 'fulfilled' && empty($_tmp['nummer']))) {
                continue;
            }

            $purchaseDate = substr($result['orders'][$i]['created_at'], 0, 10);
            if ($purchaseDate > 0 && $startdate > strtotime($purchaseDate) && $startdate > 0) {
                continue;
            }

            $j++;
            $warenkorb = [];
            $warenkorb2 = [];

            //if($this->app->Conf->Debug)$warenkorb['result'] = $result;
            $warenkorb['auftrag'] = $result['orders'][$i]['id'];
            $warenkorb['auftragsdaten'] = $result['orders'][$i];
            $warenkorb['orderData'] = $result['orders'][$i];
            date_default_timezone_set($this->timezone);
            $warenkorb['zeitstempel'] = date('Y-m-d H:i:s', strtotime($result['orders'][$i]['updated_at']));
            $warenkorb['gesamtsumme'] = $result['orders'][$i]['total_price'];

            if (!empty($result['orders'][$i]['note_attributes']) &&
        is_array($result['orders'][$i]['note_attributes']) &&
        count($result['orders'][$i]['note_attributes']) > 0) {
                foreach ($result['orders'][$i]['note_attributes'] as $kn => $vn) {
                    if (isset($vn['name']) && isset($vn['value']) && $vn['name'] === 'vat_id') {
                        $warenkorb['ustid'] = (string)$vn['value'];
                    } elseif ($vn['name'] === 'Delivery-Date' && $vn['value']) {
                        try {
                            $wunschlieferdatum = DateTime::createFromFormat('d/m/Y', (string)$vn['value']);
                            $warenkorb['lieferdatum'] = $wunschlieferdatum->format('Y-m-d');
                            unset($result['orders'][$i]['note_attributes'][$kn]);
                        } catch (Exception $x) {
                        }
                    }
                }
            }
            $steuersaetze = [];
            //$warenkorb['result'] = $result;
            $warenkorb['transaktionsnummer'] = $result['orders'][$i]['token'];

            $transactionsarr = $this->adapter->call('/orders/' . $result['orders'][$i]['id'] . '/transactions.json');
            $transactionsarr = $transactionsarr['data'];
            $warenkorb['orderData']['transactions'] = $transactionsarr;
            $warenkorb['zahlungsweise'] = (string)$result['orders'][$i]['gateway'];
            if (isset($transactionsarr['transactions']) && isset($transactionsarr['transactions'][0])) {
                foreach ($transactionsarr['transactions'] as $transAction) {
                    if (isset($transAction['authorization']) && (string)$transAction['authorization'] !== '') {
                        if (!empty($transAction['status']) && $transAction['status'] === 'failure') {
                            continue;
                        }
                        $warenkorb['transaktionsnummer'] = $transAction['authorization'];
                        if (!empty($transAction['receipt']) && isset($transAction['receipt']['transaction_id']) && (string)$transAction['receipt']['transaction_id'] !== '') {
                            $warenkorb['transaktionsnummer'] = $transAction['receipt']['transaction_id'];
                        }
                        if (!empty($transAction['receipt']['user_variable_0']) && $transAction['authorization'] === $transAction['receipt']['transaction']) {
                            $warenkorb['transaktionsnummer'] = $transAction['receipt']['user_variable_0'];
                        }
                    }
                    if (
            in_array($result['orders'][$i]['financial_status'], ['pending', 'paid'])
            && !empty($transAction['gateway'])
            && !empty($transAction['status'])
            && $transAction['status'] === $result['orders'][$i]['financial_status']
          ) {
                        $warenkorb['zahlungsweise'] = (string)$transAction['gateway'];
                        $warenkorb['transaktionsnummer'] = $transAction['authorization'];
                        if (!empty($transAction['receipt']) && isset($transAction['receipt']['transaction_id']) && (string)$transAction['receipt']['transaction_id'] !== '') {
                            $warenkorb['transaktionsnummer'] = $transAction['receipt']['transaction_id'];
                        }
                        if (!empty($transAction['receipt']['user_variable_0']) && $transAction['authorization'] === $transAction['receipt']['transaction']) {
                            $warenkorb['transaktionsnummer'] = $transAction['receipt']['user_variable_0'];
                        }
                        if ($transAction['status'] === 'paid') {
                            break;
                        }
                    }
                }
                unset($transAction);
            }
            unset($transactionsarr);
            $warenkorb['onlinebestellnummer'] = $result['orders'][$i]['name'];
            if (empty($warenkorb['onlinebestellnummer'])) {
                $warenkorb['onlinebestellnummer'] = $result['orders'][$i]['order_number'];
            }
            $taxes_included = (int)$result['orders'][$i]['taxes_included'];
            if ($taxes_included) {
                $taxFromShippingCountry = !empty(
        $this->app->DB->Select(
            sprintf(
              'SELECT `steuerfreilieferlandexport` FROM `shopexport` WHERE `id` = %d',
              $this->shopid
          )
        )
        );
                $addressField = $taxFromShippingCountry ? 'shipping_address' : 'billing_address';
                $isTaxFree = $this->app->erp->Export($result['orders'][$i][$addressField]['country_code']);
                if (!empty($warenkorb['ustid']) && !$isTaxFree) {
                    $isTaxFree = $this->app->erp->IsEU($result['orders'][$i][$addressField]['country_code']);
                }
                if ($result['orders'][$i]['total_tax'] == 0 && $isTaxFree) {
                    $warenkorb['versandkostennetto'] = $result['orders'][$i]['shipping_lines'][0]['price'];
                } else {
                    $warenkorb['versandkostenbrutto'] = $result['orders'][$i]['shipping_lines'][0]['price'];
                }
            } else {
                if (isset($result['orders'][$i]['shipping_lines'][0]['tax_lines']) && isset($result['orders'][$i]['shipping_lines'][0]['tax_lines'][0]) && isset($result['orders'][$i]['shipping_lines'][0]['tax_lines'][0]['price']) && $result['orders'][$i]['shipping_lines'][0]['tax_lines'][0]['price'] > 0) {
                    if (isset($result['orders'][$i]['shipping_lines'][0]['tax_lines'][0]['rate']) && $result['orders'][$i]['shipping_lines'][0]['tax_lines'][0]['rate'] > 0 && $result['orders'][$i]['shipping_lines'][0]['tax_lines'][0]['rate'] <= 0.1) {
                        $warenkorb['portosteuersatz'] = 'ermaessigt';
                    }
                    if (isset($result['orders'][$i]['shipping_lines'][0])) {
                        $warenkorb['versandkostenbrutto'] = $result['orders'][$i]['shipping_lines'][0]['price'] * (1 + $result['orders'][$i]['shipping_lines'][0]['tax_lines'][0]['rate']);
                    }
                } else {
                    if (isset($result['orders'][$i]['shipping_lines'][0])) {
                        $warenkorb['versandkostennetto'] = $result['orders'][$i]['shipping_lines'][0]['price'];
                    }
                }
            }
            //$warenkorb[versandkostennetto] = $result[data][invoiceShippingNet]; //TODO

            $artikelsteuer = 0;
            foreach ($result['orders'][$i]['line_items'] as $likey => $livalue) {
                if ($livalue['tax_lines'][0]['rate'] > $artikelsteuer) {
                    $artikelsteuer = $livalue['tax_lines'][0]['rate'];
                }
            }
            if ($artikelsteuer < 0.1 && $artikelsteuer > 0) {
                $warenkorb['portosteuersatz'] = 'ermaessigt';
            }

            if (isset($result['orders'][$i]['shipping_lines'][0])) {
                $warenkorb['lieferung'] = (string)$result['orders'][$i]['shipping_lines'][0]['carrier_identifier'];
            }
            if (isset($result['orders'][$i]['shipping_lines'][0]) && $warenkorb['lieferung'] === '') {
                $warenkorb['lieferung'] = (string)$result['orders'][$i]['shipping_lines'][0]['title'];
            }
            $warenkorb['rabattnetto'] = -abs($result['orders'][$i]['total_discounts']);
            $warenkorb['rabattsteuer'] = 0;

            if ($result['orders'][$i]['billing_address']['company'] != '') {
                $warenkorb['name'] = $result['orders'][$i]['billing_address']['company'];
                $warenkorb['ansprechpartner'] = $result['orders'][$i]['billing_address']['name'];
                if ($this->app->DB->Select("SELECT id FROM adresse_typ WHERE type = 'firma' AND aktiv = 1 AND geloescht = 0 LIMIT 1")) {
                    $warenkorb['anrede'] = 'firma';
                }
            } else {
                $warenkorb['name'] = $result['orders'][$i]['billing_address']['name'];
                $warenkorb['ansprechpartner'] = '';
                $projekt = $this->app->DB->Select("SELECT projekt FROM shopexport WHERE id = '" . $this->shopid . "' LIMIT 1");
                if ($this->app->DB->Select("SELECT id FROM adresse_typ WHERE type = 'privat' AND aktiv = 1 AND geloescht = 0 AND (projekt = 0 OR projekt = '" . $projekt . "') LIMIT 1")) {
                    $warenkorb['anrede'] = 'privat';
                }
            }

            if (strlen($result['orders'][$i]['billing_address']['address2']) < 5) {
                $warenkorb['strasse'] = $result['orders'][$i]['billing_address']['address1'] . ' ' . $result['orders'][$i]['billing_address']['address2'];
            } else {
                $warenkorb['strasse'] = $result['orders'][$i]['billing_address']['address1'];
                $warenkorb['adresszusatz'] = $result['orders'][$i]['billing_address']['address2'];
            }

            $warenkorb['plz'] = $result['orders'][$i]['billing_address']['zip'];
            $warenkorb['ort'] = $result['orders'][$i]['billing_address']['city'];
            $warenkorb['land'] = $result['orders'][$i]['billing_address']['country_code'];
            $warenkorb['email'] = $result['orders'][$i]['contact_email'];
            if (isset($result['orders'][$i]['billing_address']['province']) && $result['orders'][$i]['billing_address']['province'] != '') {
                $warenkorb['bundesstaat'] = $result['orders'][$i]['billing_address']['province'];
            }

            $warenkorb['waehrung'] = $result['orders'][$i]['currency'];

            $warenkorb['bestelldatum'] = substr($result['orders'][$i]['created_at'], 0, 10);

            $warenkorb['telefon'] = $result['orders'][$i]['billing_address']['phone'];

            if (!empty($result['orders'][$i]['note'])) {
                $warenkorb['freitext'] = (string)$result['orders'][$i]['note'];
            }
            if (empty($warenkorb['freitext']) && !empty($result['orders'][$i]['note_attributes'][0])
        && (string)$result['orders'][$i]['note_attributes'][0]['value'] != ''
        && (string)$result['orders'][$i]['note_attributes'][0]['value'] != 'true'
        && (string)$result['orders'][$i]['note_attributes'][0]['value'] != 'false') {
                $warenkorb['freitext'] = (string)$result['orders'][$i]['note_attributes'][0]['value'];
            }

            //$warenkorb[telefax] = $result[data][billing][fax]; //TODO
            //$warenkorb[ustid] = $result[data][billing][vatId]; //TODO
            //$warenkorb[anrede]="firma"; //TODO

            //if(!$warenkorb['subshop'])$warenkorb['subshop'] = $result[data][customer][shopId];
            //$warenkorb[abteilung] = $result[data][billing][department];
            //$warenkorb[steuerfrei] = $result[data][taxFree];

            //$warenkorb[unterabteilung] = $result[data][billing][additionalAddressLine1]; //TODO

            if ($result['orders'][$i]['shipping_address']['company'] != '') {
                $warenkorb2['lieferadresse_name'] = $result['orders'][$i]['shipping_address']['company'];
                $warenkorb2['lieferadresse_ansprechpartner'] = $result['orders'][$i]['shipping_address']['name'];
            } else {
                $warenkorb2['lieferadresse_name'] = $result['orders'][$i]['shipping_address']['name'];
                $warenkorb2['lieferadresse_ansprechpartner'] = '';
            }

            if (strlen($result['orders'][$i]['shipping_address']['address2']) < 5) {
                $warenkorb2['lieferadresse_strasse'] = $result['orders'][$i]['shipping_address']['address1'] . ' ' . $result['orders'][$i]['shipping_address']['address2'];
                $warenkorb2['lieferadresse_adresszusatz'] = '';
            } else {
                $warenkorb2['lieferadresse_strasse'] = $result['orders'][$i]['shipping_address']['address1'];
                $warenkorb2['lieferadresse_adresszusatz'] = $result['orders'][$i]['shipping_address']['address2'];
            }
            $warenkorb2['lieferadresse_plz'] = $result['orders'][$i]['shipping_address']['zip'];
            $warenkorb2['lieferadresse_ort'] = $result['orders'][$i]['shipping_address']['city'];
            $warenkorb2['lieferadresse_land'] = $result['orders'][$i]['shipping_address']['country_code'];
            if (!empty($result['orders'][$i]['shipping_address']['province'])) {
                $warenkorb2['lieferadresse_bundesstaat'] = $result['orders'][$i]['shipping_address']['province'];
            }

            //$warenkorb2[lieferadresse_abteilung] = $result[data][shipping][department]; //TODO
            //$warenkorb2[lieferadresse_unterabteilung] = $result[data][shipping][additionalAddressLine1]; //TODO

            $bruttosumme = 0;
            if ($warenkorb2['lieferadresse_name'] != $warenkorb['name'] ||
        $warenkorb2['lieferadresse_ansprechpartner'] != $warenkorb['ansprechpartner'] ||
        $warenkorb2['lieferadresse_strasse'] != $warenkorb['strasse'] ||
        $warenkorb2['lieferadresse_plz'] != $warenkorb['plz'] ||
        $warenkorb2['lieferadresse_ort'] != $warenkorb['ort'] ||
        $warenkorb2['lieferadresse_land'] != $warenkorb['land']
        //|| $warenkorb2['lieferadresse_abteilung']!=$warenkorb['abteilung']

      ) {
                $warenkorb['abweichendelieferadresse'] = '1';
                $warenkorb['lieferadresse_name'] = $warenkorb2['lieferadresse_name'];
                $warenkorb['lieferadresse_ansprechpartner'] = $warenkorb2['lieferadresse_ansprechpartner'];
                $warenkorb['lieferadresse_strasse'] = $warenkorb2['lieferadresse_strasse'];
                $warenkorb['lieferadresse_plz'] = $warenkorb2['lieferadresse_plz'];
                $warenkorb['lieferadresse_ort'] = $warenkorb2['lieferadresse_ort'];
                $warenkorb['lieferadresse_land'] = $warenkorb2['lieferadresse_land'];
                //$warenkorb['lieferadresse_abteilung'] = $warenkorb2['lieferadresse_abteilung'];
                //$warenkorb['lieferadresse_unterabteilung'] = $warenkorb2['lieferadresse_unterabteilung'];
                $warenkorb['lieferadresse_adresszusatz'] = $warenkorb2['lieferadresse_adresszusatz'];
                if (isset($warenkorb2['lieferadresse_bundesstaat'])) {
                    $warenkorb['lieferadresse_bundesstaat'] = $warenkorb2['lieferadresse_bundesstaat'];
                }
            } elseif (empty($warenkorb['telefon']) && !empty($result['orders'][$i]['shipping_address']['phone'])) {
                $warenkorb['telefon'] = $result['orders'][$i]['shipping_address']['phone'];
            }
            unset($warenkorb2);
            $steuermenge = 0;
            $citems = !empty($result['orders'][$i]['line_items']) ? count($result['orders'][$i]['line_items']) : 0;

            $discount_applications_percent = [];
            $discount_applications_absolute = [];
            $lineDiscounts = [];
            $lineDiscountsValues = [];
            $lineDiscountsToItems = [];

            $linePercentageDiscounts = [];
            $linePercentageDiscountsValues = [];
            $linePercentageDiscountsToItems = [];

            $itemsToLineDiscount = [];
            $itemsPercentageToLineDiscount = [];
            $discount_applications_index = -1;
            $discount_applications_absolute_index = -1;
            $discount_applications_absolute_sum = 0;
            $fullprecent_discount = false;
            $absolute_discount = false;
            $sumdiscountApplicationsAmount = 0;
            $shippingDiscountValue = 0;
            $shippingDiscountName = 0;
            $fixedApplicationIndexes = [];
            if (!empty($result['orders'][$i]['discount_applications'])) {
                $rabattartikelid = $this->app->DB->Select("SELECT artikelrabatt FROM shopexport WHERE id='{$this->shopid}' LIMIT 1");
                $rabattartikelnummer = $this->app->DB->Select("SELECT nummer FROM artikel WHERE id='${rabattartikelid}' LIMIT 1");
                if ($taxes_included && !empty($rabattartikelnummer)) {
                    foreach ($result['orders'][$i]['discount_applications'] as $k => $v) {
                        $isFixedAmount = $v['value_type'] === 'fixed_amount';
                        $isLineItem = $v['target_type'] === 'line_item';
                        $isShipping = $v['target_type'] === 'shipping_line';
                        $isAllocationMethodOne = in_array($v['allocation_method'], ['one', 'across']);
                        $hasCode = !empty($v['code']);
                        $hasTitle = !empty($v['title']);
                        $tmpTotalDiscount = abs($warenkorb['rabattnetto']);
                        //$warenkorb['rabattnetto'] = -abs($result['orders'][$i]['total_discounts']);

                        if ($isFixedAmount && $isLineItem && $isAllocationMethodOne && !$hasCode && $hasTitle
              && abs($v['value']) <= $tmpTotalDiscount) {
                            $found = 0;
                            $lineDiscountsToItem = -1;
                            foreach ($result['orders'][$i]['line_items'] as $likey => $livalue) {
                                if (empty($livalue['discount_allocations'])) {
                                    continue;
                                }
                                foreach ($livalue['discount_allocations'] as $discount_allocationVal) {
                                    if (!isset($discount_allocationVal['discount_application_index'])) {
                                        continue;
                                    }
                                    if ($discount_allocationVal['discount_application_index'] == $k) {
                                        $found++;
                                        $lineDiscountsToItem = $likey;
                                        break;
                                    }
                                }
                            }
                            if ($found === 1) {
                                $v['nummer'] = $rabattartikelnummer;
                                $lineDiscounts[] = $k;
                                $lineDiscountsValues[] = $v;
                                $lineDiscountsToItems[] = $lineDiscountsToItem;
                                $warenkorb['rabattnetto'] += abs($v['value']);
                                if ($result['orders'][$i]['total_discounts'] < 0) {
                                    $result['orders'][$i]['total_discounts'] += abs($v['value']);
                                } elseif ($result['orders'][$i]['total_discounts'] > 0) {
                                    $result['orders'][$i]['total_discounts'] -= abs($v['value']);
                                }
                                unset($result['orders'][$i]['discount_applications'][$k]);
                            }
                        } elseif (!$isFixedAmount && $isLineItem) {
                            $found = 0;
                            $lineDiscountsToItem = -1;
                            foreach ($result['orders'][$i]['line_items'] as $likey => $livalue) {
                                if (empty($livalue['discount_allocations'])) {
                                    continue;
                                }
                                foreach ($livalue['discount_allocations'] as $discount_allocationVal) {
                                    if (!isset($discount_allocationVal['discount_application_index'])) {
                                        continue;
                                    }
                                    if ($discount_allocationVal['discount_application_index'] == $k) {
                                        $found++;
                                        $lineDiscountsToItem = $likey;
                                        break;
                                    }
                                }
                            }
                            if ($found === 1) {
                                $v['nummer'] = $rabattartikelnummer;
                                $linePercentageDiscounts[] = $k;
                                $linePercentageDiscountsValues[] = $v;
                                $linePercentageDiscountsToItems[] = $lineDiscountsToItem;
                                unset($result['orders'][$i]['discount_applications'][$k]);
                            }
                        } elseif ($isShipping && $isFixedAmount) {
                            $shippingDiscount = abs($v['value']);
                            if ($shippingDiscount <= $warenkorb['versandkostenbrutto'] && $shippingDiscount <= $tmpTotalDiscount) {
                                $warenkorb['rabattnetto'] += abs($v['value']);
                                $shippingDiscountValue = $shippingDiscount;
                                $shippingDiscountName = !empty($v['description']) ? $v['description'] : $v['title'];
                                unset($result['orders'][$i]['discount_applications'][$k]);
                            }
                        }
                    }
                    $itemsPercentageToLineDiscount = array_flip($linePercentageDiscountsToItems);
                    $itemsToLineDiscount = array_flip($lineDiscountsToItems);
                    $warenkorb['lineDiscountsValues'] = $lineDiscountsValues;
                    $warenkorb['linePercentageDiscountsValues'] = $linePercentageDiscountsValues;
                }

                foreach ($result['orders'][$i]['discount_applications'] as $k => $v) {
                    if (in_array($k, $lineDiscounts)) {
                        continue;
                    }
                    $isFixedAmount = $v['value_type'] === 'fixed_amount';
                    $isLineItem = $v['target_type'] === 'line_item';
                    if ($isFixedAmount) {
                        $sumdiscountApplicationsAmount += $v['value'];
                        $fixedApplicationIndexes[] = $k;
                    }
                    if ($v['value_type'] === 'percentage' && $isLineItem && $v['target_selection'] === 'all') {
                        $discount_applications_index = $k;
                        if ($v['value'] == 100.0) {
                            $fullprecent_discount = true;
                        }
                    } elseif ($isFixedAmount && $isLineItem) {
                        $discount_applications_absolute_index = $k;
                        $absolute_discount = true;
                    }
                }
            }

            foreach ($result['orders'][$i]['discount_codes'] as $discount_code) {
                $warenkorb['gutscheincode'] = $discount_code['code'];
            }

            if ($sumdiscountApplicationsAmount == 0 || abs($result['orders'][$i]['total_discounts']) != $sumdiscountApplicationsAmount) {
                $fixedApplicationIndexes = [];
            }

            for ($ii = 0; $ii < $citems; $ii++) {
                $variante_id = $result['orders'][$i]['line_items'][$ii]['variant_id'];
                $variante_id = $this->app->DB->Select("SELECT af.nummer FROM `artikelnummer_fremdnummern` af INNER JOIN artikel art ON af.artikel = art.id AND art.geloescht <> 1 AND art.nummer <> 'DEL' WHERE af.nummer = '${variante_id}' AND af.nummer <> '' AND af.shopid = '" . $this->shopid . "' AND af.aktiv = 1 LIMIT 1");
                $product_id = $result['orders'][$i]['line_items'][$ii]['product_id'];
                $product_id = $this->app->DB->Select("SELECT af.nummer FROM `artikelnummer_fremdnummern` af INNER JOIN artikel art ON af.artikel = art.id AND art.geloescht <> 1 AND art.nummer <> 'DEL' WHERE af.nummer = '${product_id}' AND af.nummer <> '' AND af.shopid = '" . $this->shopid . "' AND af.aktiv = 1 LIMIT 1");

                $options = [];
                if ($this->eigenschaftenzubeschreibung && !empty($result['orders'][$i]['line_items'][$ii]['properties'])) {
                    foreach ($result['orders'][$i]['line_items'][$ii]['properties'] as $option) {
                        if (!empty($option['name']) && !empty($option['value'])) {
                            $options[] = $option['name'] . ' : ' . $option['value'];
                        }
                    }
                }
                if (!empty($options)) {
                    $hilfsarray['options'] = implode("\n", $options);
                }

                if ($taxes_included) {
                    $taxcol = 'price';
                    if ($warenkorb['rabattnetto'] == 0) {
                        $taxcol = 'rate';
                    }
                    $articleData = ['articleid' => empty($result['orders'][$i]['line_items'][$ii]['sku']) ? ($variante_id == '' ? ($product_id == '' ? $result['orders'][$i]['line_items'][$ii]['variant_id'] : $product_id) : $variante_id) : $result['orders'][$i]['line_items'][$ii]['sku'],
                        'fremdnummer' => $result['orders'][$i]['line_items'][$ii]['variant_id'],
                        'webid' => $result['orders'][$i]['line_items'][$ii]['id'],
                        'name' => $result['orders'][$i]['line_items'][$ii]['name'],
                        'price' => $result['orders'][$i]['line_items'][$ii]['price'],
                        'quantity' => $result['orders'][$i]['line_items'][$ii]['quantity'],
                        'price_netto' => $result['orders'][$i]['line_items'][$ii]['price'],
                    ];
                    if (!empty($options)) {
                        $articleData['options'] = implode("\n", $options);
                    }
                    if (!empty($fixedApplicationIndexes) && !empty($result['orders'][$i]['line_items'][$ii]['discount_allocations'])) {
                        $itemDiscountAllocation = $result['orders'][$i]['line_items'][$ii]['discount_allocations'][0]['discount_application_index'];
                        $discountApplictionByItem = $result['orders'][$i]['discount_applications'][$itemDiscountAllocation]['value'];
                        if (in_array($itemDiscountAllocation, $fixedApplicationIndexes) &&
              $discountApplictionByItem == $result['orders'][$i]['line_items'][$ii]['discount_allocations'][0]['amount'] &&
              $discountApplictionByItem == $result['orders'][$i]['line_items'][$ii]['total_discount']
            ) {
                            $articleData['price'] = $result['orders'][$i]['line_items'][$ii]['price'];
                            $articleData['price_netto'] = $result['orders'][$i]['line_items'][$ii]['price']
                / ($result['orders'][$i]['line_items'][$ii]['tax_lines'][0][$taxcol] != 0 ?
                  (1 + (!empty($result['orders'][$i]['line_items'][$ii]['tax_lines'][0]['rate']) ? $result['orders'][$i]['line_items'][$ii]['tax_lines'][0]['rate'] : 0)) :
                  1);
                        }
                    }

                    if ($discount_applications_absolute_index > -1 &&
            !empty($result['orders'][$i]['line_items'][$ii]['discount_allocations']) &&
            $result['orders'][$i]['line_items'][$ii]['discount_allocations'][0]['discount_application_index'] == $discount_applications_absolute_index) {
                        $ssatz = 100 * (!empty($result['orders'][$i]['line_items'][$ii]['tax_lines'][0]['rate']) ? $result['orders'][$i]['line_items'][$ii]['tax_lines'][0]['rate'] : 0);
                        if (empty($discount_applications_absolute[(string)$ssatz])) {
                            $discount_applications_absolute[(string)$ssatz] = 0;
                        }
                        $discount_applications_absolute_sum += $result['orders'][$i]['line_items'][$ii]['discount_allocations'][0]['amount'];
                        $discount_applications_absolute[(string)$ssatz] += $result['orders'][$i]['line_items'][$ii]['discount_allocations'][0]['amount'];
                    }
                } else {
                    $taxRate = !empty($result['orders'][$i]['line_items'][$ii]['tax_lines'][0]['rate']) ? $result['orders'][$i]['line_items'][$ii]['tax_lines'][0]['rate'] : 0;
                    $articleData = [
                        'articleid' => empty($result['orders'][$i]['line_items'][$ii]['sku']) ? ($variante_id == '' ? ($product_id == '' ? $result['orders'][$i]['line_items'][$ii]['variant_id'] : $product_id) : $variante_id) : $result['orders'][$i]['line_items'][$ii]['sku'],
                        'fremdnummer' => $result['orders'][$i]['line_items'][$ii]['variant_id'],
                        'webid' => $result['orders'][$i]['line_items'][$ii]['id'],
                        'name' => $result['orders'][$i]['line_items'][$ii]['name'],
                        'price_netto' => $result['orders'][$i]['line_items'][$ii]['price'],
                        'quantity' => $result['orders'][$i]['line_items'][$ii]['quantity'],
                        'price' => $result['orders'][$i]['line_items'][$ii]['price'] * (1 + $taxRate),
                    ];
                    if (!empty($options)) {
                        $articleData['options'] = implode("\n", $options);
                    }
                    if (!empty($fixedApplicationIndexes) && !empty($result['orders'][$i]['line_items'][$ii]['discount_allocations'])) {
                        $itemDiscountAllocation = $result['orders'][$i]['line_items'][$ii]['discount_allocations'][0]['discount_application_index'];
                        $discountApplictionByItem = $result['orders'][$i]['discount_applications'][$itemDiscountAllocation]['value'];
                        if (in_array($itemDiscountAllocation, $fixedApplicationIndexes) &&
              $discountApplictionByItem == $result['orders'][$i]['line_items'][$ii]['discount_allocations'][0]['amount'] &&
              $discountApplictionByItem == $result['orders'][$i]['line_items'][$ii]['total_discount']
            ) {
                            $articleData['price'] = $result['orders'][$i]['line_items'][$ii]['price'] * ($result['orders'][$i]['line_items'][$ii]['tax_lines'][0]['price'] != 0 ?
                  (1 + (!empty($result['orders'][$i]['line_items'][$ii]['tax_lines'][0]['rate']) ? $result['orders'][$i]['line_items'][$ii]['tax_lines'][0]['rate'] : 0)) :
                  1);
                            $articleData['price_netto'] = $result['orders'][$i]['line_items'][$ii]['price'];
                        }
                    }
                }

                if ($result['orders'][$i]['line_items'][$ii]['tax_lines'][0]['rate'] > $steuermenge
          && !(isset($result['orders'][$i]['line_items'][$ii]['tax_lines'][0]) && $result['orders'][$i]['line_items'][$ii]['tax_lines'][0]['price'] == 0)) {
                    $steuermenge = $result['orders'][$i]['line_items'][$ii]['tax_lines'][0]['rate'];
                }

                $isdiscountpercent = !empty($result['orders'][$i]['discount_codes'][0]['amount']) &&
          !empty($result['orders'][$i]['discount_codes'][0]['type']) &&
          $result['orders'][$i]['discount_codes'][0]['type'] === 'percentage' &&
          $discount_applications_index > -1;

                if (isset($result['orders'][$i]['line_items'][$ii]['tax_lines'][0])) {
                    if ($result['orders'][$i]['line_items'][$ii]['tax_lines'][0]['price'] == 0 && !$isdiscountpercent
            && $result['orders'][$i]['total_price'] == 0
          ) {
                        $articleData['steuersatz'] = 0;
                        $bruttosumme += $articleData['price'] * $articleData['quantity'];
                        $articleData['steuersatz_orig'] = $result['orders'][$i]['line_items'][$ii]['tax_lines'][0]['rate'] * 100;
                        unset($articleData['price']);
                    } else {
                        $articleData['steuersatz'] = $result['orders'][$i]['line_items'][$ii]['tax_lines'][0]['rate'] * 100;
                        $steuersaetze[(string)round($result['orders'][$i]['line_items'][$ii]['tax_lines'][0]['rate'] * 100, 2)] = true;
                    }
                }

                //$this->archive = true; //Debug sent Orders
                if (!$this->archive) {
                    if ($result['orders'][$i]['line_items'][$ii]['fulfillable_quantity'] < $articleData['quantity'] && $this->fulfilledabziehen) {
                        $abziehen = $articleData['quantity'] - $result['orders'][$i]['line_items'][$ii]['fulfillable_quantity'];
                        if ($articleData['quantity'] > $abziehen) {
                            $articleData['quantity'] -= $abziehen;
                            if (isset($articleData['price'])) {
                                $warenkorb['gesamtsumme'] -= $articleData['price'] * $abziehen;
                            } else {
                                $warenkorb['gesamtsumme'] -= $articleData['price_netto'] * $abziehen;
                            }
                        } else {
                            if (isset($articleData['price'])) {
                                $warenkorb['gesamtsumme'] -= $articleData['price'] * $result['orders'][$i]['line_items'][$ii]['quantity'];
                            } else {
                                $warenkorb['gesamtsumme'] -= $articleData['price_netto'] * $result['orders'][$i]['line_items'][$ii]['quantity'];
                            }
                            unset($articleData);
                        }
                    }
                }
                if (empty($articleData)) {
                    continue;
                }
                if (empty($result['orders'][$i]['line_items'][$ii]['tax_lines'])) {
                    $articleData['steuersatz'] = 0;
                } else {
                    foreach ($result['orders'][$i]['line_items'][$ii]['tax_lines'] as $taxLine) {
                        if ($taxLine['title'] === 'VAT' || $taxLine['rate'] > 0) {
                            $articleData['steuersatz'] = $taxLine['rate'] * 100;
                        }
                    }
                }
                $articlearray[] = $articleData;

                if ($taxes_included) {
                    if (!empty($itemsToLineDiscount) && isset($itemsToLineDiscount[$ii])) {
                        $lineDiscountsValue = $lineDiscountsValues[$itemsToLineDiscount[$ii]];
                        $articlearray[] = [
                            'articleid' => $lineDiscountsValue['nummer'],
                            'name' => $lineDiscountsValue['title'],
                            'price' => -abs($lineDiscountsValue['value']),
                            'quantity' => 1,
                            'steuersatz' => (
                                  !empty($result['orders'][$i]['line_items'][$ii]['tax_lines'][0]['rate']) ?
                                $result['orders'][$i]['line_items'][$ii]['tax_lines'][0]['rate'] * 100 :
                                0
                              ),
                        ];
                    }
                    if (!empty($itemsPercentageToLineDiscount) && isset($itemsPercentageToLineDiscount[$ii])) {
                        $linePercentageDiscountsKey = $linePercentageDiscounts[$itemsPercentageToLineDiscount[$ii]];
                        $linePercentageDiscountsValue = $linePercentageDiscountsValues[$itemsPercentageToLineDiscount[$ii]];
                        if (!empty($result['orders'][$i]['line_items'][$ii]['discount_allocations'])) {
                            foreach ($result['orders'][$i]['line_items'][$ii]['discount_allocations'] as $discountAll) {
                                if (isset($discountAll['discount_application_index']) && $discountAll['discount_application_index'] == $linePercentageDiscountsKey) {
                                    $rabattItemPrice = -(!empty($discountAll['amount_set']['shop_money']['amount']) ? $discountAll['amount_set']['shop_money']['amount'] : $discountAll['amount']);
                                    if (isset($articlearray[count($articlearray) - 1]['price_netto'])
                    && $articlearray[count($articlearray) - 1]['price_netto'] == -$rabattItemPrice) {
                                        unset($articlearray[count($articlearray) - 1]['price_netto']);
                                        $articlearray[count($articlearray) - 1]['price'] = abs($rabattItemPrice);
                                    }
                                    $articlearray[] = [
                                        'articleid' => $linePercentageDiscountsValue['nummer'],
                                        'name' => !empty($linePercentageDiscountsValue['title']) ?
                                            $linePercentageDiscountsValue['title'] :
                                            (!empty($linePercentageDiscountsValue['code']) ? $linePercentageDiscountsValue['code'] : 'Rabatt'),
                                        'price' => $rabattItemPrice,
                                        'quantity' => 1,
                                        'steuersatz' => (
                                              !empty($result['orders'][$i]['line_items'][$ii]['tax_lines'][0]['rate']) ?
                                            $result['orders'][$i]['line_items'][$ii]['tax_lines'][0]['rate'] * 100 :
                                            0
                                          ),
                                    ];
                                    $itemKey = count($articlearray) - 2;
                                    if (!empty($articlearray[$itemKey]['price_netto']) && round(-$rabattItemPrice, 2)
                    === round($articlearray[$itemKey]['price_netto'] * $articlearray[$itemKey]['quantity'], 2)
                  ) {
                                        if (!empty($articlearray[$itemKey]['steuersatz_orig'])) {
                                            $articlearray[$itemKey]['steuersatz'] = $articlearray[$itemKey]['steuersatz_orig'];
                                            unset($articlearray[$itemKey]['steuersatz_orig']);
                                        }
                                        $articlearray[$itemKey]['price'] = $articlearray[$itemKey]['price_netto'];
                                        unset($articlearray[$itemKey]['price_netto']);
                                    }
                                    if (isset($warenkorb['rabattnetto'])) {
                                        $warenkorb['rabattnetto'] -= $rabattItemPrice;
                                        $warenkorb['rabattnetto'] = round($warenkorb['rabattnetto'], 5);
                                    }
                                    if (!empty($warenkorb['rabattnetto']) && abs($warenkorb['rabattnetto']) >= abs($rabattItemPrice)) {
                                        if ($warenkorb['rabattnetto'] < 0) {
                                            $warenkorb['rabattnetto'] += abs($rabattItemPrice);
                                        } elseif ($warenkorb['rabattnetto'] > 0) {
                                            $warenkorb['rabattnetto'] -= abs($rabattItemPrice);
                                        }
                                    }
                                    $lastKey = count($articlearray) - 1;
                                    if ($articlearray[$lastKey]['steuersatz'] == 0
                    && !empty($articlearray[$lastKey]['price'])
                    && !isset($articlearray[$lastKey]['price_netto'])) {
                                        if (!empty($articlearray[$lastKey - 1]['price'])
                      && !isset($articlearray[$lastKey - 1]['price_netto'])
                      && round($articlearray[$lastKey - 1]['price'] * $articlearray[$lastKey - 1]['quantity'], 2)
                      == round(-$articlearray[$lastKey]['price'], 2)
                    ) {
                                            $articlearray[$lastKey - 1]['price_netto'] = $articlearray[$lastKey - 1]['price'];
                                            $articlearray[$lastKey - 1]['steuersatz'] = 0;
                                            unset($articlearray[$lastKey - 1]['price']);
                                        }
                                        $articlearray[$lastKey]['price_netto'] = $articlearray[$lastKey]['price'];
                                        unset($articlearray[$lastKey]['price']);
                                    }
                                    break;
                                }
                            }
                        }
                    }
                }
                //total_discount
            }

            if (isset($result['orders'][$i]['discount_codes']) && is_array($result['orders'][$i]['discount_codes']) && isset($result['orders'][$i]['discount_codes'][0])) {
                if ($result['orders'][$i]['discount_codes'][0]['amount'] && isset($result['orders'][$i]['discount_codes'][0]['type']) && $result['orders'][$i]['discount_codes'][0]['type'] === 'fixed_amount') {
                    $warenkorb['rabattnetto'] = -$result['orders'][$i]['discount_codes'][0]['amount'];
                    $warenkorb['rabattname'] = !empty($result['orders'][$i]['discount_codes'][0]['code']) ? $result['orders'][$i]['discount_codes'][0]['code'] : 'Rabatt';
                    $bruttosumme += $warenkorb['rabattnetto'];
                    $warenkorb['rabattsteuer'] = 0;
                    if (round($bruttosumme, 4) == 0) {
                        foreach ($articlearray as $k => $v) {
                            if (isset($v['price_netto']) && !isset($v['price'])) {
                                $articlearray[$k]['price'] = $v['price_netto'];
                                unset($articlearray[$k]['price_netto']);
                            }
                        }
                    }
                } elseif ($result['orders'][$i]['discount_codes'][0]['amount'] && isset($result['orders'][$i]['discount_codes'][0]['type']) && $result['orders'][$i]['discount_codes'][0]['type'] === 'percentage') {
                    if ($discount_applications_index > -1) {
                        foreach ($articlearray as $k => $v) {
                            if (
              !empty($result['orders'][$i]['line_items'][$k]['discount_allocations'])
                //&& !empty($v['steuersatz'])
              ) {
                                foreach ($result['orders'][$i]['line_items'][$k]['discount_allocations'] as $kd => $vd) {
                                    if (isset($vd['discount_application_index']) && $vd['discount_application_index'] == $discount_applications_index) {
                                        $v['steuersatz'] = empty($v['steuersatz']) ? '' : (string)$v['steuersatz'];
                                        if ($fullprecent_discount) {
                                            if (empty($discount_applications_percent[$v['steuersatz']])) {
                                                $discount_applications_percent[$v['steuersatz']] = ['price' => 0, 'price_netto' => 0];
                                            }
                                            if (!empty($v['price'])) {
                                                $discount_applications_percent[$v['steuersatz']]['price'] += $v['price'] * $v['quantity'];
                                            }
                                            if (!empty($v['price_netto'])) {
                                                if (!empty($v['price']) && $v['price'] == $v['price_netto'] && !empty($v['steuersatz']) && $v['steuersatz'] != 0) {
                                                    $articlearray[$k]['price_netto'] = $v['price'] / (1 + $v['steuersatz'] / 100);
                                                    $v['price_netto'] = $articlearray[$k]['price_netto'];
                                                }
                                                $discount_applications_percent[$v['steuersatz']]['price_netto'] += $v['price_netto'] * $v['quantity'];
                                            }
                                        } else {
                                            if (empty($discount_applications_percent[$v['steuersatz']])) {
                                                $discount_applications_percent[$v['steuersatz']] = 0;
                                            }
                                            $discount_applications_percent[$v['steuersatz']] += $vd['amount'];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if ($warenkorb['zahlungsweise'] === '') {
                if ($warenkorb['gesamtsumme'] == 0 && (isset($warenkorb['rabattnetto']) || isset($warenkorb['rabattnetto']))) {
                    $warenkorb['zahlungsweise'] = 'Gutschein';
                }
            }

            if (isset($warenkorb['rabattnetto']) && $warenkorb['rabattnetto'] == 0) {
                unset($warenkorb['rabattnetto']);
                if (isset($warenkorb['rabattname'])) {
                    unset($warenkorb['rabattname']);
                }
            }

            if (!empty($warenkorb['rabattsteuer']) && !empty($warenkorb['rabattnetto'] && !empty($result['orders'][$i]['total_discounts'])) &&
        !empty($result['orders'][$i]['taxes_included']) && $warenkorb['rabattsteuer'] > 0 && $warenkorb['rabattnetto'] > 0 &&
        $warenkorb['rabattnetto'] == $result['orders'][$i]['total_discounts']) {
                $warenkorb['rabattbrutto'] = $warenkorb['rabattnetto'];
                unset($warenkorb['rabattnetto']);
            } elseif (!empty($warenkorb['rabattnetto']) && $result['orders'][$i]['tax_inculeded'] && $steuermenge > 0) {
                if ($result['orders'][$i]['total_discount'] + $result['orders'][$i]['subtotal_price'] +
          (!empty($warenkorb['versandkostenbrutto']) ? $warenkorb['versandkostenbrutto'] : 0) ==
          $result['orders'][$i]['total_line_item_price']) {
                    $warenkorb['rabattnetto'] /= (1 + $steuermenge);
                }
            }

            $warenkorb['rabattsteuer'] = $steuermenge * 100;

            if ($warenkorb['rabattnetto'] != 0) {
                $steuersatzzugruppe = [];
                $steuergruppen = [];
                $summegesamt = 0;
                foreach ($articlearray as $key => $value) {
                    $steuerart = 'Befreit';
                    if ($value['steuersatz'] > 0) {
                        if ($value['steuersatz'] <= 11) {
                            $steuerart = 'Ermaessigt';
                        } else {
                            $steuerart = 'Normal';
                        }
                    }
                    $steuersatzzugruppe[$value['steuersatz']] = $steuerart;
                    $positionskosten = (isset($value['price_netto']) ? $value['price_netto'] : $value['price']) * $value['quantity'];
                    if (!isset($steuergruppen[$steuerart])) {
                        $steuergruppen[$steuerart] = $positionskosten;
                    } else {
                        $steuergruppen[$steuerart] += $positionskosten;
                    }
                    $summegesamt += (isset($value['price_netto']) ? $value['price_netto'] : $value['price']) * $value['quantity'];
                }

                if (count($steuergruppen) > 1) {
                    if (!empty($discount_applications_absolute) && count($discount_applications_absolute) === 1 && round(-$discount_applications_absolute_sum, 2) == round($warenkorb['rabattnetto'], 2)) {
                        $absolutekey = array_keys($discount_applications_absolute);
                        $absolutekey = reset($absolutekey);
                        $warenkorb['rabattbrutto'] = $warenkorb['rabattnetto'];
                        $warenkorb['rabattsteuer'] = $absolutekey;
                        unset($warenkorb['rabattnetto']);
                    } elseif (!empty($discount_applications_absolute) && count($discount_applications_absolute) > 1 && round(-$discount_applications_absolute_sum, 2) == round($warenkorb['rabattnetto'], 2)) {
                        $rabattartikelid = $this->app->DB->Select("SELECT artikelrabatt FROM shopexport WHERE id='{$this->shopid}' LIMIT 1");
                        $rabattartikelnummer = $this->app->DB->Select("SELECT nummer FROM artikel WHERE id='${rabattartikelid}' LIMIT 1");
                        foreach ($discount_applications_absolute as $steuersatz => $value) {
                            $key = !empty($steuersatzzugruppe[$steuersatz]) ? $steuersatzzugruppe[$steuersatz] : $steuersatz;
                            if (!is_numeric($key)) {
                                $articlearray[] = [
                                    'articleid' => $rabattartikelnummer,
                                    'name' => 'Rabatt ' . $key,
                                    'quantity' => '1',
                                    'umsatzsteuer' => strtolower($key),
                                    'price' => -abs($value),
                                ];
                            } else {
                                $articlearray[] = [
                                    'articleid' => $rabattartikelnummer,
                                    'name' => 'Rabatt ' . $key,
                                    'quantity' => '1',
                                    'steuersatz' => $key,
                                    'price' => -abs($value),
                                ];
                            }
                            if (is_numeric($steuersatz)) {
                                $articlearray[count($articlearray) - 1]['steuersatz'] = $steuersatz;
                                $articlearray[count($articlearray) - 1]['price_netto'] = $articlearray[count($articlearray) - 1]['price'] / (1 + $steuersatz / 100);
                            }
                        }
                        unset($warenkorb['rabattnetto']);
                        unset($warenkorb['rabattbrutto']);
                        unset($warenkorb['rabattname']);
                    } else {
                        $rabattartikelid = $this->app->DB->Select("SELECT artikelrabatt FROM shopexport WHERE id='{$this->shopid}' LIMIT 1");
                        $rabattartikelnummer = $this->app->DB->Select("SELECT nummer FROM artikel WHERE id='${rabattartikelid}' LIMIT 1");
                        if (!empty($discount_applications_percent)) {
                            if (!$fullprecent_discount) {
                                foreach ($discount_applications_percent as $steuersatz => $value) {
                                    $key = !empty($steuersatzzugruppe[$steuersatz]) ? $steuersatzzugruppe[$steuersatz] : $steuersatz;
                                    if (!is_numeric($key)) {
                                        $articlearray[] = [
                                            'articleid' => $rabattartikelnummer,
                                            'name' => 'Rabatt ' . $key,
                                            'quantity' => '1',
                                            'umsatzsteuer' => strtolower($key),
                                            'price' => -abs($value),
                                        ];
                                    } else {
                                        $articlearray[] = [
                                            'articleid' => $rabattartikelnummer,
                                            'name' => 'Rabatt ' . $key,
                                            'quantity' => '1',
                                            'steuersatz' => $key,
                                            'price' => -abs($value),
                                        ];
                                    }
                                    if (is_numeric($steuersatz)) {
                                        $articlearray[count($articlearray) - 1]['steuersatz'] = $steuersatz;
                                        $articlearray[count($articlearray) - 1]['price_netto'] = $articlearray[count($articlearray) - 1]['price'] / (1 + $steuersatz / 100);
                                    }
                                }
                            } else {
                                foreach ($discount_applications_percent as $steuersatz => $value) {
                                    $pricekey = !empty($value['price_netto']) && $value['price_netto'] != 0 ? 'price_netto' : 'price';
                                    $key = !empty($steuersatzzugruppe[$steuersatz]) ? $steuersatzzugruppe[$steuersatz] : $steuersatz;
                                    if (!is_numeric($key)) {
                                        $articlearray[] = [
                                            'articleid' => $rabattartikelnummer,
                                            'name' => 'Rabatt ' . $key,
                                            'quantity' => '1',
                                            'umsatzsteuer' => strtolower($key),
                                            'steuersatz' => $steuersatz,
                                            $pricekey => -abs($value[$pricekey]),
                                        ];
                                    } else {
                                        $articlearray[] = [
                                            'articleid' => $rabattartikelnummer,
                                            'name' => 'Rabatt ' . $key,
                                            'quantity' => '1',
                                            'steuersatz' => $key,
                                            $pricekey => -abs($value[$pricekey]),
                                        ];
                                    }
                                }
                            }
                        } else {
                            foreach ($steuergruppen as $key => $value) {
                                if ($value > 0) {
                                    $anteil = $value / $summegesamt;
                                    $articlearray[] = [
                                        'articleid' => $rabattartikelnummer,
                                        'name' => 'Rabatt ' . $key,
                                        'quantity' => '1',
                                        'umsatzsteuer' => strtolower($key),
                                        'price' => $warenkorb['rabattnetto'] * $anteil,
                                    ];
                                }
                            }
                        }
                        unset($warenkorb['rabattnetto']);
                        unset($warenkorb['rabattsteuer']);
                    }
                }
            }

            foreach ($articlearray as $key => $value) {
                if (!empty($value['price']) && !empty($value['price_netto']) && !empty($value['steuersatz']) && $value['price'] == $value['price_netto']) {
                    $articlearray[$key]['price_netto'] /= 1 + ($value['steuersatz'] / 100);
                }
            }

            $warenkorb['articlelist'] = $articlearray;

            if ($result['orders'][$i]['total_tax'] == 0 && $this->app->erp->Export($warenkorb['land'])) {
                $warenkorb['steuerfrei'] = 1;
            }

            if (count($steuersaetze) === 1) {
                $steuersaetze = array_keys($steuersaetze);
                $steuersaetze = reset($steuersaetze);
                if ($steuersaetze >= 14) {
                    $warenkorb['umsatzsteuer_normal'] = $steuersaetze;
                } elseif ($steuersaetze <= 10 && $steuersaetze > 0) {
                    $warenkorb['umsatzsteuer_ermassigt'] = $steuersaetze;
                }
            } elseif (count($steuersaetze) === 2) {
                $steuersaetze = array_keys($steuersaetze);
                if ($steuersaetze[0] > 0 && $steuersaetze[1] > 0 && $steuersaetze[0] > $steuersaetze[1]) {
                    $warenkorb['umsatzsteuer_normal'] = $steuersaetze[0];
                    $warenkorb['umsatzsteuer_ermassigt'] = $steuersaetze[1];
                } elseif ($steuersaetze[0] > 0 && $steuersaetze[1] > 0 && $steuersaetze[0] < $steuersaetze[1]) {
                    $warenkorb['umsatzsteuer_normal'] = $steuersaetze[1];
                    $warenkorb['umsatzsteuer_ermassigt'] = $steuersaetze[0];
                }
            }
            if (!empty($steuersaetze) && !empty($warenkorb['lieferadresse_land']) &&
        $result['orders'][$i]['total_tax'] != 0 &&
        $this->app->erp->IstEU($warenkorb['lieferadresse_land']) &&
        !$this->app->erp->Export($warenkorb['lieferadresse_land'])
      ) {
                $warenkorb['ust_befreit'] = 1;
            }
            if (empty($warenkorb['ust_befreit']) && !empty($warenkorb['rabattnetto']) && empty($warenkorb['rabattbrutto'])
        && empty($warenkorb['versandkostennetto']) && $result['orders'][$i]['taxes_included']
      ) {
                if ((!empty($warenkorb['versandkostenbrutto']) ? $warenkorb['versandkostenbrutto'] : 0) +
          $result['orders'][$i]['total_line_items_price'] - $result['orders'][$i]['total_discounts'] == $result['orders'][$i]['total_price']
        ) {
                    $warenkorb['rabattbrutto'] = $warenkorb['rabattnetto'];
                    unset($warenkorb['rabattnetto']);
                }
            }

            if (!empty($steuergruppen) && count($steuergruppen) === 1 && empty($warenkorb['rabattsteuer']) && !empty($warenkorb['rabattbrutto']) && empty($warenkorb['rabattnetto'])) {
                if (!empty($discount_applications_percent) && count($discount_applications_percent) === 1) {
                    foreach ($discount_applications_percent as $tax_percent => $prices) {
                        if (is_numeric($tax_percent)) {
                            $warenkorb['rabattsteuer'] = $tax_percent;
                        }
                        break;
                    }
                } elseif (!empty($discount_applications_absolute) && count($discount_applications_absolute) === 1) {
                    foreach ($discount_applications_absolute as $tax_percent => $prices) {
                        if (is_numeric($tax_percent)) {
                            $warenkorb['rabattsteuer'] = $tax_percent;
                        }
                        break;
                    }
                }
            }

            if ($this->autofullfilltax && empty($warenkorb['versandkostennetto']) && !empty($warenkorb['versandkostenbrutto'])) {
                if (is_array($steuersaetze) && count($steuersaetze) > 1) {
                    $itemtaxes = [];
                    foreach ($articlearray as $value) {
                        if (!empty($value['steuersatz']) && $value['steuersatz']) {
                            if (!empty($value['price_netto'])) {
                                if (empty($itemtaxes[$value['steuersatz']])) {
                                    $itemtaxes[$value['steuersatz']] = 0;
                                }
                                $itemtaxes[$value['steuersatz']] += $value['price_netto'] * $value['quantity'];
                            }
                        }
                    }
                    if (count($itemtaxes) > 1) {
                        arsort($itemtaxes);
                        $itemtaxes = array_keys($itemtaxes);
                        $itemtaxes = reset($itemtaxes);
                        if (!empty($warenkorb['umsatzsteuer_normal']) && $itemtaxes == $warenkorb['umsatzsteuer_normal']) {
                            $warenkorb['portosteuersatz'] = 'normal';
                        } elseif (!empty($warenkorb['umsatzsteuer_ermassigt']) && $itemtaxes == $warenkorb['umsatzsteuer_ermassigt']) {
                            $warenkorb['portosteuersatz'] = 'ermaessigt';
                        }
                    }
                } elseif (is_numeric($steuersaetze)) {
                    if (!empty($warenkorb['umsatzsteuer_normal']) && $steuersaetze == $warenkorb['umsatzsteuer_normal']) {
                        $warenkorb['portosteuersatz'] = 'normal';
                    } elseif (!empty($warenkorb['umsatzsteuer_ermassigt']) && $steuersaetze == $warenkorb['umsatzsteuer_ermassigt']) {
                        $warenkorb['portosteuersatz'] = 'ermaessigt';
                    }
                }
            } elseif ($taxes_included && !$this->autofullfilltax && empty($warenkorb['versandkostennetto']) && !empty($warenkorb['versandkostenbrutto'])) {
                if (!empty($result['orders'][$i]['shipping_lines'][0])
          && !empty($result['orders'][$i]['shipping_lines'][0]['tax_lines'])
          && !empty($result['orders'][$i]['shipping_lines'][0]['tax_lines'][0]['rate'])
        ) {
                    if (!empty($warenkorb['umsatzsteuer_ermassigt']) && round(100 * $result['orders'][$i]['shipping_lines'][0]['tax_lines'][0]['rate'], 2) === round($warenkorb['umsatzsteuer_ermassigt'], 2)) {
                        $warenkorb['portosteuersatz'] = 'ermaessigt';
                    } elseif (!empty($warenkorb['umsatzsteuer_normal']) && round(100 * $result['orders'][$i]['shipping_lines'][0]['tax_lines'][0]['rate'], 2) === round($warenkorb['umsatzsteuer_normal'], 2)) {
                        $warenkorb['portosteuersatz'] = 'normal';
                    }
                }
            }
            if (isset($warenkorb['rabattsteuer']) && $warenkorb['rabattsteuer'] == 0 && !empty($warenkorb['rabattbrutto']) && empty($warenkorb['rabattnetto'])) {
                $warenkorb['rabattnetto'] = $warenkorb['rabattbrutto'];
                unset($warenkorb['rabattbrutto']);
            } elseif (!empty($warenkorb['rabattsteuer']) && $warenkorb['rabattsteuer'] > 0 &&
        !empty($warenkorb['rabattnetto']) && $warenkorb['rabattnetto'] == -$result['orders'][$i]['total_discounts']) {
                $warenkorb['rabattbrutto'] = $warenkorb['rabattnetto'];
                unset($warenkorb['rabattnetto']);
            }

            if (empty($warenkorb['steuerfrei']) && $taxes_included && ($result['orders'][$i]['total_tax'] == 0)) {
                $warenkorb = $this->removeNetIfEqualToGross($warenkorb);
            }

            $warenkorb = $this->changeNetGrossIfDiscount($warenkorb);
            $warenkorb = $this->changeItemPercentDiscountTax($warenkorb, $result['orders'][$i]);

            if ($taxes_included && !empty($warenkorb['versandkostenbrutto']) && $warenkorb['versandkostenbrutto'] != 0
        && empty($warenkorb['versandkostennetto']) && empty($warenkorb['versandkostensteuersatz'])) {
                if ($warenkorb['portosteuersatz'] === 'ermaessigt' && !empty($warenkorb['umsatzsteuer_ermassigt'])
          && $warenkorb['umsatzsteuer_ermassigt'] > 0
          && $warenkorb['umsatzsteuer_ermassigt'] != $this->app->erp->Firmendaten('steuersatz_ermaessigt')) {
                    $warenkorb['versandkostensteuersatz'] = $warenkorb['umsatzsteuer_ermassigt'];
                } elseif (!empty($warenkorb['umsatzsteuer_normal']) && $warenkorb['umsatzsteuer_normal'] > 0
          && (empty($warenkorb['portosteuersatz']) || $warenkorb['portosteuersatz'] === 'normal')
          && $warenkorb['umsatzsteuer_normal'] != $this->app->erp->Firmendaten('steuersatz_normal')) {
                    $warenkorb['versandkostensteuersatz'] = $warenkorb['umsatzsteuer_normal'];
                }
            }

            if (!empty($warenkorb['rabattnetto']) && $result['orders'][$i]['total_tax'] > 0) {
                $warenkorb['rabattbrutto'] = $warenkorb['rabattnetto'];
                unset($warenkorb['rabattnetto']);
            }

            if ($shippingDiscountValue > 0) {
                $shippingDiscountItem = [
                    'articleid' => $rabattartikelnummer,
                    'name' => $shippingDiscountName,
                    'quantity' => '1',
                    'umsatzsteuer' => $warenkorb['portosteuersatz'],
                ];
                if (!empty($warenkorb['versandkostensteuersatz'])) {
                    $shippingDiscountItem['steuersatz'] = $warenkorb['versandkostensteuersatz'];
                }

                foreach ($result['orders'][$i]['shipping_lines'] as $shippingLine) {
                    $shippingDiscountItem['steuersatz'] = 0;
                    if (!empty($shippingLine['tax_lines'])) {
                        foreach ($shippingLine['tax_lines'] as $taxLine) {
                            if ($taxLine['title'] === 'VAT' || $taxLine['rate'] > 0) {
                                $shippingDiscountItem['steuersatz'] = $taxLine['rate'] * 100;
                            }
                        }
                    }
                }
                $shippingDiscountTax = isset($shippingDiscountItem['steuersatz']) ? $shippingDiscountItem['steuersatz'] : 0;
                if ($shippingDiscountTax > 0) {
                    $shippingDiscountItem['price_netto'] = -$shippingDiscountValue / (1 + $shippingDiscountTax / 100);
                } elseif ($warenkorb['portosteuersatz'] === 'ermaessigt' && !empty($warenkorb['umsatzsteuer_ermassigt'])) {
                    $shippingDiscountTax = $warenkorb['umsatzsteuer_ermassigt'];
                    $shippingDiscountItem['steuersatz'] = $shippingDiscountTax;
                    $shippingDiscountItem['price_netto'] = -$shippingDiscountValue / (1 + $shippingDiscountTax / 100);
                } elseif ($warenkorb['portosteuersatz'] === 'normal' && !empty($warenkorb['umsatzsteuer_normal'])) {
                    $shippingDiscountTax = $warenkorb['umsatzsteuer_normal'];
                    $shippingDiscountItem['steuersatz'] = $shippingDiscountTax;
                    $shippingDiscountItem['price_netto'] = -$shippingDiscountValue / (1 + $shippingDiscountTax / 100);
                } else {
                    $shippingDiscountItem['price'] = -$shippingDiscountValue;
                }

                $warenkorb['articlelist'][] = $shippingDiscountItem;
            }

            if (!empty($warenkorb['rabattbrutto']) && !empty($result['orders'][$i]['total_discounts'])
        && !empty($rabattartikelnummer)) {
                $articleListRabatt = 0;
                foreach ($warenkorb['articlelist'] as $subatricle) {
                    if ($subatricle['articleid'] == $rabattartikelnummer && !empty($subatricle['price'])) {
                        $articleListRabatt -= $subatricle['price'];
                    }
                }
                $rabattbrutto = round($result['orders'][$i]['total_discounts'] - $articleListRabatt, 7);
                if ($rabattbrutto == 0) {
                    unset($warenkorb['rabattbrutto']);
                    if (!empty($warenkorb['rabattsteuer'])) {
                        unset($warenkorb['rabattsteuer']);
                    }
                } elseif ($rabattbrutto > 0 && $rabattbrutto < $warenkorb['rabattbrutto']) {
                    $warenkorb['rabattbrutto'] = $rabattbrutto;
                }
            }

            if (!empty($warenkorb['rabattbrutto'])
        && !isset($warenkorb['rabattnetto'])
        && !empty($warenkorb['rabattsteuer'])
        && is_numeric($warenkorb['rabattsteuer'])
      ) {
                $warenkorb['rabattnetto'] = $warenkorb['rabattbrutto'] / (1 + $warenkorb['rabattsteuer'] / 100);
                unset($warenkorb['rabattbrutto']);
            }
            if ($result['orders'][$i]['total_tax'] > 0 && !empty($warenkorb['articlelist'])) {
                foreach ($warenkorb['articlelist'] as $articleKey => $article) {
                    if (!empty($article['steuersatz'])
            && is_numeric($article['steuersatz'])
            && isset($article['price']) && !isset($article['price_netto'])
          ) {
                        $warenkorb['articlelist'][$articleKey]['price_netto'] = $article['price'] / (1 + $article['steuersatz'] / 100);
                    }
                }
            }
            if ($taxes_included && abs($warenkorb['rabattnetto']) > 0 && $warenkorb['rabattsteuer'] == 0) {
                $isItemNetGross = true;
                foreach ($warenkorb['articlelist'] as $article) {
                    if (!isset($article['price']) || !isset($article['price_netto']) || $article['price'] != $article['price_netto']) {
                        $isItemNetGross = false;
                        break;
                    }
                }
                if ($isItemNetGross) {
                    foreach ($warenkorb['articlelist'] as $articleKey => $article) {
                        $warenkorb['articlelist'][$articleKey]['steuersatz'] = 0;
                    }
                }
            }
            unset($steuersaetze);
            unset($articlearray);
            $tmp[$j]['id'] = $warenkorb['auftrag'];

            $tmp[$j]['warenkorb'] = base64_encode(serialize($warenkorb));
            unset($warenkorb);
        }
        if (isset($tmp)) {
            $this->log('Shopify import finished at LOC: 3023', ['tmp' => $tmp]);

            return $tmp;
        }

        date_default_timezone_set($this->timezone);
        $this->log('Shopify import finished at LOC: 3029', ['zeitstempel' => date('Y-m-d H:i:s', strtotime($exitzeitstempel))]);

        return ['zeitstempel' => date('Y-m-d H:i:s', strtotime($exitzeitstempel))];
    }

    protected function log(string $message, array $context = [])
    {
        if (!$this->logging) {
            return;
        }
        $this->logger->debug($message, $context);
    }

    //TODO: Umstellen des Auftrags nach dem er abgeholt wurde auf in inbearbeitung o.ä.

    private function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        if (strpos($date, '.') !== false) {
            $date = strstr($date, '.', true);
        }
        $d = DateTime::createFromFormat($format, $date);
        $valid = ($d && ($d->format($format) === $date));
        if (($valid === false) && ($date != '0000-00-00 00:00:00') && ($date != null) && ($date != '')) {
            if ($this->Debug) {
                $this->DumpVar("ERROR: Falsche Datumsangabe: ${date}");
            }
            $this->error[] = "ERROR: Falsche Datumsangabe: ${date}";
        }

        return $valid;
    }

    public function CheckOldAuftrag($anz = 50)
    {
        $arr = $this->app->DB->SelectArr("SELECT id,extid FROM shopimporter_shopify_auftraege WHERE shop = '" . $this->shopid . "' AND extid <> '' AND  transaction_id = '' AND getestet = 0 ORDER BY id LIMIT ${anz}");
        if (!$arr) {
            return;
        }
        foreach ($arr as $v) {
            $i = 0;
            $checkid = $v['id'];
            $_tmp['nummer'] = $v['extid'];
            $result = $this->adapter->call('orders.json?status=any&ids=' . $_tmp['nummer']);

            if (!isset($result['data']['orders']) || !isset($result['data']['orders'][$i]['id']) || !$result['data']['orders'][$i]['id']) {
            } else {
                $transactionsarr = $this->adapter->call('orders/' . $result['data']['orders'][$i]['id'] . '/transactions.json');
                if (isset($transactionsarr['data']['transactions']) && isset($transactionsarr['data']['transactions'][0])) {
                    $transactionsarr = $transactionsarr['data']['transactions'][0];
                    if (isset($transactionsarr['data']['authorization']) && (string)$transactionsarr['data']['authorization'] !== '') {
                        if (isset($transactionsarr['data']['receipt']) && isset($transactionsarr['data']['receipt']['transaction_id']) && (string)$transactionsarr['data']['receipt']['transaction_id'] !== '') {
                            $transaction_id = $this->app->DB->real_escape_string($transactionsarr['data']['receipt']['transaction_id']);
                        } else {
                            $transaction_id = $this->app->DB->real_escape_string($transactionsarr['data']['authorization']);
                        }
                        $this->app->DB->Update("UPDATE shopimporter_shopify_auftraege SET transaction_id = '${transaction_id}',zahlungsweise ='" . $this->app->DB->real_escape_string((string)$result['orders'][$i]['gateway']) . "' WHERE id = '${checkid}' LIMIT 1");
                    }
                }
            }
            $this->app->DB->Update("UPDATE shopimporter_shopify_auftraege SET getestet = 1 WHERE id = '${checkid}' LIMIT 1");
        }
    }

    /**
     * @param array $warenkorb
     *
     * @return array
     */
    protected function removeNetIfEqualToGross($warenkorb)
    {
        if (!empty($warenkorb['rabattnetto']) || !empty($warenkorb['rabattbrutto']) || empty($warenkorb['articlelist'])) {
            return $warenkorb;
        }
        if (!empty($warenkorb['versandkostenbrutto']) && !empty($warenkorb['versandkostennetto']) &&
      $warenkorb['versandkostenbrutto'] != $warenkorb['versandkostennetto']) {
            return $warenkorb;
        }

        foreach ($warenkorb['articlelist'] as $article) {
            if (empty($article['price'])) {
                return $warenkorb;
            }

            if (!empty($article['price_netto'])) {
                if (!empty($article['price']) && $article['price'] != $article['price_netto']) {
                    return $warenkorb;
                }
            }
        }

        foreach ($warenkorb['articlelist'] as $articleKey => $article) {
            unset($warenkorb['articlelist']['price_netto']);
        }

        if (!empty($warenkorb['versandkostenbrutto']) && !empty($warenkorb['versandkostennetto'])) {
            unset($warenkorb['versandkostennetto']);
        }

        return $warenkorb;
    }

    /**
     * @param array $warenkorb
     *
     * @return array
     */
    protected function changeNetGrossIfDiscount($warenkorb)
    {
        if (empty($warenkorb['articlelist'])) {
            return $warenkorb;
        }
        foreach ($warenkorb['articlelist'] as $key => $article) {
            if ($article['steuersatz'] === 0 && !empty($article['steuersatz_orig'])) {
                $warenkorb['articlelist'][$key]['steuersatz'] = $article['steuersatz_orig'];
            }
        }
        if ($warenkorb['gesamtsumme'] != 0 || empty($warenkorb['rabattnetto'])) {
            return $warenkorb;
        }
        if (!empty($warenkorb['land']) && ($this->app->erp->Export($warenkorb['land']) || $this->app->erp->IstEU($warenkorb['land']))) {
            return $warenkorb;
        }
        $steuersatz = false;
        $price = 0;
        foreach ($warenkorb['articlelist'] as $articleKey => $article) {
            if (empty($article['price']) || !empty($article['price_netto'])) {
                return $warenkorb;
            }
            $tmpSteuersatz = !empty($article['steuersatz_orig']) ? $article['steuersatz_orig'] : $article['steuersatz'];
            if (empty($tmpSteuersatz)) {
                return $warenkorb;
            }
            if ($steuersatz === false) {
                $steuersatz = $tmpSteuersatz;
            } elseif ($steuersatz != $tmpSteuersatz) {
                return $warenkorb;
            }
            $price += $article['price'] * $article['quantity'];
        }
        if (round(-$price, 2) === round($warenkorb['rabattnetto'], 2)) {
            $warenkorb['rabattbrutto'] = $warenkorb['rabattnetto'];
            unset($warenkorb['rabattnetto']);
            $warenkorb['rabattsteuer'] = $steuersatz;
            foreach ($warenkorb['articlelist'] as $articleKey => $article) {
                $warenkorb['articlelist'][$articleKey]['steuersatz'] = $steuersatz;
            }
        }

        return $warenkorb;
    }

    /**
     * @param array $warenkorb
     * @param array $order
     *
     * @return array
     */
    protected function changeItemPercentDiscountTax($warenkorb, $order)
    {
        if (empty($order['taxes_included']) || empty($warenkorb['rabattnetto'])) {
            return $warenkorb;
        }
        if (isset($warenkorb['rabattsteuer']) && $warenkorb['rabattsteuer'] > 0) {
            return $warenkorb;
        }
        if (empty($order['discount_applications']) || count($order['discount_applications']) > 0) {
            return $warenkorb;
        }
        if ($order['discount_applications'][0]['value_type'] !== 'percentage'
      || $order['discount_applications'][0]['target_type'] !== 'line_item'
      || $order['discount_applications'][0]['value'] != 100.0
    ) {
            return $warenkorb;
        }
        $shopexport = $this->app->DB->SelectRow(
            sprintf(
          'SELECT art.umsatzsteuer, s.artikelrabattsteuer FROM shopexport AS s 
        INNER JOIN artikel AS art ON s.artikelrabatt = art.id
        WHERE s.id = %d',
          $this->shopid
      )
        );
        if (empty($shopexport) || $shopexport['artikelrabattsteuer'] === 0.0
      || $shopexport['artikelrabattsteuer'] === 'befreit') {
            return $warenkorb;
        }
        $tax = false;
        foreach ($warenkorb['articlelist'] as $article) {
            if (!isset($article['steuersatz'])) {
                return $warenkorb;
            }
            if ($tax === false) {
                $tax = $article['steuersatz'];
            } elseif ($tax !== $article['steuersatz']) {
                return $warenkorb;
            }
        }

        if ($tax === false) {
            return $warenkorb;
        }

        if (!empty($warenkorb['umsatzsteuer_ermassigt'])
      && $tax == $warenkorb['umsatzsteuer_ermassigt'] &&
      $shopexport['artikelrabattsteuer'] === 'ermaessigt'
    ) {
            $warenkorb['rabattbrutto'] = $warenkorb['rabattnetto'];
            unset($warenkorb['rabattnetto']);
            $warenkorb['rabattsteuer'] = $tax;

            return $warenkorb;
        }
        if (!empty($warenkorb['umsatzsteuer_normal'])
      && $tax == $warenkorb['umsatzsteuer_normal'] &&
      $shopexport['artikelrabattsteuer'] === 'normal'
    ) {
            $warenkorb['rabattbrutto'] = $warenkorb['rabattnetto'];
            unset($warenkorb['rabattnetto']);
            $warenkorb['rabattsteuer'] = $tax;

            return $warenkorb;
        }

        return $warenkorb;
    }

    public function ImportDeleteAuftrag()
    {
        $tmp = $this->CatchRemoteCommand('data');
        $externalOrderId = $tmp['auftrag'];

        $this->shopify->metafields()->createFor(
            'order',
            'xentral',
            'sync_status',
            'integer',
            1,
            $externalOrderId
        );

        return 'ok';
    }

    public function ImportStorniereAuftrag()
    {
        $tmp = $this->CatchRemoteCommand('data');
        $externalOrderId = $tmp['auftrag'];

        if (empty($externalOrderId)) {
            return 'OK';
        }

        $this->shopify->orders()->cancel($externalOrderId);

        $this->shopify->orders()->refund(
            $this->shopify->orders()->get($externalOrderId)
        );

        $this->shopify->metafields()->createFor(
            'order',
            'xentral',
            'sync_status',
            'integer',
            3,
            $externalOrderId
        );

        return 'OK';
    }

    public function ImportUpdateAuftrag()
    {
        $tmp = $this->CatchRemoteCommand('data');
        $externalOrderId = $tmp['auftrag'];

        if (empty($externalOrderId)) {
            return new ShopConnectorOrderStatusUpdateResponse(false, 'Order number empty');
        }

        //TODO
        $trackingUrls = [];
        if (!empty($tmp['trackinglinkraw'])) {
            $trackingUrls = [$tmp['trackinglinkraw']];
        } elseif (!empty($tmp['trackinglink'])) {
            $trackingUrls = [$tmp['trackinglink']];
        }

        $this->shopify->fulfillments()->create(
            $externalOrderId,
            $this->location,
            $tmp['tracking'],
            $trackingUrls,
            $this->notifyCustomer
        );

        $this->shopify->metafields()->createFor(
            'order',
            'xentral',
            'sync_status',
            'integer',
            2,
            $externalOrderId
        );

        return 'OK';
    }

    public function ImportAuth()
    {
        if ($this->ShopifyToken === ''
      && ($this->ShopifyURL === '' || $this->ShopifyAPIKey === '' || $this->ShopifyPassword === '')) {
            return 'Bitte API-Daten ausfüllen';
        }

        try {
            if ($this->CatchRemoteCommand('data') === 'info') {
                $this->fillLocationsIntoSettings();
            } else {
                $this->shopify->products()->count();
            }

            return 'success';
        } catch (\GuzzleHttp\Exception\TransferException $e) {
            $this->logger->debug($e->getMessage());

            return 'failed ' . $e->getMessage();
        }
    }

    private function fillLocationsIntoSettings()
    {
        $locations = $this->shopify->locations()->list();

        $locationsForSettings = [];
        foreach ($locations as $location) {
            if (!$location->isActive()) {
                continue;
            }
            $locationsForSettings[$location->getId()] = $location->getName();
        }
        $settings = $this->getSettings();
        $settings['felder']['locations'] = base64_encode(json_encode($locationsForSettings));
        if (empty($settings['felder']['location'])) {
            $settings['felder']['location'] = array_keys($locationsForSettings)[0];
        }

        $this->saveSettings($settings);
    }

    private function getSettings(): array
    {
        $settings = $this->app->DB->Select("SELECT einstellungen_json FROM shopexport WHERE id = '{$this->shopid}' LIMIT 1");
        if (!empty($settings)) {
            $settings = json_decode($settings, true);
        } else {
            $settings = [];
        }

        return $settings;
    }

    private function saveSettings(array $settings): void
    {
        $preparedSettings = $this->app->DB->real_escape_string(json_encode($settings));
        $this->app->DB->UPDATE("UPDATE `shopexport` SET `einstellungen_json` = '{$preparedSettings}' WHERE `id` = '{$this->shopid}'");
    }

    public function ShopifyLog($nachricht, $dump = '')
    {
        if ($this->logging) {
            $this->app->erp->LogFile($nachricht, print_r($dump, true));
        }
    }

    public function SetError($scope, $error)
    {
        $scope = $this->app->DB->real_escpa_string($scope);
        $error = $this->app->DB->real_escpa_string($error);
        $bearbeiter = $this->app->erp->GetBearbeiter(true);
        $this->app->DB->Insert('INSERT INTO shopexport_log (shopid, typ,parameter1,parameter2,bearbeiter,zeitstempel)
        VALUES (' . $this->shopid . ",'fehler','Fehlendes API-Recht: ${scope}','${error}','${bearbeiter}',now())");
    }

    public function SendResponseAES($value)
    {
        $z = $this->app->Conf->ImportKey;//"12345678912345678912345678912345"; // 256-bit key

        $aes = new AES('ecb');
        $aes->setKey($z);

        return base64_encode($aes->encrypt(serialize($value)));
    }

    /**
     * @param array $shopArr
     * @param array $postData
     *
     * @return array
     */
    public function updateShopexportArr($shopArr, $postData)
    {
        $shopArr['anzgleichzeitig'] = 50;
        $shopArr['datumvon'] = date('Y-m-d H:i:s');
        $shopArr['demomodus'] = 0;

        return $shopArr;
    }

    /**
     * @return JsonResponse|null
     */
    public function AuthByAssistent()
    {
        $shopifyURL = $this->app->Secure->GetPOST('ShopifyURL');
        $this->shopifytracking = !empty($this->app->Secure->GetPOST('shopifytracking'));
        if (empty($shopifyURL)) {
            return new JsonResponse(['error' => 'Bitte die URL des Shops angeben.'], JsonResponse::HTTP_BAD_REQUEST);
        }
        $shopifyURL = trim($shopifyURL);
        if (stripos($shopifyURL, 'http') === false) {
            $shopifyURL = 'https://' . $shopifyURL;
        }

        $shopifyAPIKey = $this->app->Secure->GetPOST('ShopifyAPIKey');
        $shopifyPassword = $this->app->Secure->GetPOST('ShopifyPassword');
        $shopifyToken = $this->app->Secure->GetPOST('ShopifyToken');
        $step = (int)$this->app->Secure->GetPOST('step');
        $this->ShopifyToken = $shopifyToken;
        $this->ShopifyAPIKey = $shopifyAPIKey;
        $this->ShopifyPassword = $shopifyPassword;
        if (empty($shopifyToken) && (empty($shopifyAPIKey) || empty($shopifyPassword))) {
            return new JsonResponse(['error' => 'Bitte ApiKey/Passwort oder Token angeben'], JsonResponse::HTTP_BAD_REQUEST);
        }
        if (empty($shopifyToken)) {
            if (strpos($shopifyURL, 'https://') !== false) {
                $shopifyURL = 'https://' . $shopifyAPIKey . ':' . $shopifyPassword . '@' . str_replace('https://', '', $shopifyURL);
            } else {
                $shopifyURL = 'http://' . $shopifyAPIKey . ':' . $shopifyPassword . '@' . str_replace('http://', '', $shopifyURL);
            }
        }
        $this->ShopifyURL = $shopifyURL;
        if ($step < 1) {
            $adapter = new Shopimporter_Shopify_Adapter($this->app, $shopifyURL, 0, $shopifyToken);
            $this->setAdapter($adapter);
            $this->data = true;

            $result = $this->adapter->call('locations.json', '', '', false);
            if (strpos($result['data'], '<html><body>') === 0) {
                return new JsonResponse(['error' => 'bitte prüfen Sie die Zugangsdaten'], JsonResponse::HTTP_BAD_REQUEST);
            }

            $result['data'] = @json_decode($result['data'], true);

            if (!$result['data']) {
                return new JsonResponse(['error' => 'bitte prüfen Sie die Zugangsdaten'], JsonResponse::HTTP_BAD_REQUEST);
            }
            if (empty($result['data']['locations'])) {
                return null;
            }
            $locations = ['' => ''];
            $this->location = '';
            $activeCount = 0;
            foreach ($result['data']['locations'] as $location) {
                if ($location['active']) {
                    $activeCount++;
                    $locations[$location['id']] = $location['name'];
                    if ($this->location === '') {
                        $this->location = $location['id'];
                    }
                }
            }
            $this->locations = base64_encode(json_encode($locations));
            if ($activeCount > 1) {
                $this->location = '';
            }

            return $this->getStorageSelectionPage(null, $locations);
        }

        return null;
    }

    /**
     * @param array|null $requiredForSubmit
     * @param mixed      $locations
     *
     * @return JsonResponse
     */
    public function getStorageSelectionPage($requiredForSubmit = null, $locations = [])
    {
        if ($requiredForSubmit === null) {
            $requiredForSubmit = $this->app->Secure->POST;
            $requiredForSubmit['step'] = 1;
            $requiredForSubmit = $this->updatePostDataForAssistent($requiredForSubmit);
        }

        $page = [
            'type' => 'form',
            'submitType' => 'submit',
            'icon' => 'password-icon',
            'headline' => 'Shopify',
            'subHeadline' => 'Bitte wähle den Shop aus?',
            'submitUrl' => 'index.php?module=onlineshops&action=create&cmd=saveassistent&shopmodule=shopimporter_shopify',
            'form' => [
            ],
            'ctaButtons' => [
                [
                    'title' => 'Weiter',
                    'type' => 'submit',
                    'action' => 'submit',
                ],
            ],
        ];

        if (count($locations) > 2) {
            $page['form'][] = [
                'id' => 0,
                'name' => 'exportArticlesGroup',
                'inputs' => [
                    [
                        'label' => 'Shop',
                        'type' => 'select',
                        'name' => 'location',
                        'validation' => false,
                        'options' => $this->getVueLocations(),
                    ],

                ],
            ];
        }
        $page['form'][] = [
            'id' => 1,
            'name' => 'sendTrackingGroup',
            'inputs' => [
                [
                    'label' => 'Tracking E-Mails über Shopify versenden',
                    'type' => 'checkbox',
                    'name' => 'shopifytracking',
                ],
            ],
        ];

        return new JsonResponse(
            [
                'page' => $page,
                'dataRequiredForSubmit' => $requiredForSubmit,
            ]
        );
    }

    /**
     * @param array $postData
     *
     * @return array
     */
    public function updatePostDataForAssistent($postData)
    {
        if (!empty($this->ShopifyURL)) {
            $url = $this->ShopifyURL;
            if (!empty($this->ShopifyAPIKey) && !empty($this->ShopifyPassword) && empty($this->ShopifyToken)) {
                $url = str_replace($this->ShopifyAPIKey . ':' . $this->ShopifyPassword . '@', '', $url);
            }

            $postData['ShopifyURL'] = $url;
        }
        if (!empty($this->shopifytracking)) {
            $postData['shopifytracking'] = 1;
        }
        if (!empty($this->locations)) {
            $postData['locations'] = $this->locations;
        }
        if (!empty($this->location) && empty($postData['location'])) {
            $postData['location'] = $this->location;
        }

        return $postData;
    }

    /**
     * @return array
     */
    public function getVueLocations()
    {
        $ret = [];
        $locations = json_decode(base64_decode($this->locations), true);
        foreach ($locations as $locationId => $location) {
            $ret[] = [
                'value' => $locationId,
                'text' => $location,
            ];
        }

        return $ret;
    }

    /**
     * @return array
     */
    public function getStructureDataForClickByClickSave()
    {
        $ret = [];
        $locations = $this->app->Secure->GetPOST('locations');
        if (!empty($locations)) {
            $ret['locations'] = $locations;
        }
        $location = $this->app->Secure->GetPOST('location');
        if (!empty($location)) {
            $ret['location'] = $location;
        }

        if (!empty($this->app->Secure->GetPOST('shopifytracking'))) {
            $ret['shopifytracking'] = 1;
        }

        return $ret;
    }

    /**
     * @return array[]
     */
    public function getCreateForm()
    {
        return [
            [
                'id' => 0,
                'name' => 'urls',
                'inputs' => [
                    [
                        'label' => 'URL des Shops',
                        'type' => 'text',
                        'name' => 'ShopifyURL',
                        'validation' => true,
                    ],

                ],
            ],
            [
                'id' => 1,
                'name' => 'username',
                'inputs' => [
                    [
                        'label' => 'API-Key aus Shopify',
                        'type' => 'text',
                        'name' => 'ShopifyAPIKey',
                        'validation' => false,
                    ],
                ],
            ],
            [
                'id' => 2,
                'name' => 'password',
                'inputs' => [
                    [
                        'label' => 'Passwort aus Shopify',
                        'type' => 'password',
                        'name' => 'ShopifyPassword',
                        'validation' => false,
                    ],
                ],
            ],
            [
                'id' => 3,
                'name' => 'token',
                'inputs' => [
                    [
                        'label' => 'Token aus Shopify',
                        'type' => 'text',
                        'name' => 'ShopifyToken',
                        'validation' => false,
                    ],
                ],
            ],
        ];
    }

    public function getBoosterHeadline(): string
    {
        return 'Shopify Business Booster App';
    }

    public function getBoosterSubHeadline(): string
    {
        return 'Bitte gehe auf dein Shopify Shop und installiere dort die App Xentral Business Booster App. 
    Dort kann man sich dann mit ein paar Klicks mit Xentral verbinden';
    }

    /**
     * @return string
     */
    public function getYoutubeLink(): string
    {
        return 'https://www.youtube.com/embed/xEBl3h8mIPg';
    }

    /**
     * @param array $warenkorb
     * @param array $order
     *
     * @return array
     */
    protected function changeItemDiscountTax($warenkorb, $order)
    {
        if (empty($order['taxes_included']) || empty($warenkorb['rabattnetto'])) {
            return $warenkorb;
        }
        if (isset($warenkorb['rabattsteuer']) && $warenkorb['rabattsteuer'] > 0) {
            return $warenkorb;
        }
        if (empty($order['discount_applications']) || count($order['discount_applications']) > 0) {
            return $warenkorb;
        }
        if ($order['discount_applications'][0]['value_type'] !== 'percentage'
      || $order['discount_applications'][0]['target_type'] !== 'line_item'
      || $order['discount_applications'][0]['value'] != 100.0
    ) {
            return $warenkorb;
        }
        $shopexport = $this->app->DB->SelectRow(
            sprintf(
          'SELECT art.umsatzsteuer, s.artikelrabattsteuer FROM shopexport AS s 
        INNER JOIN artikel AS art ON s.artikelrabatt = art.id
        WHERE s.id = %d',
          $this->shopid
      )
        );
        if (empty($shopexport) || $shopexport['artikelrabattsteuer'] === 0.0
      || $shopexport['artikelrabattsteuer'] === 'befreit') {
            return $warenkorb;
        }
        $tax = false;
        foreach ($warenkorb['articlelist'] as $article) {
            if (!isset($article['steuersatz'])) {
                return $warenkorb;
            }
            if ($tax === false) {
                $tax = $article['steuersatz'];
            } elseif ($tax !== $article['steuersatz']) {
                return $warenkorb;
            }
        }

        if ($tax === false) {
            return $warenkorb;
        }

        if (!empty($warenkorb['umsatzsteuer_ermassigt'])
      && $tax == $warenkorb['umsatzsteuer_ermassigt'] &&
      $shopexport['artikelrabattsteuer'] === 'ermaessigt'
    ) {
            $warenkorb['rabattbrutto'] = $warenkorb['rabattnetto'];
            unset($warenkorb['rabattnetto']);
            $warenkorb['rabattsteuer'] = $tax;

            return $warenkorb;
        }
        if (!empty($warenkorb['umsatzsteuer_normal'])
      && $tax == $warenkorb['umsatzsteuer_normal'] &&
      $shopexport['artikelrabattsteuer'] === 'normal'
    ) {
            $warenkorb['rabattbrutto'] = $warenkorb['rabattnetto'];
            unset($warenkorb['rabattnetto']);
            $warenkorb['rabattsteuer'] = $tax;

            return $warenkorb;
        }

        return $warenkorb;
    }
}
