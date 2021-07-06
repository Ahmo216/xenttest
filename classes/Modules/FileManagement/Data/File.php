<?php

declare(strict_types=1);

namespace Xentral\Modules\FileManagement\Data;

class File
{
    /** @var string */
    public const OWNER_ARTICLE = 'artikel';

    /** @var string */
    public const OWNER_TICKET = 'Ticket';

    /** @var string */
    public const OWNER_EMAIL = 'E-Mail';

    /** @var string */
    public const OWNER_ADDRESS = 'adressen';

    /** @var string */
    public const OWNER_DOCSCAN = 'docscan';

    /** @var string */
    public const OWNER_LIABILITY = 'verbindlichkeit';

    /** @var string */
    public const OWNER_ORDER = 'auftrag';

    /** @var string */
    public const OWNER_INVOICE = 'rechnung';

    /** @var string */
    public const OWNER_DELIVERY_NOTE = 'lieferschein';

    /** @var string */
    public const OWNER_SUPPLIER_ORDER = 'bestellung';

    /** @var string */
    public const OWNER_RETURN_ORDER = 'retoure';

    /** @var string */
    public const OWNER_PRODUCTION = 'produktion';

    /** @var int */
    private $id;

    /** @var string */
    private $title;

    /** @var string */
    private $description = '';

    /** @var FileVersion */
    private $version;

    /** @var FileAssociation[] */
    private $associations = [];

    public function __construct(
        ?string $title = null,
        ?string $description = null,
        ?FileVersion $version = null,
        array $associations = []
    ) {
        $this->setTitle($title);
        $this->description = $description;
        $this->version = $version;
        foreach ($associations as $association) {
            $this->addAssociation($association);
        }
        $this->id = 0;
    }

    /**
     * @param array $array
     *
     * @return File
     */
    public static function fromDbState(array $array): File
    {
        $fileData = new self();

        $fileData->id = isset($array['file_id']) ? $array['file_id'] : 0;
        $fileData->setTitle(isset($array['title']) ? $array['title'] : null);
        $fileData->setDescription(isset($array['description']) ? $array['description'] : null);

        return $fileData;
    }

    /**
     * @param FileAssociation $association
     *
     * @return void
     */
    public function addAssociation(FileAssociation $association): void
    {
        // Ensure the association is linked to the file
        $this->associations[] = FileAssociation::fromDbState([
            'file_id' => $this->getId(),
            'file_association_id' => $association->getId(),
            'subject' => $association->getDocumentType(),
            'object' => $association->getEntity(),
            'parameter' => $association->getEntityId(),
            'sort' => $association->getSort(),
        ]);
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     *
     * @return void
     */
    public function setTitle(?string $title): void
    {
        if ($title === null) {
            return;
        }

        $this->title = str_replace(['\\\'', '\\"', '\'', '"'], '_', $title);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return FileVersion
     */
    public function getVersionInfo(): ?FileVersion
    {
        return $this->version;
    }

    /**
     * @param FileVersion|null $version
     */
    public function setVersionInfo(?FileVersion $version): void
    {
        $this->version = $version;
    }

    /**
     * @return FileAssociation[]
     */
    public function getAssociations(): array
    {
        return $this->associations;
    }
}
