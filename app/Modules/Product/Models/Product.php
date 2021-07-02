<?php

declare(strict_types=1);

namespace App\Modules\Product\Models;

use App\Modules\Product\Enums\SerialNumberType;
use App\Modules\Product\Enums\TaxType;
use Illuminate\Database\Eloquent\Model;
use Xentral\Modules\Article\Exception\InvalidArgumentException;

/**
 * Represents a row from the 'artikel' database table.
 *
 * TODO: Consider providing direct access to the data from these tables:
 *       - artikelnummer_fremdnummern
 *       - verkaufspreise
 *       - einkaufspreise
 *       - artikel_texte
 *       - artikelkategorien
 *       - artikel_onlineshops
 *       - artikeleigenschaften
 *       - artikeleigenschaftenwerte
 */
class Product extends Model
{
    public const CREATED_AT = 'logdatei';

    public const UPDATED_AT = 'logdatei';

    protected $table = 'artikel';

    // Default values for the attributes.
    protected $attributes = [
        'produktion' => 0,
        'variante' => 0,
        'herstellernummer' => '',
        'variante_von' => 0,
        'lieferzeitmanuell' => '',
        'lieferzeitmanuell_en' => '',
    ];

    protected $casts = [
        'projekt' => 'int',
        'inaktiv' => 'boolean',
        'ausverkauft' => 'boolean',
        'stueckliste' => 'boolean',
        'juststueckliste' => 'boolean',
        'produktion' => 'boolean',
        'lagerartikel' => 'boolean',
        'variante' => 'boolean',
        'porto' => 'boolean',
        'has_preproduced_partlist' => 'boolean',
        'mindesthaltbarkeitsdatum' => 'boolean',
        'chargenverwaltung' => 'boolean',
        'herstellerlink' => 'string',
        'herstellernummer' => 'string',
        'lieferzeit' => 'string',
        'lieferzeit_en' => 'string',
    ];

    /** @var string */
    private $languageCode;

    public function __construct()
    {
        parent::__construct([]);

        // TODO: Pass this as a parameter once the system supports defining both default
        //       languages (per user/project/system) and fallback languages.
        $this->languageCode = 'de';
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set language to use with the getters that support i18n values.
     *
     * Usage:
     *   $product->setLanguage('en');
     *   $product->getName();
     *   $product->getShortDescription();
     *
     * Or without this setter:
     *   $product->getName('en');
     *   $product->getShortDescription('en');
     */
    public function setLanguage(string $languageCode): self
    {
        if (!in_array($languageCode, ['en', 'de'])) {
            throw new InvalidArgumentException(
                "The language '{$languageCode}' is currently not supported with products."
            );
        }

        $this->languageCode = $languageCode;

        return $this;
    }

    /**
     * Set category id.
     *
     * For legacy reasons the category id will be saved in format n_kat where
     * n is the id found from the artikelkategorien table.
     *
     * The column can be converted from varchar to integer once all other possible
     * legacy values (such as "product", "gebuehr", etc) have been removed from
     * the column.
     */
    public function setCategoryId(int $categoryId): self
    {
        $this->typ = "{$categoryId}_kat";

        return $this;
    }

    public function getCategoryId(): int
    {
        return (int)str_replace('_kat', '', $this->typ);
    }

    public function setSku(string $sku): self
    {
        $this->nummer = $sku;

        return $this;
    }

    /**
     * Get SKU.
     *
     * NOTE that there is also a separate `artikelnummer_fremdnummern` table that
     * provides alternative SKUs. Imagine you give your products the same SKU as
     * your supplier does. This would result in you pushing this product to an
     * online shop with the same SKU. Now if everyone does that potential buyers
     * have an easy game just searching for the SKU and picking the cheapest one.
     *
     * You would basically have to compete with every other seller due to the
     * identical SKU. To circumvent this you could give you product an alternative
     * SKU. One which is not identical to the SKU of the supplier and therefore
     * not identical to the SKU of other sellers. Potential buyers would have a
     * harder time comparing results and therefore be less likely to pick a competitor.
     *
     * Secondly this alternative table also provides additional information for
     * systems which do not work with SKUs directly. Shopify for example requires
     * us to save two different IDs for products (one ID for products with variations)
     * which are linked to the shop.
     *
     * TODO: Figure out how to provide easy access to the alternative SKUs.
     *       Should this method maybe take an extra parameters (such as online shop
     *       id/object) and then choose the correct SKU based on that?
     */
    public function getSku(): string
    {
        return $this->nummer;
    }

    /**
     * Set checksum.
     *
     * This is checksum of shop-relevant product fields for onlineshop-transfer
     * to check if product has been changed.
     *
     * @see \erpAPI::UpdateChecksumShopartikel()
     */
    public function setCheckSum(string $checksum): self
    {
        $this->checksum = $checksum;

        return $this;
    }

    /**
     * Get checksum.
     *
     * This is checksum of shop-relevant product fields for onlineshop-transfer
     * to check if product has been changed.
     *
     * @see \erpAPI::UpdateChecksumShopartikel()
     */
    public function getCheckSum(): string
    {
        return $this->checksum;
    }

    /**
     * Set project id.
     */
    public function setProjectId(int $projectId): self
    {
        $this->projekt = $projectId;

        return $this;
    }

    /**
     * Get project id.
     */
    public function getProjectId(): int
    {
        return $this->projekt;
    }

    /**
     * Mark the product as active/inactive.
     *
     * Whereas the 'geloescht' column marks an product as being deleted "completely",
     * 'inaktiv' is an information relevant for shops. Many shops support some option
     * to have the product in the system, but to make it unavailable for sales.
     */
    public function setIsInactive(bool $isInactive): self
    {
        $this->inaktiv = $isInactive;

        return $this;
    }

    /**
     * Is the product active?
     */
    public function isInactive(): bool
    {
        return $this->inaktiv;
    }

    /**
     * Set whether the product has been sold out.
     */
    public function setIsSoldOut(bool $isSoldOut): self
    {
        $this->ausverkauft = $isSoldOut;

        return $this;
    }

    /**
     * Check whether the product has been sold out.
     */
    public function isSoldOut(): bool
    {
        return $this->ausverkauft;
    }

    /**
     * Set the class of the goods.
     *
     * @deprecated It seems this column is not used anywhere anymore.
     *
     * @param mixed $class
     */
    public function setClassOfGoods($class): self
    {
        $this->warengruppe = $class;

        return $this;
    }

    /**
     * Get the class of goods.
     *
     * @deprecated It seems the `warengruppe` column is not used anywhere anymore.
     */
    public function getClassOfGoods()
    {
        return $this->warengruppe;
    }

    /**
     * Set name of the product in the given language.
     *
     * @param string $languageCode ISO 15897 code (preferred) or ISO 639-1 code.
     */
    public function setName(string $name, string $languageCode): self
    {
        $propertyName = "name_{$languageCode}";

        $this->$propertyName = $name;

        return $this;
    }

    /**
     * Get name of the product in the language defined for the product.
     *
     * @param string|null $languageCode Currently supports only 'de' and 'en'.
     */
    public function getName(?string $languageCode = null): string
    {
        if ($languageCode === null) {
            $languageCode = $this->languageCode;
        }

        $propertyName = "name_{$languageCode}";

        return $this->$propertyName;
    }

    /**
     * Set short description.
     *
     * Meant to be displayed in the general overview of an online store. The idea
     * is to give a short summary to not confront people with walls of text.
     */
    public function setShortDescription(string $shortDescription, string $languageCode): self
    {
        $propertyName = "kurztext_{$languageCode}";

        $this->$propertyName = $shortDescription;

        return $this;
    }

    /**
     * Get Short description (in the desired language).
     */
    public function getShortDescription(?string $languageCode = null): string
    {
        if ($languageCode === null) {
            $languageCode = $this->languageCode;
        }

        $propertyName = "kurztext_{$languageCode}";

        return $this->$propertyName;
    }

    /**
     * Set URL of the manufacturer.
     */
    public function setManufacturerUrl(string $url): self
    {
        $this->herstellerlink = $url;

        return $this;
    }

    /**
     * Get URL of the manufacturer.
     */
    public function getManufacturerUrl(): string
    {
        return $this->herstellerlink;
    }

    /**
     * Set name of manufacturer.
     */
    public function setManufacturerName(string $name): self
    {
        $this->hersteller = $name;

        return $this;
    }

    /**
     * Get name of manufacturer.
     */
    public function getManufacturerName(): string
    {
        return $this->hersteller;
    }

    /**
     * Set the SKU that the original manufacturer uses for the item
     */
    public function setManufacturerSku(string $number): self
    {
        $this->herstellernummer = $number;

        return $this;
    }

    /**
     * Get the SKU that the original manufacturer uses for the item
     */
    public function getManufacturerSku(): string
    {
        return $this->herstellernummer;
    }

    /**
     * Set the default storage bin for this product.
     *
     * Defines the storage bin that should be used as default target
     * when adding stocks.
     */
    public function setDefaultStorageBinId(int $storageBinId): self
    {
        $this->lager_platz = $storageBinId;

        return $this;
    }

    /**
     * Get the default storage bin for this product.
     */
    public function getDefaultStorageBinId(): int
    {
        return $this->lager_platz;
    }

    /**
     * Set the type that defines how serial numbers are used with this product.
     */
    public function setSerialNumberType(SerialNumberType $type): self
    {
        $this->seriennummern = $type->value;

        return $this;
    }

    /**
     * Get the type that defines how serial numbers are used with this product.
     */
    public function getSerialNumberType(): string
    {
        return $this->seriennummern;
    }

    /**
     * Set a non-specific delivery time (i.e. "1-3 days").
     *
     * @param string $languageCode
     */
    public function setDeliveryTimeString(string $string, $languageCode): self
    {
        if ($languageCode === 'de') {
            $propertyName = 'lieferzeitmanuell';
        } else {
            $propertyName = "lieferzeitmanuell_{$languageCode}";
        }

        $this->$propertyName = $string;

        return $this;
    }

    /**
     * Get the non-specific delivery time (i.e. "1-3 days").
     *
     * @return bool|mixed
     */
    public function getDeliveryTimeString(?string $languageCode = null)
    {
        if (!$languageCode) {
            $languageCode = $this->languageCode;
        }

        if ($languageCode === 'de') {
            $propertyName = 'lieferzeitmanuell';
        } else {
            $propertyName = "lieferzeitmanuell_{$languageCode}";
        }

        return $this->$propertyName;
    }

    /**
     * Set weight in kilograms.
     */
    public function setWeight(float $weight): self
    {
        $this->gewicht = $weight;

        return $this;
    }

    /**
     * Get weight in kilograms.
     *
     * This weight can be calculated as a suggestion when printing parcel
     * labels based on the products per order.
     */
    public function getWeight(): float
    {
        return $this->gewicht;
    }

    public function setNetWeight(float $netWeight): self
    {
        $this->nettogewicht = $netWeight;

        return $this;
    }

    public function getNetWeight(): float
    {
        return $this->nettogewicht;
    }

    public function setIsBillOfMaterials(bool $isBillOfMaterials): self
    {
        $this->stueckliste = $isBillOfMaterials;

        return $this;
    }

    public function isBillOfMaterials(): bool
    {
        return $this->stueckliste;
    }

    public function setIsJustInTimeBillOfMaterials(bool $isJustInTimeBillOfMaterials): self
    {
        $this->juststueckliste = $isJustInTimeBillOfMaterials;

        return $this;
    }

    public function isJustInTimeBillOfMaterials(): bool
    {
        return $this->juststueckliste;
    }

    /**
     * Set whether the product is needed in production of an another product.
     *
     * Items to be produced are called "parts list" products. Products that
     * are needed in the production of another product are called "production
     * articles" (because of legacy terminology).
     */
    public function setIsProductionArticle(bool $isProductionArticle): self
    {
        $this->produktion = $isProductionArticle;

        return $this;
    }

    /**
     * Get whether the product is needed in production of an another product.
     *
     * @return bool
     */
    public function isProductionArticle(): bool
    {
        return $this->produktion;
    }

    /**
     * Determine whether the item can be stored and is managed as stock in a warehouse.
     *
     * An automatic warehouse deduction in the logistics processes can
     * be made only for items where this setting is enabled.
     */
    public function setIsStockItem(bool $isStockItem): self
    {
        $this->lagerartikel = $isStockItem;

        return $this;
    }

    /**
     * Get whether the product is stored and is managed as stock in a warehouse.
     */
    public function isStockItem(): bool
    {
        return $this->lagerartikel;
    }

    public function setEAN(string $ean): self
    {
        $this->ean = $ean;

        return $this;
    }

    public function getEan(): string
    {
        return $this->ean;
    }

    public function setIsVariant(bool $isVariant): self
    {
        $this->variante = $isVariant;

        return $this;
    }

    public function isVariant(): bool
    {
        return $this->variante;
    }

    /**
     * Set this product to be a variant of the given product.
     */
    public function setVariantOf(int $productId): self
    {
        $this->variante_von = $productId;

        return $this;
    }

    /**
     * Get id of the product that this one is a variant of.
     */
    public function getVariantOf(): int
    {
        return $this->variante_von;
    }

    /**
     * Set whether an product is a shipping fee (domestic postage, premium shipping, cash on delivery fee, etc.)
     *
     * These products are not queried during the scan in the shipping center and
     * are excluded from statistics.
     */
    public function setIsShippingFee(bool $isShippingFee): self
    {
        $this->porto = $isShippingFee;

        return $this;
    }

    public function isShippingFee(): bool
    {
        return $this->porto;
    }

    /**
     * Set the tax rate type.
     */
    public function setTaxType(TaxType $type): self
    {
        $this->umsatzsteuer = $type->value;

        return $this;
    }

    /**
     * Get the tax type (note: this is not the percentage, but a name for the type).
     */
    public function getTaxType(): string
    {
        return $this->umsatzsteuer;
    }

    public function setHasPreproducedPartsList(bool $hasPreproducedPartsList): self
    {
        $this->has_preproduced_partlist = $hasPreproducedPartsList;

        return $this;
    }

    public function hasPreproducedPartsList(): bool
    {
        return $this->has_preproduced_partlist;
    }

    /**
     * Set that this product is a parts list that consists of another list.
     *
     * For example "Pack of four" could be a
     */
    public function setPreproducedPartsList(int $preproducedPartsList): self
    {
        $this->preproduced_partlist = $preproducedPartsList;

        return $this;
    }

    public function getPreproducedPartsList(): int
    {
        return $this->preproduced_partlist;
    }

    public function setHasBestBeforeDate(bool $hasBestBeforeDate): self
    {
        $this->mindesthaltbarkeitsdatum = $hasBestBeforeDate;

        return $this;
    }

    public function hasBestBeforeDate(): bool
    {
        return $this->mindesthaltbarkeitsdatum;
    }

    public function setUsesBatchManagement(bool $usesBatchManagement): self
    {
        $this->chargenverwaltung = $usesBatchManagement;

        return $this;
    }

    public function usesBatchManagement(): bool
    {
        return $this->chargenverwaltung;
    }

    /**
     * Set description used in the business documents (order, invoice, etc).
     *
     * This is the so called "anabregs" field. Is stands for ANgebot, Auftrag,
     * Bestellung, REchnung, GutSchrift and is the description meant to be
     * displayed in orders, invoices and so on.
     */
    public function setBusinessDocumentDescription(?string $businessDocumentDescription, string $languageCode): self
    {
        if ($languageCode === 'de') {
            $propertyName = 'anabregs_text';
        } else {
            $propertyName = "anabregs_text_{$languageCode}";
        }

        $this->$propertyName = $businessDocumentDescription;

        return $this;
    }

    /**
     * Get description used in the business documents (order, invoice, etc).
     */
    public function getBusinessDocumentDescription(?string $languageCode = null): string
    {
        if ($languageCode === null) {
            $languageCode = $this->languageCode;
        }

        if ($languageCode === 'de') {
            $propertyName = 'anabregs_text';
        } else {
            $propertyName = "anabregs_text_{$languageCode}";
        }

        return $this->$propertyName;
    }

    /**
     * Set description meant to be displayed in online shops.
     */
    public function setShopDescription(string $shopDescription, string $languageCode): self
    {
        $propertyName = "uebersicht_{$languageCode}";

        $this->$propertyName = $shopDescription;

        return $this;
    }

    /**
     * Get description meant to be displayed in an online shop.
     */
    public function getShopDescription(?string $languageCode = null): string
    {
        if ($languageCode === null) {
            $languageCode = $this->languageCode;
        }

        $propertyName = "uebersicht_{$languageCode}";

        return $this->$propertyName;
    }

    public function toArray(): array
    {
        return [
            'category_id' => $this->getCategoryId(),
            'sku' => $this->getSku(),
            'checksum' => $this->getCheckSum(),
            'project_id' => $this->getProjectId(),
            'is_inactive' => $this->isInactive(),
            'is_sold_out' => $this->isSoldOut(),
            'name_en' => $this->getName('en'),
            'name_de' => $this->getName('de'),
            'short_description_en' => $this->getShortDescription('en'),
            'short_description_de' => $this->getShortDescription('de'),
            'shop_description_en' => $this->getShopDescription('en'),
            'shop_description_de' => $this->getShopDescription('de'),
            'ean' => $this->getEan(),
            'is_bill_of_materials' => $this->isBillOfMaterials(),
            'is_just_in_time_bill_of_materials' => $this->isJustInTimeBillOfMaterials(),
            'is_production_article' => $this->isProductionArticle(),
            'is_stock_item' => $this->isStockItem(),
            'is_variant' => $this->isVariant(),
            'variant_of' => $this->getVariantOf(),
            'manufacturer_name' => $this->getManufacturerName(),
            'manufacturer_sku' => $this->getManufacturerSku(),
            'manufacturer_url' => $this->getManufacturerUrl(),
            'is_shipping_fee' => $this->isShippingFee(),
            'serial_number_type' => $this->getSerialNumberType(),
            'tax_type' => $this->getTaxType(),
            'has_preproduced_parts_list' => $this->hasPreproducedPartsList(),
            'preproduced_parts_list' => $this->getPreproducedPartsList(),
            'has_best_before_date' => $this->hasBestBeforeDate(),
            'uses_batch_management' => $this->usesBatchManagement(),
            'delivery_time_en' => $this->getDeliveryTimeString('en'),
            'delivery_time_de' => $this->getDeliveryTimeString('de'),
        ];
    }
}
