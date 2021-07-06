<?php

namespace Xentral\Modules\CustomCodeImporter;

class FileOverview
{
    /** @var array */
    private $clonedFiles;

    /**
     * FileOverview constructor.
     *
     * @param array $clonedFiles
     */
    public function __construct(array $clonedFiles)
    {
        $this->clonedFiles = $clonedFiles;
    }

    /**
     * Get an array of custom files that are currently applied to the system.
     *
     * @return array
     */
    public function getFilesInProjectTree(): array
    {
        $projectRoot = dirname(dirname(dirname(__DIR__)));

        $files = [];
        foreach ($this->clonedFiles as $file) {
            if (!file_exists($projectRoot . DIRECTORY_SEPARATOR . $file)) {
                continue;
            }

            $files[] = $file;
        }

        return $files;
    }
}
