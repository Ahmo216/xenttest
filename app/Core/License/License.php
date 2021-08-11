<?php

namespace App\Core\License;

use DateTimeImmutable;

interface License
{
    public function getMaxUsers(): int;

    public function getMaxLightUsers(): int;

    public function isDemo(): bool;

    public function isExpired(): bool;

    public function expiresAt(): DateTimeImmutable;

    public function isSuspended(): bool;

    public function isCloud(): bool;

    public function getVersionName(): string;

    public function getCustomProperty(string $key);
}
