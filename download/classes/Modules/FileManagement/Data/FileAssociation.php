<?php

declare(strict_types=1);

namespace Xentral\Modules\FileManagement\Data;

class FileAssociation
{
    /** @var int */
    private $id;

    /** @var int $fileId */
    private $fileId;

    /** @var string|null */
    private $documentType;

    /** @var string|null */
    private $entity;

    /** @var int|null */
    private $entityId;

    /** @var int */
    private $sort;

    /**
     * @param int         $fileId
     * @param string|null $documentType
     * @param string|null $entity
     * @param int|null    $entityId
     */
    public function __construct(int $fileId, ?string $documentType = null, ?string $entity = null, ?int $entityId = null)
    {
        $this->id = 0;
        $this->documentType = $documentType;
        $this->entity = $entity;
        $this->entityId = $entityId;
        $this->sort = 0;
        $this->fileId = $fileId;
    }

    /**
     * @param array $data
     *
     * @return static
     */
    public static function fromDbState(array $data): self
    {
        $fileAssociation = new self(isset($data['file_id']) ? $data['file_id'] : 0);
        $fileAssociation->id = isset($data['file_association_id']) ? $data['file_association_id'] : 0;
        $fileAssociation->documentType = isset($data['subject']) ? $data['subject'] : null;
        $fileAssociation->entity = isset($data['object']) ? $data['object'] : null;
        $fileAssociation->entityId = isset($data['parameter']) ? (int)$data['parameter'] : null;
        $fileAssociation->sort = isset($data['sort']) ? (int)$data['sort'] : 0;

        return $fileAssociation;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getDocumentType(): ?string
    {
        return $this->documentType;
    }

    /**
     * @param string $documentType
     */
    public function setDocumentType(string $documentType): void
    {
        $this->documentType = $documentType;
    }

    /**
     * @return string|null
     */
    public function getEntity(): ?string
    {
        return $this->entity;
    }

    /**
     * @param string $entity
     */
    public function setEntity(string $entity): void
    {
        $this->entity = $entity;
    }

    /**
     * @return int|null
     */
    public function getEntityId(): ?int
    {
        return $this->entityId;
    }

    /**
     * @param int $entityId
     */
    public function setEntityId(int $entityId): void
    {
        $this->entityId = $entityId;
    }

    /**
     * @return int
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * @param int $sort
     */
    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }

    /**
     * @return int
     */
    public function getFileId(): int
    {
        return $this->fileId;
    }
}
