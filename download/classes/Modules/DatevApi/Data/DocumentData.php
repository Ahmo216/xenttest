<?php

declare(strict_types=1);

namespace Xentral\Modules\DatevApi\Data;

use DateTimeImmutable;
use DateTimeInterface;
use Exception;

final class DocumentData
{
    /** @var int $projectId */
    private $projectId;

    /** @var DateTimeInterface $documentDate */
    private $documentDate;

    /** @var string $yearMonth */
    private $yearMonth;

    /** @var string $documentNumber */
    private $documentNumber;

    /** @var float $debit */
    private $debit;

    /** @var string $currency */
    private $currency;

    /** @var int $documentId */
    private $documentId;

    /** @var int $addressId */
    private $addressId;

    /** @var string $vatNumber */
    private $vatNumber;

    /** @var string $customerNumber */
    private $customerNumber;

    /** @var string $customerName */
    private $customerName;

    /** @var string $customerCity */
    private $customerCity;

    /** @var string $customerCountry */
    private $customerCountry;

    /** @var bool $isVatFree */
    private $isVatFree;

    /** @var string $onlineOrderId */
    private $onlineOrderId;

    private function __construct()
    {
    }

    /**
     * @param array $dbData
     *
     * @return DocumentData
     */
    public static function fromDbState(array $dbData): DocumentData
    {
        $documentData = new self();
        $documentData->projectId = (int)$dbData['project'];
        try {
            $documentData->documentDate = new DateTimeImmutable($dbData['document_date']);
        } catch (Exception $e) {
        }
        $documentData->yearMonth = $dbData['year_month'];
        $documentData->documentNumber = $dbData['document_number'];
        $documentData->debit = (float)$dbData['debit'];
        $documentData->currency = $dbData['currency'];
        $documentData->documentId = (int)$dbData['document_id'];
        $documentData->addressId = (int)$dbData['address_id'];
        $documentData->vatNumber = $dbData['vat_number'];
        $documentData->customerNumber = $dbData['customer_number'];
        $documentData->customerName = $dbData['customer_Name'];
        $documentData->customerCity = $dbData['customer_city'];
        $documentData->customerCountry = $dbData['customer_country'];
        $documentData->isVatFree = (bool)$dbData['vat_free'];
        $documentData->onlineOrderId = (string)$dbData['internet'];

        return $documentData;
    }

    /**
     * @return int
     */
    public function getProjectId(): int
    {
        return $this->projectId;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDocumentDate(): ?DateTimeInterface
    {
        return $this->documentDate;
    }

    /**
     * @return string
     */
    public function getYearMonth(): string
    {
        return $this->yearMonth;
    }

    /**
     * @return string
     */
    public function getDocumentNumber(): string
    {
        return $this->documentNumber;
    }

    /**
     * @param string $documentNumber
     */
    public function setDocumentNumber(string $documentNumber): void
    {
        $this->documentNumber = $documentNumber;
    }

    /**
     * @return float
     */
    public function getDebit(): float
    {
        return $this->debit;
    }

    /**
     * @param float $debit
     */
    public function setDebit(float $debit): void
    {
        $this->debit = $debit;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @return int
     */
    public function getDocumentId(): int
    {
        return $this->documentId;
    }

    /**
     * @return int
     */
    public function getAddressId(): int
    {
        return $this->addressId;
    }

    /**
     * @return string
     */
    public function getVatNumber(): string
    {
        return $this->vatNumber;
    }

    /**
     * @return string
     */
    public function getCustomerNumber(): string
    {
        return $this->customerNumber;
    }

    /**
     * @return string
     */
    public function getCustomerName(): string
    {
        return $this->customerName;
    }

    /**
     * @return string
     */
    public function getCustomerCity(): string
    {
        return $this->customerCity;
    }

    /**
     * @return string
     */
    public function getCustomerCountry(): string
    {
        return $this->customerCountry;
    }

    /**
     * @return bool
     */
    public function isVatFree(): bool
    {
        return $this->isVatFree;
    }

    /**
     * @return string
     */
    public function getOnlineOrderId(): string
    {
        return $this->onlineOrderId;
    }
}
