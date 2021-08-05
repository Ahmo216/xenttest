<?php

declare(strict_types=1);

namespace Xentral\Modules\FileManagement\Helper;

use Xentral\Components\Util\StringUtil;

/**
 * Class DmsPathHelper
 *
 * Helper class to determine the absolute path of a file based on the file version id.
 *
 * In the DMS (=DateiManagementSystem) the path is calculated based on the id of the
 * file version entry in the `datei_version` table.
 *
 * Example: FileVersion database id = 12345
 * The absolute path to the file is <userdata_directory>/dms/<database_name>/d12/d34/d5/12345
 * There is no file extension
 */
class DmsPathHelper
{
    /** @var string $dmsBasePath */
    private $dmsBasePath;

    /**
     * @param string $dmsBasePath root directory of the dms system e.g. '/root/userdata/dms/<db_name>/
     */
    public function __construct(string $dmsBasePath)
    {
        $this->dmsBasePath = $dmsBasePath;
    }

    /**
     * Creates a path for the dms from a file version id.
     * The path is NOT created in the actual file system.
     *
     * @example getPathFromVersion(12345) returns '/d12/d34/d5'
     *
     * @param int $fileVersionId
     *
     * @return string
     */
    public function getPathFromVersion(int $fileVersionId): string
    {
        $idParts = str_split((string)$fileVersionId, 2);
        $pathParts = [];
        foreach ($idParts as $pathPart) {
            $pathParts[] = "d{$pathPart}";
        }

        return $this->assemblePath($pathParts);
    }

    /**
     * Creates a path for the dms from a file version id.
     * The path is NOT created in the actual file system.
     *
     * @example getPathFromVersion(12345) returns '/d12/d34/d5'
     *
     * @param int $fileVersionId
     *
     * @return string
     */
    public function getFilePathFromVersion(int $fileVersionId): string
    {
        return $this->assemblePath([
            $this->getPathFromVersion($fileVersionId),
            (string)$fileVersionId,
        ]);
    }

    /**
     * Creates a path for the dms from a file version id.
     * The path is NOT created in the actual file system.
     *
     * @example getAbsolutePathFromVersion(12345) returns '/<dms_root>/d12/d34/d5'
     *
     * @param int $fileVersionId
     *
     * @return string
     */
    public function getAbsolutePathFromVersion(int $fileVersionId): string
    {
        return $this->assemblePath([
            $this->dmsBasePath,
            $this->getPathFromVersion($fileVersionId),
        ]);
    }

    /**
     * Creates a path for the dms from a file version id.
     * The path is NOT created in the actual file system.
     *
     * @example getAbsolutePathFromVersion(12345) returns '/<dms_root>/d12/d34/d5/12345'
     *
     * @param int $fileVersionId
     *
     * @return string
     */
    public function getAbsoluteFilePathFromVersion(int $fileVersionId): string
    {
        return $this->assemblePath([
            $this->dmsBasePath,
            $this->getPathFromVersion($fileVersionId),
            (string)$fileVersionId,
        ]);
    }

    /**
     * Creates a path for the dms cache file from a file version id.
     *
     * Note: The path is NOT created in the actual file system.
     *
     * @example getCacheFilePathFromVersion(12345) returns '/cache/d12/d34/d5/12345_100_100'
     *
     * @param int $fileVersionId
     *
     * @return string
     */
    public function getCacheFilePathFromVersion(int $fileVersionId): string
    {
        return $this->assemblePath([
            'cache',
            $this->getPathFromVersion($fileVersionId),
            "{$fileVersionId}_100_100",
        ]);
    }

    /**
     * Creates a path for the dms cache file from a file version id.
     *
     * Note: The path is NOT created in the actual file system.
     *
     * @example getAbsoluteCacheFilePathFromVersion(12345) returns '/<dms_root>/cache/d12/d34/d5/12345_100_100'
     *
     * @param int $fileVersionId
     *
     * @return string
     */
    public function getAbsoluteCacheFilePathFromVersion(int $fileVersionId): string
    {
        return $this->assemblePath([
            $this->dmsBasePath,
            'cache',
            $this->getPathFromVersion($fileVersionId),
            "{$fileVersionId}_100_100",
        ]);
    }

    /**
     * @param string[] $pathParts
     *
     * @return string
     */
    private function assemblePath(array $pathParts): string
    {
        $path = '';
        foreach ($pathParts as $pathPart) {
            if (
                !StringUtil::endsWith($path, DIRECTORY_SEPARATOR)
                && !StringUtil::startsWith($pathPart, DIRECTORY_SEPARATOR)
            ) {
                $path .= DIRECTORY_SEPARATOR;
            }

            $path .= $pathPart;
        }

        return str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $path);
    }
}
