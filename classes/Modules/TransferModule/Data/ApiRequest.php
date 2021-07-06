<?php

declare(strict_types=1);

namespace Xentral\Modules\TransferModule\Data;

use Exception;
use DateTime;
use DateTimeInterface;

final class ApiRequest
{
    private $id;

    private $apiId;

    private $transferAccountId;

    private $status;

    private $type;

    private $documentKey;

    private $projectId;

    private $extendedDocumentKey;

    private $info;

    private $priority;

    private $createdAt;

    private $isTransferred;

    private $isDeleted;

    private $transferredOn;

    private $transferCount;

    private $fileName;

    /**
     * ApiRequest constructor.
     *
     * @param int|null               $id
     * @param int                    $transferAccountId
     * @param int                    $apiId
     * @param string                 $status
     * @param string                 $type
     * @param string                 $documentKey
     * @param int                    $projectId
     * @param string                 $extendedDocumentKey
     * @param DateTimeInterface|null $createdAt
     * @param string                 $fileName
     * @param DateTimeInterface|null $transferredOn
     * @param int                    $priority
     * @param bool                   $isTransferred
     * @param int                    $transerCounts
     * @param string                 $info
     * @param bool                   $isDeleted
     */
    public function __construct(
        ?int $id,
        int $transferAccountId,
        int $apiId,
        string $status,
        string $type,
        string $documentKey,
        int $projectId = 0,
        string $extendedDocumentKey = '',
        ?DateTimeInterface $createdAt = null,
        string $fileName = '',
        ?DateTimeInterface $transferredOn = null,
        int $priority = 0,
        bool $isTransferred = false,
        int $transerCounts = 0,
        string $info = '',
        bool $isDeleted = false
    ) {
        $this->id = $id;
        $this->transferAccountId = $transferAccountId;
        $this->apiId = $apiId;
        $this->status = $status;
        $this->type = $type;
        $this->documentKey = $documentKey;
        $this->projectId = $projectId;
        $this->extendedDocumentKey = $extendedDocumentKey;
        $this->createdAt = $createdAt;
        $this->fileName = $fileName;
        $this->transferredOn = $transferredOn;
        $this->priority = $priority;
        $this->isTransferred = $isTransferred;
        $this->transferCount = $transerCounts;
        $this->isDeleted = $isDeleted;
        $this->info = $info;
    }

    /**
     * @param array $dbState
     *
     * @return static
     */
    public static function fromDbState(array $dbState): self
    {
        $createdAt = null;
        $transferedAt = null;
        if ($dbState['zeitstempel'] !== null && $dbState['zeitstempel'] !== '0000-00-00 00:00:00') {
            try {
                $createdAt = new DateTime($dbState['zeitstempel']);
            } catch (Exception $e) {
            }
        }
        if ($dbState['uebertragen_am'] !== null && $dbState['uebertragen_am'] !== '0000-00-00 00:00:00') {
            try {
                $transferedAt = new DateTime($dbState['uebertragen_am']);
            } catch (Exception $e) {
            }
        }

        return new self(
            empty($dbState['id']) ? null : (int)$dbState['id'],
            (int)$dbState['uebertragung_account'],
            (int)$dbState['api'],
            (string)$dbState['status'],
            (string)$dbState['typ'],
            (string)$dbState['parameter1'],
            (int)$dbState['projekt'],
            (string)$dbState['parameter2'],
            $createdAt,
            (string)$dbState['datei'],
            $transferedAt,
            (int)$dbState['prio'],
            !empty($dbState['uebertragen']),
            (int)$dbState['anzahl_uebertragen'],
            (string)$dbState['anzeige'],
            !empty($dbState['geloescht'])
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id'                   => $this->getId(),
            'api'                  => $this->getApiId(),
            'uebertragung_account' => $this->getTransferAccountId(),
            'status'               => $this->getStatus(),
            'prio'                 => $this->getPriority(),
            'zeitstempel'          => $this->getCreatedAt() === null ? null : $this->getCreatedAt()->format(
                'Y-m-d H:i:s'
            ),
            'typ'                  => $this->getType(),
            'parameter1'           => $this->getDocumentKey(),
            'projekt'              => $this->getProjectId(),
            'parameter1int'        => $this->getParameter1Int(),
            'parameter2'           => $this->getExtendedDocumentKey(),
            'anzeige'              => $this->getInfo(),
            'uebertragen'          => (int)$this->isTransferred(),
            'geloescht'            => (int)$this->isDeleted(),
            'datei'                => $this->getFileName(),
            'uebertragen_am'       => $this->getTransferredOn() === null ? null : $this->getTransferredOn()->format(
                'Y-m-d H:i:s'
            ),
            'anzahl_uebertragen'   => $this->getTransferCount(),
        ];
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getApiId(): int
    {
        return $this->apiId;
    }

    /**
     * @param int $apiId
     */
    public function setApiId(int $apiId): void
    {
        $this->apiId = $apiId;
    }

    /**
     * @return int
     */
    public function getTransferAccountId(): int
    {
        return $this->transferAccountId;
    }

    /**
     * @param int $transferAccountId
     */
    public function setTransferAccountId(int $transferAccountId): void
    {
        $this->transferAccountId = $transferAccountId;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getDocumentKey(): string
    {
        return $this->documentKey;
    }

    /**
     * @param string $documentKey
     */
    public function setDocumentKey(string $documentKey): void
    {
        $this->documentKey = $documentKey;
    }

    /**
     * @return int
     */
    public function getParameter1Int(): int
    {
        return (int)$this->documentKey;
    }

    /**
     * @param int $parameter1Int
     */
    public function setParameter1Int(int $parameter1Int): void
    {
        $this->documentKey = (string)$parameter1Int;
    }

    /**
     * @return int
     */
    public function getProjectId(): int
    {
        return $this->projectId;
    }

    /**
     * @param int $projectId
     */
    public function setProjectId(int $projectId): void
    {
        $this->projectId = $projectId;
    }

    /**
     * @return string
     */
    public function getExtendedDocumentKey(): string
    {
        return $this->extendedDocumentKey;
    }

    /**
     * @param string $extendedDocumentKey
     */
    public function setExtendedDocumentKey(string $extendedDocumentKey): void
    {
        $this->extendedDocumentKey = $extendedDocumentKey;
    }

    /**
     * @return string
     */
    public function getInfo(): string
    {
        return $this->info;
    }

    /**
     * @param string $info
     */
    public function setInfo(string $info): void
    {
        $this->info = $info;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     */
    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface|null $createdAt
     */
    public function setCreatedAt(?DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return bool
     */
    public function isTransferred(): bool
    {
        return $this->isTransferred;
    }

    /**
     * @param bool $isTransferred
     */
    public function setIsTransferred(bool $isTransferred): void
    {
        $this->isTransferred = $isTransferred;
    }

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    /**
     * @param bool $isDeleted
     */
    public function setIsDeleted(bool $isDeleted): void
    {
        $this->isDeleted = $isDeleted;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getTransferredOn(): ?DateTimeInterface
    {
        return $this->transferredOn;
    }

    /**
     * @param DateTimeInterface|null $transferredOn
     */
    public function setTransferredOn(?DateTimeInterface $transferredOn): void
    {
        $this->transferredOn = $transferredOn;
    }

    /**
     * @return int
     */
    public function getTransferCount(): int
    {
        return $this->transferCount;
    }

    /**
     * @param int $transferCount
     */
    public function setTransferCount(int $transferCount): void
    {
        $this->transferCount = $transferCount;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     */
    public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
    }
}
