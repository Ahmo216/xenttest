<?php

declare(strict_types=1);

namespace App\Core\Helpers;

class AppVersion
{
    private $major;

    private $minor;

    private $patch;

    public function __construct()
    {
        include base_path() . '/version.php';

        $revisionParts = explode('.', $version_revision);

        $this->major = $revisionParts[0];
        $this->minor = $revisionParts[1];
        $this->patch = $revisionParts[2] ?? null;
    }

    public function getMajor(): string
    {
        return $this->major;
    }

    public function getMinor(): string
    {
        return $this->minor;
    }

    public function getPatch(): ?string
    {
        return $this->patch;
    }

    public static function major(): string
    {
        return app(AppVersion::class)->getMajor();
    }

    public static function minor(): string
    {
        return app(AppVersion::class)->getMinor();
    }

    public static function patch(): ?string
    {
        return app(AppVersion::class)->getPatch();
    }
}
