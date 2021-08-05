<?php

declare(strict_types=1);

namespace Xentral\Modules\DatevApi\Data;

final class AccountSettingsData
{
    /** @var int $id */
    private $id;

    /** @var string $adviserNumber */
    private $adviserNumber;

    /** @var string $mandatorNumber */
    private $mandatorNumber;

    /** @var int $project */
    private $project;

    /** @var string $paymentMethod */
    private $paymentMethod;

    /** @var string $commercialYear */
    private $commercialYear;

    /** @var int $nominalAccountLength */
    private $nominalAccountLength;

    private function __construct()
    {
    }

    /**
     * @param array $data
     *
     * @return AccountSettingsData
     */
    public static function fromDbState(array $data): AccountSettingsData
    {
        $accountSettingsData = new self();
        $accountSettingsData->id = (int)$data['id'];
        $accountSettingsData->adviserNumber = $data['adviser_number'];
        $accountSettingsData->mandatorNumber = $data['mandator_number'];
        $accountSettingsData->project = (int)$data['project'];
        $accountSettingsData->paymentMethod = $data['payment_method'];
        $accountSettingsData->commercialYear = $data['commercial_year'];
        $accountSettingsData->nominalAccountLength = (int)$data['nominal_account_length'];

        return $accountSettingsData;
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
    public function getAdviserNumber(): string
    {
        return $this->adviserNumber;
    }

    /**
     * @return string
     */
    public function getMandatorNumber(): string
    {
        return $this->mandatorNumber;
    }

    /**
     * @return int
     */
    public function getProject(): int
    {
        return $this->project;
    }

    /**
     * @return string
     */
    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    /**
     * @return string
     */
    public function getCommercialYear(): string
    {
        return $this->commercialYear;
    }

    /**
     * @return int
     */
    public function getNominalAccountLength(): int
    {
        return $this->nominalAccountLength;
    }
}
