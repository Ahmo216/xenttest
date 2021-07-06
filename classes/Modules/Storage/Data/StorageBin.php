<?php

namespace Xentral\Modules\Storage\Data;

use DateTimeImmutable;
use Xentral\Modules\Storage\Exception\InvalidArgumentException;

/**
 * A physical place that can contain stocks (products, components, raw materials, etc).
 *
 * Represents a single row from the `lager_platz` database table.
 *
 * A storage (See \Xentral\Modules\Storage\Data\Storage) generally contains several
 * storage spaces or slots. These are called "storage bins". The storage bins contain
 * the actual stocks. The storage bin therefore describes the location in the
 * warehouse where the goods are or can be stored.
 */
class StorageBin
{
    public const TYPE_SHELF = 'Fachboden';

    public const TYPE_PALLET = 'Palette';

    private const CATEGORY_OPTIONS = ['', 'A', 'B', 'C'];

    /** @var int */
    private $id;

    /** @var int */
    private $storageId;

    /** @var string */
    private $name;

    /** @var string */
    private $comment;

    /** @var int */
    private $projectId;

    /** @var int */
    private $companyId;

    /** @var bool */
    private $isDeleted;

    /** @var string */
    private $timeModified;

    /** @var bool */
    private $isAutomaticShippingDisabled;

    /** @var bool */
    private $isConsumablesStorage;

    /** @var bool */
    private $isBlockedStock;

    /** @var float */
    private $length;

    /** @var float */
    private $width;

    /** @var float */
    private $height;

    /** @var bool */
    private $isPosStorage;

    /** @var int */
    private $addressId;

    /** @var string */
    private $category;

    /** @var string */
    private $shelfType;

    /** @var int */
    private $rowNumber;

    /** @var bool */
    private $isAvailableForProduction;

    /**
     * StorageBin constructor.
     *
     * @param int      $storageId
     * @param string   $name
     * @param string   $comment
     * @param int      $projectId
     * @param int      $companyId
     * @param bool     $isDeleted
     * @param string   $timeModified
     * @param bool     $isAutomaticShippingDisabled
     * @param bool     $isConsumablesStorage
     * @param bool     $isBlockedStock
     * @param float    $length
     * @param float    $width
     * @param float    $height
     * @param bool     $isPosStorage
     * @param int      $addressId
     * @param string   $category
     * @param string   $shelfType
     * @param int      $rowNumber
     * @param bool     $isAvailableForProduction
     * @param int|null $id
     */
    public function __construct(
        int $storageId,
        string $name,
        string $comment,
        int $projectId,
        int $companyId,
        bool $isDeleted,
        string $timeModified,
        bool $isAutomaticShippingDisabled,
        bool $isConsumablesStorage,
        bool $isBlockedStock,
        float $length,
        float $width,
        float $height,
        bool $isPosStorage,
        int $addressId,
        string $category,
        string $shelfType,
        int $rowNumber,
        bool $isAvailableForProduction,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->storageId = $storageId;
        $this->name = $name;
        $this->comment = $comment;
        $this->projectId = $projectId;
        $this->companyId = $companyId;
        $this->isDeleted = $isDeleted;
        $this->timeModified = $timeModified;
        $this->isAutomaticShippingDisabled = $isAutomaticShippingDisabled;
        $this->isConsumablesStorage = $isConsumablesStorage;
        $this->isBlockedStock = $isBlockedStock;
        $this->length = $length;
        $this->width = $width;
        $this->height = $height;
        $this->isPosStorage = $isPosStorage;
        $this->addressId = $addressId;
        $this->category = $category;
        $this->shelfType = $shelfType;
        $this->rowNumber = $rowNumber;
        $this->isAvailableForProduction = $isAvailableForProduction;
    }

    /**
     * Get unique id of the bin.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set unique id of the bin.
     *
     * @param int $id
     *
     * @return StorageBin
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id of the storage (warehouse, storage room, etc) where this bin is located.
     *
     * @return int
     */
    public function getStorageId(): int
    {
        return $this->storageId;
    }

    /**
     * Set id of the storage (warehouse, storage room, etc) where this bin is located.
     *
     * @param int $storageId
     *
     * @return StorageBin
     */
    public function setStorageId(int $storageId): self
    {
        $this->storageId = $storageId;

        return $this;
    }

    /**
     * Get name of the storage bin.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set name for the storage bin.
     *
     * The name must be unique and must not contain spaces.
     *
     * @param string $name
     *
     * @return StorageBin
     */
    public function setName(string $name): self
    {
        if (empty($name)) {
            throw new InvalidArgumentException('The name of a storage bin cannot be empty.');
        }

        if (preg_match('/[^a-z0-9A-Z\-_.]/i', $name)) {
            throw new InvalidArgumentException(
                'The storage bin name is not allowed to contain spaces or special characters.'
            );
        }

        $this->name = $name;

        return $this;
    }

    /**
     * Get comment.
     *
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * Set comment.
     *
     * @param string $comment
     *
     * @return StorageBin
     */
    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get project id.
     *
     * @return int
     */
    public function getProjectId(): int
    {
        return $this->projectId;
    }

    /**
     * Set project id.
     *
     * @param int $projectId
     *
     * @return StorageBin
     */
    public function setProjectId(int $projectId): self
    {
        $this->projectId = $projectId;

        return $this;
    }

    /**
     * Get company id.
     *
     * @return int
     *
     * @deprecated Use setProjectId() and getProjectId() instead.
     */
    public function getCompanyId(): int
    {
        return $this->companyId;
    }

    /**
     * Set company id.
     *
     * @param int $companyId
     *
     * @return StorageBin
     *
     * @deprecated Use setProjectId() and getProjectId() instead.
     */
    public function setCompanyId(int $companyId): self
    {
        $this->companyId = $companyId;

        return $this;
    }

    /**
     * Is the storage bin marked as deleted.
     *
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    /**
     * Set whether the storage bin is deleted.
     *
     * @param bool $isDeleted
     *
     * @return StorageBin
     */
    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * Get time of last modification.
     *
     * @return DateTimeImmutable
     */
    public function getTimeModified(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->timeModified);
    }

    /**
     * Update the modification time.
     */
    public function updateTimeModified(): self
    {
        $this->timeModified = date('Y-m-d H:i:s');

        return $this;
    }

    /**
     * Is this storage bin disabled for automatic shipping?
     *
     * When an order is created and then moved to shipping, the system will try
     * to reserve the products automatically from the storages. If this setting
     * is enabled, this storage bin cannot be used as the stock source for this
     * kind of automatic process.
     *
     * This setting could be used for example at a POS for a storage bin meant
     * for refilling the store shelves when they become empty. In this case the
     * storage bin is not supposed to be used by automatic shipping processed
     * but only manually when this becomes necessary.
     *
     * @return bool
     */
    public function isAutomaticShippingDisabled(): bool
    {
        return $this->isAutomaticShippingDisabled;
    }

    /**
     * Set whether this storage bin is disabled for automatic shipping.
     *
     * @param bool $isAutomaticShippingDisabled
     *
     * @return StorageBin
     */
    public function setIsAutomaticShippingDisabled(bool $isAutomaticShippingDisabled): self
    {
        $this->isAutomaticShippingDisabled = $isAutomaticShippingDisabled;

        return $this;
    }

    /**
     * Is the storage bin is meant for storing consumables?
     *
     * Consumables are tools or objects that are used for production and manufacture,
     * but do not represent a material input meaning they do not have a stock. When
     * an article is added, it will only add an log-entry that there was an booking.
     *
     * You might for example have a stock of a plastic rolls in storage room A.
     * When you need more plastic rolls in storage room B to wrap products in it, you
     * can move the rolls from storage A to the consumables storage of storage B.
     * The rolls can then be used from there without having to reduce stock every
     * time you wrap something into plastic.
     *
     * @return bool
     */
    public function isConsumablesStorage(): bool
    {
        return $this->isConsumablesStorage;
    }

    /**
     * Set whether the storage bin is meant for storing consumables.
     *
     * @param bool $isConsumablesStorage
     *
     * @return StorageBin
     */
    public function setIsConsumablesStorage(bool $isConsumablesStorage): self
    {
        $this->isConsumablesStorage = $isConsumablesStorage;

        return $this;
    }

    /**
     * Get whether the storage bin has been blocked by default as a source for stocks.
     *
     * @return bool
     */
    public function isBlockedStock(): bool
    {
        return $this->isBlockedStock;
    }

    /**
     * Set whether this storage bin is block for use by default.
     *
     * Use cases for this kind of storage bin are:
     *   - To store returned (second hand) products
     *   - The bin is meant to be used by a POS (and therefore blocked for all other use)
     *   - Goods that are broken and will either be repaired or thrown away later
     *
     * This setting can be overridden by either of these properties:
     *   - isPosStorage
     *   - isAvailableForProduction
     *
     * @param bool $isBlockedStock
     *
     * @return StorageBin
     */
    public function setIsBlockedStock(bool $isBlockedStock): self
    {
        $this->isBlockedStock = $isBlockedStock;

        return $this;
    }

    /**
     * Get length of the storage bin (in meters).
     *
     * @return float
     */
    public function getLength(): float
    {
        return $this->length;
    }

    /**
     * Set length of the storage bin (in meters).
     *
     * @param float $length
     *
     * @return StorageBin
     */
    public function setLength(float $length): self
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Get width of the storage bin (in meters).
     *
     * @return float
     */
    public function getWidth(): float
    {
        return $this->width;
    }

    /**
     * Set width of the storage bin (in meters).
     *
     * @param float $width
     *
     * @return StorageBin
     */
    public function setWidth(float $width): self
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get height of the storage bin (in meters).
     *
     * @return float
     */
    public function getHeight(): float
    {
        return $this->height;
    }

    /**
     * Set height of the storage bin (in meters).
     *
     * @param float $height The height in meters.
     *
     * @return StorageBin
     */
    public function setHeight(float $height): self
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Is POS able to use this stock.
     *
     * This setting is usually used in combination with the isBlockedStock property,
     * as the storage bin becomes available for the POS only if it has been explicitly
     * marked as blocked.
     *
     * @return bool
     */
    public function isPosStorage(): bool
    {
        return $this->isPosStorage;
    }

    /**
     * Set whether this stock can be used with POS.
     *
     * This setting is usually used in combination with the isBlockedStock property,
     * as the storage bin becomes available for the POS only if it has been explicitly
     * marked as blocked.
     *
     * @param bool $isPosStorage
     *
     * @return StorageBin
     */
    public function setIsPosStorage(bool $isPosStorage): self
    {
        $this->isPosStorage = $isPosStorage;

        return $this;
    }

    /**
     * Get id of the address that this storage bin has been associated with.
     *
     * @return int
     */
    public function getAddressId(): int
    {
        return $this->addressId;
    }

    /**
     * Associate the storage bin with one of the addresses entered into the system.
     *
     * The address is used only if the storage bin has been dedicated to an
     * individual customer.
     *
     * @param int $addressId
     *
     * @return StorageBin
     */
    public function setAddressId(int $addressId): self
    {
        $this->addressId = $addressId;

        return $this;
    }

    /**
     * Get category.
     *
     * @return string A, B, C or empty
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * Set storage bin category: A, B, C (or empty).
     *
     * This property can be used to freely categorize the storage bins. It could for
     * example be defined that category "A" means "Can be used to store food".
     *
     * @param string $category
     *
     * @return StorageBin
     */
    public function setCategory(string $category): self
    {
        if (!in_array($category, self::CATEGORY_OPTIONS)) {
            throw new InvalidArgumentException(
                "Invalid category value for storage bin: {$category}"
            );
        }

        $this->category = $category;

        return $this;
    }

    /**
     * Get the shelf type (either "Pallet" or "Fachboden").
     *
     * This should be used in combination with the constants, i.e:
     *
     *     if ($storageBin->getShelfType() === $storageBin::TYPE_PALLET) {
     *         // Code here
     *     }
     *
     * TODO: Maybe replace this with two methods isTypeShelf() and isTypePallet()
     *       to make sure that implementation details (the German words) are hidden.
     *
     * @return string
     */
    public function getShelfType(): string
    {
        return $this->shelfType;
    }

    /**
     * Set the type of the shelf.
     *
     * @param string $shelfType Use either StorageBin::TYPE_PALLET or StorageBin::TYPE_SHELF.
     *
     * @return StorageBin
     */
    public function setShelfType(string $shelfType): self
    {
        if ($shelfType !== self::TYPE_PALLET && $shelfType !== self::TYPE_SHELF) {
            throw new InvalidArgumentException("{$shelfType} is not a valid storage bin type");
        }

        $this->shelfType = $shelfType;

        return $this;
    }

    /**
     * Get the row number where the storage bin is located.
     *
     * @return int
     */
    public function getRowNumber(): int
    {
        return $this->rowNumber;
    }

    /**
     * Set the row number where the storage bin is located.
     *
     * @param int $rowNumber
     *
     * @return StorageBin
     */
    public function setRowNumber(int $rowNumber): self
    {
        $this->rowNumber = $rowNumber;

        return $this;
    }

    /**
     * Should the storage bin be used as the default bin for production processes.
     *
     * NOTE: Be cautious about the documentation below. It has not been fully confirmed.
     *
     * By default all available storage bins are available for production processes.
     * However, if this setting is enabled, only the bin having this setting will be
     * used, and the bins that are missing this setting are ignored.
     *
     * Additionally, second-hand storages (isBlockedStock property) will not be
     * used by default in production processes. However this setting can be used to
     * override that property so that the stocks from the second-had storage bin can
     * also be used for production.
     *
     * @return bool
     */
    public function isAvailableForProduction(): bool
    {
        return $this->isAvailableForProduction;
    }

    /**
     * Set whether the storage bin is available for production processes.
     *
     * @param bool $isAvailableForProduction
     *
     * @return StorageBin
     */
    public function setIsAvailableForProduction(bool $isAvailableForProduction): self
    {
        $this->isAvailableForProduction = $isAvailableForProduction;

        return $this;
    }

    /**
     * Create a new Storage bin instance from a database row.
     *
     * @param array $data
     *
     * @return $this
     */
    public static function fromDbState(array $data): self
    {
        return new self(
            (int)$data['lager'],
            (string)$data['kurzbezeichnung'],
            (string)$data['bemerkung'],
            (int)$data['projekt'],
            (int)$data['firma'],
            (bool)$data['geloescht'],
            (string)$data['logdatei'],
            (int)$data['autolagersperre'],
            (int)$data['verbrauchslager'],
            (int)$data['sperrlager'],
            (float)$data['laenge'],
            (float)$data['breite'],
            (float)$data['hoehe'],
            (bool)$data['poslager'],
            (int)$data['adresse'],
            (string)$data['abckategorie'],
            (string)$data['regalart'],
            (int)$data['rownumber'],
            (bool)$data['allowproduction'],
            (int)$data['id']
        );
    }

    /**
     * Convert the object properties into an associative array that uses snake_case keys.
     *
     * Main use case for this is to get content for REST API responses.
     *
     * @return array
     */
    public function toArray(): array
    {
        $properties = get_object_vars($this);

        // Exclude deprecated properties from the return value.
        unset($properties['companyId']);

        // These are meant only for internal use, and should not be exposed to third parties.
        unset($properties['isDeleted']);
        unset($properties['timeModified']);

        // Map camelCase property names to snake_case names (used i.e. in REST API responses).
        $map = [
            'id' => 'id',
            'storageId' => 'storage_id',
            'name' => 'name',
            'comment' => 'comment',
            'projectId' => 'project_id',
            'isAutomaticShippingDisabled' => 'is_automatic_shipping_disabled',
            'isConsumablesStorage' => 'is_consumables_storage',
            'isBlockedStock' => 'is_blocked_stock',
            'length' => 'length',
            'width' => 'width',
            'height' => 'height',
            'isPosStorage' => 'is_pos_storage',
            'addressId' => 'address_id',
            'category' => 'category',
            'shelfType' => 'shelf_type',
            'rowNumber' => 'row_number',
            'isAvailableForProduction' => 'is_available_for_production',
        ];

        $values = [];
        foreach ($properties as $camelCaseName => $value) {
            $values[$map[$camelCaseName]] = $value;
        }

        return $values;
    }

    /**
     * Create a new Storage bin instance from an array of key-value pairs.
     *
     * @param array $data
     *
     * @return StorageBin
     */
    public static function fromArray(array $data): self
    {
        if (empty($data['name'])) {
            throw new InvalidArgumentException('The name of a storage bin cannot be empty.');
        }

        if (preg_match('/[^a-z0-9A-Z\-_.]/i', $data['name'])) {
            throw new InvalidArgumentException(
                'The storage bin name is not allowed to contain spaces or special characters.'
            );
        }

        if (empty($data['storage_id'])) {
            throw new InvalidArgumentException('The storage_id of a bin cannot be empty.');
        }

        $default_values = [
            'storage_id' => 0,
            'name' => '',
            'comment' => '',
            'project_id' => 0,
            'company_id' => 0,
            'is_deleted' => false,
            'is_automatic_shipping_disabled' => false,
            'is_consumables_storage' => false,
            'is_blocked_stock' => false,
            'length' => 0.00,
            'width' => 0.00,
            'height' => 0.00,
            'is_pos_storage' => false,
            'address_id' => 0,
            'category' => '',
            'shelf_type' => '',
            'row_number' => 0,
            'is_available_for_production' => false,
            'time_modified' => date('Y-m-d H:i:s'),
            'id' => null,
        ];

        foreach ($default_values as $key => $value) {
            if (!isset($data[$key])) {
                $data[$key] = $value;
            }
        }

        return new self(
            $data['storage_id'],
            $data['name'],
            $data['comment'],
            $data['project_id'],
            $data['company_id'],
            $data['is_deleted'],
            $data['time_modified'],
            $data['is_automatic_shipping_disabled'],
            $data['is_consumables_storage'],
            $data['is_blocked_stock'],
            $data['length'],
            $data['width'],
            $data['height'],
            $data['is_pos_storage'],
            $data['address_id'],
            $data['category'],
            $data['shelf_type'],
            $data['row_number'],
            $data['is_available_for_production'],
            $data['id']
        );
    }
}
