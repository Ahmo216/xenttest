<?php

namespace App\Core\License;

use Carbon\CarbonImmutable;
use DateTimeImmutable;

class IonCubeLicense implements License
{
    /** @var array */
    private $licenseProperties;

    public function __construct(array $licenseProperties)
    {
        $this->licenseProperties = $licenseProperties;
    }

    public function getMaxUsers(): int
    {
        if (!isset($this->licenseProperties['maxuser']['value'])) {
            return 0;
        }

        return (int)$this->licenseProperties['maxuser']['value'];
    }

    public function getMaxLightUsers(): int
    {
        if (!isset($this->licenseProperties['maxlightuser']['value'])) {
            return 0;
        }

        return (int)$this->licenseProperties['maxlightuser']['value'];
    }

    public function isDemo(): bool
    {
        return !empty($this->licenseProperties['testlizenz']['value']);
    }

    public function isExpired(): bool
    {
        return now()->isAfter($this->expiresAt());
    }

    public function expiresAt(): DateTimeImmutable
    {
        if (!isset($this->licenseProperties['expdate']['value'])) {
            return CarbonImmutable::now()->setTimestamp(0);
        }

        return CarbonImmutable::now()->setTimestamp((int)$this->licenseProperties['expdate']['value']);
    }

    public function isSuspended(): bool
    {
        // TODO: whats the implementation of this?
        return false;
    }

    public function getVersionName(): string
    {
        return 'Enterprise Version';
    }

    public function getCustomProperty(string $key)
    {
        if (!array_key_exists($key, $this->licenseProperties)) {
            throw LicenseException::missingProperty($key);
        }

        return $this->licenseProperties[$key]['value'];
    }
}
