<?php

declare(strict_types=1);

namespace Xentral\Modules\FileManagement\Data;

final class FileData
{
    public const OWNER_ARTICLE = 'artikel';
    public const OWNER_TICKET = 'ticket';
    public const OWNER_ADDRESS = 'adressen';
    public const OWNER_DOCSCAN = 'docscan';
    public const OWNER_LIABILITY = 'verbindlichkeit';
    public const OWNER_ORDER = 'auftrag';
    public const OWNER_INVOICE = 'rechnung';
    public const OWNER_DELIVERY_NOTE = 'lieferschein';
    public const OWNER_SUPPLIER_ORDER = 'bestellung';
    public const OWNER_RETURN_ORDER = 'retoure';
    public const OWNER_PRODUCTION = 'produktion';
    public const OWNER_MATRIX_OPTION = 'matrix_option';

    public const DOCUMENT_TYPE_OTHER = 'Sonstige';
    public const DOCUMENT_TYPE_SHOP_IMAGE = 'Shopbild';
    public const DOCUMENT_TYPE_ATTACHMENT = 'Anhang';
    public const DOCUMENT_TYPE_ACCOUNT_STATEMENT = 'Kontoauszug';
    public const DOCUMENT_TYPE_DELIVERY_NOTE = 'lieferschein';

    /** information regarding the `datei` table */
    /** @var int|null id of the corresponding entry in the `datei` table */
    private $fileId;
    /** @var string title (not name) of the file */
    private $title = '';
    /** @var string note towards the file itself*/
    private $fileNote = '';
    /** @var bool marks the file as deleted */
    private $deleted = false;

    /** information regarding the `datei_version` table */
    /** @var int|null id of the corresponding entry in the `datei_version` table */
    private $fileVersionId;
    /** @var string name of the file (actual filename) */
    private $fileName = '';
    /** @var string|null name (not id) of the creator of the file */
    private $creatorName;
    /** @var string description of the file version, e.g. "initial version" */
    private $description = '';
    /** @var int size of the file in bytes */
    private $sizeInBytes = 0;
    /** @var int version of the file */
    private $version = 0;
    /** @var string|null path to the file in the dms directory */
    private $localFilePath;
    /** @var string|null the temporary path the file is supposed to get copied from */
    private $temporaryFilePath;

    /** information regarding the `datei_stichwoerter` table  */
    /** @var string|null type of the file, e.g. Shopbild, Deckblatt, Lieferschein */
    private $documentType;
    /** @var int|null id of the corresponding entry in the `datei_stichwoerter` table */
    private $fileAssociationId;
    /** @var string|null entity owning the file */
    private $owningEntity;
    /** @var int|null id of the entity owning the file */
    private $owningEntityId;
    /** @var int sorting of the file */
    private $sort = 0;

    public static function fromDbState(array $array): FileData
    {
        $fileData = new self();

        if (isset($array['file_id'])) {
            $fileData->setFileId((int)$array['file_id']);
        }
        if (isset($array['title'])) {
            $fileData->setTitle($array['title']);
        }
        if (isset($array['creator_name'])) {
            $fileData->setCreatorName($array['creator_name']);
        }
        if (isset($array['description'])) {
            $fileData->setDescription($array['description']);
        }
        if (isset($array['deleted'])) {
            $fileData->setDeleted((bool)$array['deleted']);
        }
        if (isset($array['file_version_id'])) {
            $fileData->setFileVersionId((int)$array['file_version_id']);
        }
        if (isset($array['size'])) {
            $fileData->setSizeInBytes((int)$array['size']);
        }
        if (isset($array['version'])) {
            $fileData->setVersion((int)$array['version']);
        }
        if (isset($array['local_file_path'])) {
            $fileData->setLocalFilePath($array['local_file_path']);
        }
        if (isset($array['file_name'])) {
            $fileData->setFileName($array['file_name']);
        }
        if (isset($array['file_association_id'])) {
            $fileData->setFileAssociationId((int)$array['file_association_id']);
        }
        if (isset($array['subject'])) {
            $fileData->setDocumentType($array['subject']);
        }
        if (isset($array['object'])) {
            $fileData->setOwningEntity($array['object']);
        }
        if (isset($array['parameter'])) {
            $fileData->setOwningEntityId((int)$array['parameter']);
        }
        if (isset($array['sort'])) {
            $fileData->setSort((int)$array['sort']);
        }


        return $fileData;
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * @param int $version
     *
     * @return FileData
     */
    public function setVersion(int $version): FileData
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return string
     */
    public function getFileNote(): string
    {
        return $this->fileNote;
    }

    /**
     * @param string|null $fileNote
     *
     * @return FileData
     */
    public function setFileNote(?string $fileNote): FileData
    {
        $this->fileNote = $fileNote;

        return $this;
    }

    public function getFileId(): ?int
    {
        return $this->fileId;
    }

    public function setFileId(?int $fileId): FileData
    {
        $this->fileId = $fileId;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(?string $title): FileData
    {
        $this->title = str_replace(['\\\'', '\\"', '\'', '"'], '_', $title);

        return $this;
    }

    public function getCreatorName(): ?string
    {
        return $this->creatorName;
    }

    public function setCreatorName(?string $creatorName): FileData
    {
        $this->creatorName = $creatorName;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(?string $description): FileData
    {
        $this->description = $description;

        return $this;
    }

    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): FileData
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getFileVersionId(): ?int
    {
        return $this->fileVersionId;
    }

    public function setFileVersionId(?int $fileVersionId): FileData
    {
        $this->fileVersionId = $fileVersionId;

        return $this;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function setFileName(?string $fileName): FileData
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getSizeInBytes(): int
    {
        return $this->sizeInBytes;
    }

    public function setSizeInBytes(int $sizeInBytes): FileData
    {
        $this->sizeInBytes = $sizeInBytes;

        return $this;
    }

    public function getLocalFilePath(): ?string
    {
        return $this->localFilePath;
    }

    public function setLocalFilePath(?string $localFilePath): FileData
    {
        $this->localFilePath = $localFilePath;

        return $this;
    }

    public function getTemporaryFilePath(): ?string
    {
        return $this->temporaryFilePath;
    }

    public function setTemporaryFilePath(?string $temporaryFilePath): FileData
    {
        //the implication of a temporary file path is a new file version
        //therefore setting up a temporary file path invalidates the file version
        $this->fileVersionId = null;
        $this->temporaryFilePath = $temporaryFilePath;

        return $this;
    }

    public function getFileAssociationId(): ?int
    {
        return $this->fileAssociationId;
    }

    public function setFileAssociationId($fileAssociationId): FileData
    {
        $this->fileAssociationId = $fileAssociationId;

        return $this;
    }

    public function getDocumentType(): ?string
    {
        return $this->documentType;
    }

    public function setDocumentType(?string $documentType): FileData
    {
        $this->documentType = $documentType;

        return $this;
    }

    public function getOwningEntity(): ?string
    {
        return $this->owningEntity;
    }

    public function setOwningEntity(?string $owningEntity): FileData
    {
        $this->owningEntity = $owningEntity;

        return $this;
    }

    public function getOwningEntityId(): ?int
    {
        return $this->owningEntityId;
    }

    public function setOwningEntityId(?int $owningEntityId): FileData
    {
        $this->owningEntityId = $owningEntityId;

        return $this;
    }

    public function getSort(): int
    {
        return $this->sort;
    }

    public function setSort(int $sort): FileData
    {
        $this->sort = $sort;

        return $this;
    }
}
